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
                $this->showErrorFilter();
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


            if(isset($_GET['order']) && isset($_GET['sortby'])) {
                $sortby = $_GET['sortby'];
                $order = $_GET['order'];

                if(isset($paramers[$sortby]) && isset($paramers[$order])) {

                    $reviews = $this->model->orderByField($sortby, $order);
        
                    $this->view->response($reviews);
                    
                }
                else {
                    $this->showErrorParams();
                }
            }
            else {
                $this->showErrorIncomplete();
            }
        } //paginar
        else if(isset($_GET['page']) && isset($_GET['limit']) && !isset($_GET['order']) && !isset($_GET['sortby']) && !isset($_GET['filter'])) {
            $page = $_GET['page'];
            $limit = $_GET['limit'];

            //los valores deben ser numeros
             if (is_numeric($page) && (is_numeric($limit))) {

                    //calcular cantidad de paginas total
                    $all = $this->model->getAll(); //obtiene todas las reseñas
                    $pages = count($all); //obtiene el total de valores
                    $pages /= $limit; //divide el total de valores por el limite usado.
                    $pages = ceil($pages); //redondear cifra para arriba.

                    if($page > 0 && $limit > 0) { //verificar que el valor ingresado sea minimo 1.
                        
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
            if(is_numeric($page) && (is_numeric($limit)) && isset($_GET['order']) && isset($_GET['sortby'])) {
                //calcular cantidad de paginas total
                    $all = $this->model->getAll(); //obtiene todas las reseñas
                    $pages = count($all); //obtiene el total de valores
                    $pages /= $limit; //divide el total de valores por el limite usado.
                    $pages = ceil($pages); //redondear cifra para arriba.

                    if($page > 0 && $limit > 0) { //verificar que el valor ingresado sea minimo 1.
                    
                        $offset = ($page -1) *  $limit;
    
                        $reviews = $this->model->OrderAndPaginate($sortby, $order, $limit, $offset);
    
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
        }//filtrar y ordenar
        else if(isset($_GET['filter']) && isset($_GET['order']) && isset($_GET['sortby']) && !isset($_GET['page']) && !isset($_GET['limit'])) {
            $filter = $_GET['filter'];
            $sortby = $_GET['sortby'];
            $order = $_GET['order'];

            $ordering = "ORDER BY $sortby $order "; 

            if(isset($paramers[$sortby]) && isset($paramers[$order])) { //solo si los campos existen en la tabla se arma la sentencia con las variables
                
            }
        }//filtrar y paginar
        else if(isset($_GET['filter']) && isset($_GET['page']) && isset($_GET['limit']) && !isset($_GET['order']) && !isset($_GET['sortby'])) {
            $page = $_GET['page'];
            $limit = $_GET['limit'];
            $filter = $_GET['filter'];

                if (is_numeric($page) && (is_numeric($limit))) {

                    $start = ($page -1) *  $limit;

                    $paginate =  "LIMIT $limit OFFSET $start ";

             
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

           
            }

        }

        
    }

    public function getReviewsssss() {
        $paramers = $this->paramers(); //evitar inyecciones sql
        
        //variables creadas con valor nulo, para evitar warnings de variable indefinida, en caso de que no se utilice en alguno de los if.

        $filter = null;
        $sortby = null;
        $order = null;
        $page = null;
        $limit = null;
        $start = null;
        
   
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
                $this->showErrorFilter();
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

            if(isset($_GET['order']) && isset($_GET['sortby'])) {
                $sortby = $_GET['sortby'];
                $order = $_GET['order'];

                if(isset($paramers[$sortby]) && isset($paramers[$order])) {

                    $reviews = $this->model->orderByField($sortby, $order);
        
                    $this->view->response($reviews);
                    
                }
                else {
                    $this->showErrorParams();
                }
            }
            else {
                $this->showErrorIncomplete();
            }
            
        } 
        else if(isset($_GET['order']) && isset($_GET['sortby']) && isset($_GET['filter']) && !isset($_GET['page']) && !isset($_GET['limit'])) {
            echo "holaaa";
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
        $id = $params[':ID'];

        $review = $this->model->get($id);
        if ($review) {
            $this->model->delete($id);
            $this->view->response("La reseña se eliminó con éxito con el id=$id.", 200);
        } else 
            $this->view->response("La reseña con el id=$id no existe.", 404);
    }   

    //insertar reseña

    public function insertReview($params = null) {
        $review = $this->getData();

        if (empty($review->author) || empty($review->comment) || empty($review->id_Serie_fk)) {
            $this->view->response("Complete los datos", 400);
        } else {
            $error = $this->model->insert($review->author, $review->comment, $review->id_Serie_fk);
            if($error) {
                $this->view->response("Id_serie incorrecto.", 400);
            }
            else {
                $this->view->response("La reseña se insertó con éxito.", 201);
            }

        }
    } 

    //actualizar reseña

      public function updateReview($params = null) {
        $id = $params[':ID'];
        $review = $this->getData();

        if (empty($review->author) || empty($review->comment) || empty($review->id_Serie)) {
            $this->view->response("Complete los datos.", 400);
     
        } else {
            $error = $this->model->update($id, $review->author, $review->comment, $review->id_Serie);
            if($error) {
                $this->view->response("Id_serie incorrecto.", 400);
            }
            else {
                $this->view->response("La reseña se modifico con éxito con el id=$id.", 201);
            }
        }
    } 


    //funciones de error

    public function showErrorFilter() {
        $this->view->response("No se ha encontrado contenido.", 400);
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
        $this->view->response("Solo existen $pages paginas.", 400);
        die();
    }
    
}
    
 

