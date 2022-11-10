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

    function makeAll ($filter = null, $sortby = null , $order = null , $start= null, $limit= null) {
        $sql = "SELECT * FROM review";
        $filtering = " WHERE score > ? ";
        $order = "ORDER BY $sortby $order ";
        $paginate =  "LIMIT $limit OFFSET $start" ;
        $query = $this->db->prepare($sql . $filtering . $order . $paginate);
        $query->execute([$filter]);
        $reviews = $query->fetchAll(PDO::FETCH_OBJ);
        return $reviews;
    }




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
    function sortbyorder ($sortby = null , $order = null ){
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
        $query = $this->db->prepare("SELECT id_review, author, comment, name, id_Serie_fk FROM reviews a INNER JOIN serie b ON a.id_Serie_fk = b.id_serie WHERE name LIKE '$filter%'");
        $query->execute();
        $reviews = $query->fetchAll(PDO::FETCH_OBJ);
        return $reviews;
    }


 

} 


    