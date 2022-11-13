<?php

require_once "./app/models/review.model.php";
require_once "./app/views/api.view.php";
require_once './app/helpers/auth-api.helper.php';

class ReviewApiController {

    private $model;
    private $view;
    private $data;
    private $authHelper;

    public function __construct() {
        
        $this->model = new ReviewModel();
        $this->view = new ApiView();
        $this->authHelper =  new AuthApiHelper();
        // lee el body del request
        $this->data = file_get_contents("php://input");
    }
    
    private function getData() {
        return json_decode($this->data);
    }

    public function paramers($params = null) {  //function para reutilizacion de arreglo asociativo.
        $paramers = array('id_review' => 'id_review',
        'author' => 'author',
        'about' => 'about',
        'comment' => 'comment',
        'id_Serie' => 'id_Serie',
        'asc' => 'asc',
        'desc' => 'desc'
        );
    return $paramers;
    }

    public function getReviews() {

        $paramers = $this->paramers(); //evitar inyecciones sql
        
        //ninguno
       if(!isset($_GET['filter']) && !isset($_GET['sortby']) && !isset($_GET['order']) && !isset($_GET['page']) && !isset($_GET['limit'])) {

            $reviews = $this->model->getAll();

            $this->view->response($reviews);

        }//filtrar 
        else if(isset($_GET['filter']) && !isset($_GET['sortby']) && !isset($_GET['order']) && !isset($_GET['page']) && !isset($_GET['limit'])) {
            $filter = $_GET['filter'];
            
            $reviews = $this->model->filterByName($filter);
            if($reviews) {
                $this->view->response($reviews);
            }
            else {
                $this->showErrorNotContent();
            }

        }  //ordenar por id
        else if(isset($_GET['order']) && !isset($_GET['sortby']) && !isset($_GET['filter']) && !isset($_GET['page']) && !isset($_GET['limit'])) {
            $order = $_GET['order'];

            if(isset($paramers[$order])) { //solo si los campos existen en la tabla
                $order = $_GET['order'];

                $reviews = $this->model->orderById($order);
    
                $this->view->response($reviews);
                
            }
            else {
                $this->showErrorParams();
             }
            
        } //ordenar por campo
        else if(isset($_GET['order']) && isset($_GET['sortby']) && !isset($_GET['filter']) && !isset($_GET['page']) && !isset($_GET['limit'])) {

                $sortby = $_GET['sortby'];
                $order = $_GET['order'];

                if(isset($paramers[$sortby]) && isset($paramers[$order])) {

                    $reviews = $this->model->orderByField($sortby, $order);
        
                    $this->view->response($reviews);
                    
                }
                else {
                    $this->showErrorParams();
                }


        } //paginar
        else if(isset($_GET['page']) && isset($_GET['limit']) && !isset($_GET['order']) && !isset($_GET['sortby']) && !isset($_GET['filter'])) {
            $page = $_GET['page'];
            $limit = $_GET['limit'];

            //los valores deben ser numeros
             if (is_numeric($page) && (is_numeric($limit))) {

                    if($page > 0 && $limit > 0) { //verificar que el valor ingresado sea minimo 1.

                     //calcular cantidad de paginas total
                    $all = $this->model->getAll(); //obtiene todas las reseñas
                    $pages = count($all); //obtiene el total de valores
                    $pages /= $limit; //divide el total de valores por el limite usado.
                    $pages = ceil($pages); //redondear cifra para arriba.
                        
                        $offset = ($page -1) *  $limit;

                        $reviews = $this->model->paginate($limit, $offset);

                        if($reviews) {
                            $this->view->response($reviews);
                        }
                        else {
                            $this->showErrorPages($pages);
                        }
                    }
                    else {
                        $this->showErrorMinNum(); 
                    }
                }
                else {
                    $this->showErrorNaN();
                }

        } //ordenar y paginar
        else if (isset($_GET['order']) && isset($_GET['sortby']) && isset($_GET['page']) && isset($_GET['limit']) && !isset($_GET['filter'])) {
            
           $sortby = $_GET['sortby'];
            $order = $_GET['order'];
            $page = $_GET['page'];
            $limit = $_GET['limit'];

            //los valores deben ser numeros
            if(is_numeric($page) && (is_numeric($limit))) {

                if($page > 0 && $limit > 0) { //verificar que el valor ingresado sea minimo 1.
                    if(isset($paramers[$sortby]) && isset($paramers[$order])){
                        //calcular cantidad de paginas total
                        $all = $this->model->getAll(); //obtiene todas las reseñas
                        $pages = count($all); //obtiene el total de valores
                        $pages /= $limit; //divide el total de valores por el limite usado.
                        $pages = ceil($pages); //redondear cifra para arriba.
                    
                        $offset = ($page -1) *  $limit;
    
                        $reviews = $this->model->orderAndPaginate($sortby, $order, $limit, $offset);
    
                        if($reviews) {
                            $this->view->response($reviews);
                        }
                        else {
                            $this->showErrorPages($pages);
                        }
                    }
                    else {
                        $this->showErrorParams();
                    }
                }
                else {
                    $this->showErrorMinNum(); 
                }
            }
            else {
                $this->showErrorNaN();
            }

        }//filtrar y ordenar
        else if(isset($_GET['filter']) && isset($_GET['order']) && isset($_GET['sortby']) && !isset($_GET['page']) && !isset($_GET['limit'])) {
            $filter = $_GET['filter'];
            $sortby = $_GET['sortby'];
            $order = $_GET['order'];

            if(isset($paramers[$sortby]) && isset($paramers[$order])) { //solo si los campos existen en la tabla se arma la sentencia con las variables
                $reviews = $this->model->filterAndOrder($sortby, $order, $filter);

                    if($reviews) {
                        $this->view->response($reviews);
                    }
                    else {
                        $this->showErrorNotContent();
                    }
            }
            else {
                $this->showErrorParams();
            }

        }//filtrar y paginar
        else if(isset($_GET['filter']) && isset($_GET['page']) && isset($_GET['limit']) && !isset($_GET['order']) && !isset($_GET['sortby'])) {
            $page = $_GET['page'];
            $limit = $_GET['limit'];
            $filter = $_GET['filter'];

            //los valores deben ser numeros
             if (is_numeric($page) && (is_numeric($limit))) {

                    if($page > 0 && $limit > 0) { //verificar que el valor ingresado sea minimo 1.
                        
                        $offset = ($page -1) *  $limit;

                        $reviews = $this->model->filterAndPaginate($filter, $limit, $offset);

                        if($reviews) {
                            
                            $this->view->response($reviews);
                        }
                        else {
                            $this->showErrorNotContent();
                        }
                    }
                    else {
                        $this->showErrorMinNum(); 
                    }
                }
                else {
                    $this->showErrorNaN();
                }

        }//filtrar, ordenar y paginar
        else if(isset($_GET['filter']) && isset($_GET['order']) && isset($_GET['sortby']) && isset($_GET['page']) && isset($_GET['limit'])) {
            
            $filter = $_GET['filter'];
            $sortby = $_GET['sortby'];
            $order = $_GET['order'];
            $page = $_GET['page'];
            $limit = $_GET['limit'];

            if(isset($paramers[$sortby]) && isset($paramers[$order]) && is_numeric($page) && (is_numeric($limit))) { //solo si los campos existen en la tabla se arma la sentencia con las variables y los valores de page y limit son numeros, se cumplen las condiciones.


                    if($page > 0 && $limit > 0) { //verificar que el valor ingresado sea minimo 1.
                        
                        $offset = ($page -1) *  $limit;

                        $reviews = $this->model->makeAll($filter, $sortby, $order, $offset, $limit);

                        if($reviews) {
                            $this->view->response($reviews);
                        }
                        else {
                            $this->showErrorNotContent();
                        }
                    }
                    else {
                        $this->showErrorMinNum(); 
                    }
           
            }
            else if(!isset($paramers[$sortby]) || !isset($paramers[$order])){
                $this->showErrorParams();
            }
            else if(!is_numeric($page) || !is_numeric($limit)) {
                $this->showErrorNaN();
            }

        }   //si solo esta sortby, mostrar error.
        else if(!isset($_GET['filter']) && isset($_GET['sortby']) || isset($_GET['order']) && !isset($_GET['page']) && !isset($_GET['limit'])) {
            $this->showErrorIncomplete();

        }   //si solo esta limit o page, mostrar error.
        else if(!isset($_GET['filter']) && !isset($_GET['sortby']) && !isset($_GET['order']) && isset($_GET['page']) || isset($_GET['limit'])) {
            $this->showErrorIncomplete();

        }   //si se usa todo, pero falta page o limit, o solo esta sortby, mostrar error
        else if(isset($_GET['filter']) && isset($_GET['order']) || isset($_GET['sortby']) && isset($_GET['page']) || isset($_GET['limit'])) {
            $this->showErrorIncomplete();
        }
            
    }

    //obtener solo una reseña por id

    public function getReview($params = null) {
        // obtengo el id del arreglo de params
        $id = $params[':ID'];
        $review = $this->model->get($id);

        // si no existe devuelvo 404
        if($review) {
            $this->view->response($review);
        }
        else {
            $this->view->response("la reseña con el id: $id no existe.", 404);
        } 
    }

    //borrar reseña

    public function deleteReview($params = null) {
        //si no se esta logeado, no se ejecuta la funcion
        if(!$this->authHelper->isLoggedIn()){
            $this->showErrorNotLoggued();
        }

        $id = $params[':ID'];

        $review = $this->model->get($id);
        if ($review) {
            $this->model->delete($id);
            $this->view->response("La reseña se eliminó con éxito con el id=$id.", 200);
        } else {
            $this->view->response("La reseña con el id=$id no existe.", 404);
        }
    }   

    //verificar que exista la serie vinculada a la reseña nueva
    public function verifyAndPost() {
        //si no se esta logeado, no se ejecuta la funcion
        if(!$this->authHelper->isLoggedIn()){
            $this->showErrorNotLoggued();
        }

        $review = $this->getData();

        if (empty($review->author) || empty($review->comment) || empty($review->name)) {
            $this->view->response("Complete los datos", 400);
        }
        else {

                if(!is_numeric($review->name)) {
                    //traer id_serie al que corresponde el nombre ingresado
                    $id_serie_fk = $this->model->verifyName($review->name);

                    if($id_serie_fk) {

                        $id_serie = $id_serie_fk->id_Serie_fk;

                        $id_serie = intval($id_serie); //convierte de string a int

                        //verificar que los datos ingresados correspondan con su tipo de dos en la base de datos.
                        if(is_numeric($review->author) || is_numeric($review->comment)) {
                            $this->showErrorInvalidType();
                        }
                        else {
                            $this->insertReview($review->author, $review->comment,  $id_serie);
                        } 
                    }
                    else {
                        $this->showErrorNotExist();
                    }
                }
            else {
                $this->showErrorInvalidType();
            }
            
        }
    }

    public function insertReview($author  = null, $comment = null, $id_serie = null) {

        $count = $this->model->insert($author, $comment, $id_serie);

        if($count != 0) {
            $this->view->response("La reseña se insertó con éxito.", 201);
        }
    }

    public function verifyAndPut($params = null) {
        //si no se esta logeado, no se ejecuta la funcion
        if(!$this->authHelper->isLoggedIn()){
            $this->showErrorNotLoggued();
        }

        $id = $params[':ID'];

        $review = $this->getData();

        if (empty($review->author) || empty($review->comment) || empty($review->name)) {
            $this->view->response("Complete los datos", 400);
        }
        else {

                if(!is_numeric($review->name)) {
                    //traer id_serie al que corresponde el nombre ingresado
                    $id_serie_fk = $this->model->verifyName($review->name);

                    if($id_serie_fk) {

                        $id_serie = $id_serie_fk->id_Serie_fk;

                        $id_serie = intval($id_serie); //convierte de string a int

                        //verificar que los datos ingresados correspondan con su tipo de dos en la base de datos.
                        if(is_numeric($review->author) || is_numeric($review->comment)) {
                            $this->showErrorInvalidType();
                        }
                        else {
                            $this->updateReview($id, $review->author, $review->comment, $id_serie);
                        } 
                    }
                    else {
                        $this->showErrorNotExist();
                    }
                }
            else {
                $this->showErrorInvalidType();
            }
            
        }
    }
            
    //actualizar reseña

    public function updateReview($id = null, $author = null, $comment =  null, $id_serie = null) {
        
        $count = $this->model->update($id, $author, $comment, $id_serie);

        //si ninguna fila fue afectada.
        if ($count == "0") {    
            $this->view->response("Error, revise que el id exista, los datos que intenta ingresar no sean incorrectos o iguales a la reseña que intenta modificar.", 400);
        }
        else {
            $this->view->response("La reseña se modifico con éxito con el id=$id.", 201);
        }
    } 
 
    //funciones de error

    public function showErrorNotContent() {
        $this->view->response("No se ha encontrado contenido.", 404);
        die();
    }

    public function showErrorParams() {
        $this->view->response("Parametros incorrectos.", 400);
        die();
    }

    public function showErrorIncomplete() {
        $this->view->response("Falta algun parametro para la peticion que esta solicitando.", 400);
        die();
    }

    public function showErrorNaN() {
        $this->view->response("Debe introducir un numero.", 400);
        die();
    }

    public function showErrorMinNum() {
        $this->view->response("Debe introducir un valor mayor a cero.", 400);
        die();
    }

    public function showErrorPages($pages = null) {
        $this->view->response("Solo existen $pages paginas.", 404);
        die();
    }

    public function showErrorInvalidType() {
        $this->view->response("Verifique el tipo de datos que intenta enviar.", 400);
        die();
    }

    public function showErrorNotExist() {
        $this->view->response("La Serie que esta buscando no existe o no se encuentra registrada.", 404);
        die();
    }

    public function showErrorNotLoggued() {
        $this->view->response("No estas logeado.", 401);
        die();
    }
    
}
    
 

