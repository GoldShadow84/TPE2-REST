<?php

class ReviewModel {

    private $db;

    //abrimos conexion con la base de datos db_series
    public function __construct() {
        $this->db = new PDO('mysql:host=localhost;'.'dbname=db_series;charset=utf8', 'root', '');
    }

    //devuelve lista de reseñas completa
    public function getAll() {

        $query = $this->db->prepare("SELECT id_review, author, comment, name, id_Serie_fk FROM reviews a INNER JOIN serie b ON a.id_Serie_fk = b.id_serie ");
        $query->execute();
        $reviews = $query->fetchAll(PDO::FETCH_OBJ);
        return $reviews;
    }

    //obtener informacion de una reseña en particular, segun su id
    public function get($id) {
        // 1. abro conexión a la DB
        // ya esta abierta por el constructor de la clase

        // 2. ejecuto la sentencia (2 subpasos)

        $query = $this->db->prepare("SELECT id_review, author, comment, name, id_Serie_fk FROM reviews a INNER JOIN serie b ON a.id_Serie_fk = b.id_serie  WHERE id_review = ?");
        $query->execute(array($id));

        // 3. obtengo los resultados
        $review = $query->fetchAll(PDO::FETCH_OBJ); // devuelve un arreglo de objetos
        
        return $review;
    }

    public function makeAll($sentence = null, $verifyfilter = null) {

        $l = "%";

        $query = $this->db->prepare($sentence);

        if($verifyfilter) {
            $query->execute([$verifyfilter . $l]);
        }
        else {
            $query->execute();
        }

        $review = $query->fetchAll(PDO::FETCH_OBJ); 
        
        return $review;
    }


    //realizar todas las acciones
    /*function makeAll ($filter = null, $sortby = null , $order = null , $offset= null, $limit= null, $aux = null) {

        //SELECT id_review, author, comment, name, id_Serie_fk FROM reviews a INNER JOIN serie b ON a.id_Serie_fk = b.id_serie WHERE name LIKE "better%" ORDER BY id_review DESC LIMIT 4 OFFSET 0;

        $sentence = null;
        $l = '%'; //para concatenar y usar el filtro.
        $sql = "SELECT id_review, author, comment, name, id_Serie_fk FROM reviews a INNER JOIN serie b ON a.id_Serie_fk = b.id_serie "; 
        $filterstring = "WHERE name LIKE ? "; 
        $ordering = "ORDER BY $sortby $order "; //VER SI AQUI ESTA EL PROBLEMA EN FILTRAR - PAGINAR Y ARREGLAR sortyby-orderby a la vez
        $paginate =  "LIMIT $limit OFFSET $offset ";

        if(empty($_GET['filter']) && empty($_GET['sortby']) && empty($_GET['order']) && empty($_GET['page']) && empty($_GET['limit'])) {

            $sentence = $sql;

        }






            $query = $this->db->prepare($sentence); //preparar sentencia formada segun las condiciones cumplidas
            if(!empty($filter)) {   //si se usa el filtro, usar variable en execute
                $query->execute([$filter . $l]);
            }
            else {
                $query->execute();
            }
    
            $reviews = $query->fetchAll(PDO::FETCH_OBJ);
       

        return $reviews;
        
    }   
    
        */



    //añadir una nueva reseña
    public function insert($author, $comment, $id_serie) {
    
        $error = false;

        try {
            $query = $this->db->prepare("INSERT INTO reviews (author,  comment, id_Serie_fk) VALUES (?, ?, ?)");
            $query->execute([$author, $comment, $id_serie]);
        }
        catch(PDOException $e) {
            $error = true;
        }
        
        return $error;
    } 
    
   
 
    //borrar una reseña segun su id
    public function delete($id) {
   
        $query = $this->db->prepare("DELETE FROM reviews WHERE id_review = ?");
        $query->execute([$id]);
    }

    //actualizar una reseña segun su id

   public function update($id, $author, $comment, $id_serie) {
        $error = false;

        try {
            $query = $this->db->prepare('UPDATE reviews SET author = ?, comment = ?, id_Serie_fk = ? WHERE id_review = ?');

            $query->execute([$author, $comment, $id_serie, $id]);
        }
        catch(PDOException $e) {
            $error = true;
        }

        return $error;
    }

    
    //ordenar por id
    function orderdesc () {
        $query = $this->db->prepare("SELECT * FROM reviews ORDER BY id_review desc");
        $query->execute();
        $reviews = $query->fetchAll(PDO::FETCH_OBJ);
        return $reviews;
    }

    //ordenar por id y campo
    function sortbyorder ($sortby = null , $order = null ) {
        $query = $this->db->prepare("SELECT * FROM reviews ORDER BY $sortby $order");
        $query->execute();
        $reviews = $query->fetchAll(PDO::FETCH_OBJ);
        return $reviews;
    }

    //paginar las reseñas
    function paginate ($offset= null, $limit= null) {
        $query = $this->db->prepare("SELECT * FROM reviews LIMIT $limit OFFSET $offset ");
        $query->execute();
        $reviews = $query->fetchAll(PDO::FETCH_OBJ);
         
        return $reviews;
    }

    //filtrar por campo about
    function filter ($filter = null) {
        $l = '%';
        $query = $this->db->prepare("SELECT id_review, author, comment, name, id_Serie_fk FROM reviews a INNER JOIN serie b ON a.id_Serie_fk = b.id_serie WHERE name LIKE ?");
        $query->execute([$filter . $l]);
        $reviews = $query->fetchAll(PDO::FETCH_OBJ);
        return $reviews;
    }


 

} 


    