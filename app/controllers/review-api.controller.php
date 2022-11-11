<?php

require_once "./app/models/review.model.php";

require_once "./app/views/api.view.php";

class ReviewApiController {

    private $model;
    private $view;

    private $data;

    public function __construct() {
        
        $this->model = new ReviewModel();
        $this->view = new ApiView();
        
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
        
        //variables creadas con valor nulo, para evitar warnings de variable indefinida, en caso de que no se utilice en alguno de los if.

        $filter = null;
        $sortby = null;
        $order = null;
        $page = null;
        $limit = null;
        $start = null;
        $verifyfilter = null;

        //strings para armar sentencias segun que condiciones se cumplan.


        //ordring y paginate en null ya que al utilizar variables, el valor se asigna segun que condiciones cumplan y previniedo inyecciones sql

        $ordering = null; 
        $paginate =  null; 
        $sentence = null; 
        $sql = "SELECT id_review, author, comment, name, id_Serie_fk FROM reviews a INNER JOIN serie b ON a.id_Serie_fk = b.id_serie ";
        $filterstring = "WHERE name LIKE ? "; 
   

        //ninguno
        if(!isset($_GET['filter']) && !isset($_GET['sortby']) && !isset($_GET['order']) && !isset($_GET['page']) && !isset($_GET['limit'])) {

            $sentence = $sql;

        }//filtrar
        else if(isset($_GET['filter']) && !isset($_GET['sortby']) && !isset($_GET['order']) && !isset($_GET['page']) && !isset($_GET['limit'])) {
            $filter = $_GET['filter'];

            $sentence = $sql . $filterstring;

        }//ordenar por id
        else if(isset($_GET['order']) && !isset($_GET['sortby']) && !isset($_GET['filter']) && !isset($_GET['page']) && !isset($_GET['limit'])) {
            $order = $_GET['order'];
            $orderbyid = "ORDER BY id_review $order "; 

            if(isset($paramers[$order])) { //solo si los campos existen en la tabla se arma la sentencia con las variables
                $sentence = $sql . $orderbyid;
            }
        }//ordenar por campo
        else if(isset($_GET['order']) && isset($_GET['sortby']) && !isset($_GET['filter']) && !isset($_GET['page']) && !isset($_GET['limit'])) {
            $sortby = $_GET['sortby'];
            $order = $_GET['order'];
            $ordering = "ORDER BY $sortby $order "; 

            if(isset($paramers[$sortby]) && isset($paramers[$order])) { //solo si los campos existen en la tabla se arma la sentencia con las variables
                $sentence = $sql . $ordering;
            }
        }//paginar
        else if(isset($_GET['page']) && isset($_GET['limit']) && !isset($_GET['order']) && !isset($_GET['sortby']) && !isset($_GET['filter']))  {
            $page = $_GET['page'];
            $limit = $_GET['limit'];

            //el usuario usa la pagina 1 en adelante y los valores deben ser numeros
            if (is_numeric($page) && (is_numeric($limit))) {

                $start = ($page -1) *  $limit;

                $paginate =  "LIMIT $limit OFFSET $start ";

                $sentence = $sql . $paginate;
            }
        } //ordenar y paginar
        else if(isset($_GET['order']) && isset($_GET['sortby']) && isset($_GET['page']) && isset($_GET['limit']) && !isset($_GET['filter']) ) {

            $sortby = $_GET['sortby'];
            $order = $_GET['order'];
            $page = $_GET['page'];
            $limit = $_GET['limit'];

            if(isset($paramers[$sortby]) && isset($paramers[$order]) && is_numeric($page) && (is_numeric($limit))) { //solo si los campos existen en la tabla se arma la sentencia con las variables y los valores de page y limit son numeros, se cumplen las condiciones.

                $start = ($page -1) *  $limit;

                $ordering = "ORDER BY $sortby $order "; 
                $paginate =  "LIMIT $limit OFFSET $start ";

                $sentence = $sql . $ordering . $paginate;
            }
        }//filtrar y ordenar
        else if(isset($_GET['filter']) && isset($_GET['order']) && isset($_GET['sortby']) && !isset($_GET['page']) && !isset($_GET['limit'])) {
            $filter = $_GET['filter'];
            $sortby = $_GET['sortby'];
            $order = $_GET['order'];

            $ordering = "ORDER BY $sortby $order "; 

            if(isset($paramers[$sortby]) && isset($paramers[$order])) { //solo si los campos existen en la tabla se arma la sentencia con las variables
                $sentence = $sql . $filterstring . $ordering;
            }
        }//filtrar y paginar
        else if(isset($_GET['filter']) && isset($_GET['page']) && isset($_GET['limit']) && !isset($_GET['order']) && !isset($_GET['sortby'])) {
            $page = $_GET['page'];
            $limit = $_GET['limit'];
            $filter = $_GET['filter'];

                if (is_numeric($page) && (is_numeric($limit))) {

                    $start = ($page -1) *  $limit;

                    $paginate =  "LIMIT $limit OFFSET $start ";

                    $sentence = $sql . $filterstring . $paginate;
                }
        }//filtrar, ordenar y paginar
        else if(isset($_GET['filter']) && isset($_GET['order']) && isset($_GET['sortby']) && isset($_GET['page']) && isset($_GET['limit'])) {
            
            $filter = $_GET['filter'];
            $sortby = $_GET['sortby'];
            $order = $_GET['order'];
            $page = $_GET['page'];
            $limit = $_GET['limit'];

            if(isset($paramers[$sortby]) && isset($paramers[$order]) && is_numeric($page) && (is_numeric($limit))) { //solo si los campos existen en la tabla se arma la sentencia con las variables y los valores de page y limit son numeros, se cumplen las condiciones.

                $start = ($page -1) *  $limit;

                $ordering = "ORDER BY $sortby $order "; 
                $paginate =  "LIMIT $limit OFFSET $start ";

                $sentence = $sql . $filterstring . $ordering . $paginate;
            }

        }

        //seccion ejecucion de la sentencia y retorno de la respuesta

        if(!empty($filter)) {   //si se usa el filtro, usar variable en execute
            $verifyfilter = $filter;
        }

        $reviews = $this->model->makeAll($sentence, $verifyfilter);

        if($reviews) {
            $this->view->response($reviews);
        }
        else {
            $this->view->response("Parametros incorrectos",400);
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
            $this->view->response("la reseña con el id: $id no existe", 404);
        } 
    }

    //borrar reseña

    public function deleteReview($params = null) {
        $id = $params[':ID'];

        $review = $this->model->get($id);
        if ($review) {
            $this->model->delete($id);
            $this->view->response("La reseña se eliminó con éxito con el id=$id", 200);
        } else 
            $this->view->response("La reseña con el id=$id no existe", 404);
    }   

    //insertar reseña

    public function insertReview($params = null) {
        $review = $this->getData();

        if (empty($review->author) || empty($review->about) || empty($review->comment) || empty($review->id_Serie_fk)) {
            $this->view->response("Complete los datos", 400);
        } else {
            $error = $this->model->insert($review->author, $review->about, $review->comment, $review->id_Serie_fk);
            if($error) {
                $this->view->response("Id_serie incorrecto", 400);
            }
            else {
                $this->view->response("La reseña se insertó con éxito", 201);
            }

        }
    } 

    //actualizar reseña

      public function updateReview($params = null) {
        $id = $params[':ID'];
        $review = $this->getData();

        if (empty($review->author) || empty($review->about) || empty($review->comment) || empty($review->id_Serie)) {
            $this->view->response("Complete los datos", 400);
     
        } else {
            $error = $this->model->update($id, $review->author, $review->about, $review->comment, $review->id_Serie);
            if($error) {
                $this->view->response("Id_serie incorrecto", 400);
            }
            else {
                $this->view->response("La reseña se modifico con éxito con el id=$id", 201);
            }
        }
    } 
    
}
    
 

