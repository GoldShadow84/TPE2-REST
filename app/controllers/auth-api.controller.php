<?php
require_once './app/views/api.view.php';
require_once './app/helpers/auth-api.helper.php';
require_once './app/models/user.model.php';
require_once './app/token.php';

function base64url_encode($data) {
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}

class AuthApiController {
  
    private $view;
    private $authHelper;
    private $model;
    private $data;

    public function __construct() {
      
        $this->view = new ApiView();
        $this->authHelper = new AuthApiHelper();
        $this->model = new UserModel();

        // lee el body del request
        $this->data = file_get_contents("php://input");
    }

    private function getData() {
        return json_decode($this->data);
    }

    public function getToken($params = null) {
        // Obtener "Basic base64(user:pass)
        $basic = $this->authHelper->getAuthHeader();
        
        if(empty($basic)){
            $this->view->response('No autorizado', 401);
            return;
        }
        $basic = explode(" ",$basic); // ["Basic" "base64(user:pass)"]
        if($basic[0]!="Basic"){
            $this->view->response('La autenticación debe ser Basic', 401);
            return;
        }

        //validacion usuario:contraseña
        $userpass = base64_decode($basic[1]); // user:pass
        $userpass = explode(":", $userpass);
        $user = $userpass[0];
        $pass = $userpass[1];


         $email = $user;
         $password = $pass;

         //obtenemos los datos guardados en la tabla de usuarios.
        $user = $this->model->getByEmail($email);

        //verificamos que las contraseñas y el email/username coincidan.

        if (!empty($user) && password_verify($password, $user->password)) {

            //  crear un token
            $header = array(
                'alg' => 'HS256',
                'typ' => 'JWT'
            );
            $payload = array(
                'id' => 1,
                'name' => "Nico",
                'exp' => time()+3600
            );

            $header = base64url_encode(json_encode($header));
            $payload = base64url_encode(json_encode($payload));
            $keyToken = getKeyToken();
            $signature = hash_hmac('SHA256', "$header.$payload", $keyToken, true);
            $signature = base64url_encode($signature);
            $token = "$header.$payload.$signature";
            $this->view->response($token);
        
        } 
        else {
            $this->view->response('No autorizado', 401);
        } 

 }


}
