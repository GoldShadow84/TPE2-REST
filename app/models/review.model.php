<?php

class ReviewModel {

    private $db;

    //abrimos conexion con la base de datos db_series
    public function __construct() {
        $this->db = new PDO('mysql:host=localhost;'.'dbname=db_series;charset=utf8', 'root', '');
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

} 


    