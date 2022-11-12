<?php

class ReviewModel {

    private $db;

    //abrimos conexion con la base de datos db_series
    public function __construct() {
        $this->db = new PDO('mysql:host=localhost;'.'dbname=db_series;charset=utf8', 'root', '');
    }


    //obtener todas las reseñas
    public function getAll() { 
        $query = $this->db->prepare("SELECT id_review, author, comment, name, id_Serie_fk FROM reviews a INNER JOIN serie b ON a.id_Serie_fk = b.id_serie");
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


    //añadir una nueva reseña
    public function insert($author, $comment, $id_serie_fk) {
    
        $error = false;

        try {
            $query = $this->db->prepare("INSERT INTO reviews (author,  comment, id_Serie_fk) VALUES (?, ?, ?)");
            $query->execute([$author, $comment, $id_serie_fk]);
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
   public function update($id, $author, $comment, $id_serie_fk) {

        try {
            $query = $this->db->prepare("UPDATE reviews SET author = ?, comment = ?, id_Serie_fk = ? WHERE id_review = ?");
            $query->execute([$author, $comment, $id_serie_fk, $id]);
            $count = $query->rowCount(); //obtener cantidad de filas afectadas
        }
        catch(PDOException $e) {
            $count = "error";
        }

        return $count;
    }

    //filtrar por nombre de serie en la tabla reseñas

    function filterByName($filter = null) {
        $percent = "%"; //para concatenar con la variable filter

        $query = $this->db->prepare("SELECT id_review, author, comment, name, id_Serie_fk FROM reviews a INNER JOIN serie b ON a.id_Serie_fk = b.id_serie WHERE name LIKE ?");

        $query->execute([$filter . $percent]);

        $reviews = $query->fetchAll(PDO::FETCH_OBJ);

        return $reviews;
    }

    //Ordenar solo por id

    function orderById($order = null) {  
        $query = $this->db->prepare("SELECT id_review, author, comment, name, id_Serie_fk FROM reviews a INNER JOIN serie b ON a.id_Serie_fk = b.id_serie ORDER BY id_review $order");

        $query->execute();

        $reviews = $query->fetchAll(PDO::FETCH_OBJ);

        return $reviews;
    }

    //Ordenar por Campo

    function orderByField($sortby = null, $order = null) {  
        $query = $this->db->prepare("SELECT id_review, author, comment, name, id_Serie_fk FROM reviews a INNER JOIN serie b ON a.id_Serie_fk = b.id_serie ORDER BY $sortby $order");

        $query->execute();

        $reviews = $query->fetchAll(PDO::FETCH_OBJ);

        return $reviews;
    }

    //Paginar las reseñas

    function paginate($limit = null, $offset = null) {
        $query = $this->db->prepare("SELECT id_review, author, comment, name, id_Serie_fk FROM reviews a INNER JOIN serie b ON a.id_Serie_fk = b.id_serie LIMIT $limit OFFSET $offset");

        $query->execute();

        $reviews = $query->fetchAll(PDO::FETCH_OBJ);

        return $reviews;
    }

    //Ordenar y Paginar las reseñas

    function orderAndPaginate($sortby = null, $order = null, $limit = null, $offset = null) {
        $query = $this->db->prepare("SELECT id_review, author, comment, name, id_Serie_fk FROM reviews a INNER JOIN serie b ON a.id_Serie_fk = b.id_serie ORDER BY $sortby $order LIMIT $limit OFFSET $offset");

        $query->execute();

        $reviews = $query->fetchAll(PDO::FETCH_OBJ);

        return $reviews;
    }

    function filterAndOrder($sortby = null, $order = null, $filter = null) {
        $percent = "%"; //para concatenar con la variable filter

        $query = $this->db->prepare("SELECT id_review, author, comment, name, id_Serie_fk FROM reviews a INNER JOIN serie b ON a.id_Serie_fk = b.id_serie WHERE name LIKE ?ORDER BY $sortby $order");

        $query->execute([$filter . $percent]);

        $reviews = $query->fetchAll(PDO::FETCH_OBJ);

        return $reviews;
    }

    function filterAndPaginate($filter = null, $limit = null, $offset = null) {
        $percent = "%"; //para concatenar con la variable filter

        $query = $this->db->prepare("SELECT id_review, author, comment, name, id_Serie_fk FROM reviews a INNER JOIN serie b ON a.id_Serie_fk = b.id_serie WHERE name LIKE ?LIMIT $limit OFFSET $offset");

        $query->execute([$filter . $percent]);

        $reviews = $query->fetchAll(PDO::FETCH_OBJ);

        return $reviews;
    }

    function makeAll($filter = null, $sortby= null, $order = null, $offset = null, $limit = null) {
        $percent = "%"; //para concatenar con la variable filter

        $query = $this->db->prepare("SELECT id_review, author, comment, name, id_Serie_fk FROM reviews a INNER JOIN serie b ON a.id_Serie_fk = b.id_serie WHERE name LIKE ? ORDER BY $sortby $order LIMIT $limit OFFSET $offset");

        $query->execute([$filter . $percent]);

        $reviews = $query->fetchAll(PDO::FETCH_OBJ);

        return $reviews;
    }

} 


    