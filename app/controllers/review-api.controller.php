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

    public function paramers ($params = null) {  //function para reutilizacion de arreglo asociativo.
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


    public function getReviews($params = null) {

/*
     if (isset($_GET['filter'])  && isset($_GET['sortby']) && isset($_GET['order']) && isset($_GET['page']) && isset($_GET['limit'])) {
            $filter = $_GET['filter'];
            $sortby = $_GET['sortby'];
            $order = $_GET['order']; 
            $page = $_GET['page'];
            $limit = $_GET['limit'];
            $start = ($page -1) *  $limit;
           
        $reviews = $this->model->makeAll($filter, $sortby, $order, $start, $limit);
        $this->view->response($reviews);
        }
*/    

        
        

        if(isset($_GET['order']) && !isset($_GET['sortby']) && ($_GET['order'] == 'desc')) {
             $this->orderdesc(); 
        }
        else if(isset($_GET['sortby'])  && isset($_GET['order'])) {
                $paramers =  $this->paramers();
                $sortby = $_GET['sortby'];
                $order = $_GET['order']; 
                if(isset($paramers[$sortby]) && (isset($paramers[$order]))) { 
                    $this->sortby($sortby, $order);
                }
                else {
                    $this->view->response("Campo incorrecto", 400);
                }
            }
            else if (isset($_GET['page']) && (isset($_GET['limit']))) {
                $page = $_GET['page'];
                $limit = $_GET['limit'];
                try {
                    if (is_numeric($page) && (is_numeric($limit))) {
                        $offset = ($page -1) *  $limit;
                        $reviews =  $this->model->paginate($offset, $limit);
                        $this->view->response($reviews);
                       }
                    else {
                        $this->view->response("Debe ingresar un numero", 400);
                    }
                }
                catch (PDOException $e){
                    $this->view->response("Debe ingresar a partir de la pagina numero 1", 400);
                }
            }
        else if (isset($_GET['filter'])) {
           $paramers =  $this->paramers();
           $filter = $_GET['filter'];
           $reviews =  $this->model->filter($filter);
                if(!empty($reviews)) {
                    $this->view->response($reviews);
                }
                else {
                    $this->view->response("No se encontro un registro", 200);
                }
        }
        else {
            $reviews = $this->model->getall();
            $this->view->response($reviews);
        }
        

        
    
    }


    //ordenar por id
    function orderdesc($params = null) {
        $reviews = $this->model->orderdesc();
        $this->view->response($reviews);
    }

    //ordenar por campo e Id
    function sortby($sortby = null, $order = null) {
        $reviews = $this->model->sortbyorder($sortby, $order);
        if ($reviews){
            $this->view->response($reviews);
        }
        else {
            $this->view->response("escribio mal los campos", 400);
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
    
 

