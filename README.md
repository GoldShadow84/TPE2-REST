# API REST - recurso de reseñas de Series.

## Importar la base de datos
- importar desde PHPMyAdmin (o cualquiera) database/db_series.sql

# Pueba con postman, o similar.
El endpoint de la API es: http://localhost/tucarpetalocal/chocolate-rest/api/reviews

# Obtener todas las reseñas:
http://localhost/tucarpetalocal/TPE2-REST/api/reviews.

# Obtener una reseña:
http://localhost/tucarpetalocal/TPE2-REST/api/reviews/id de reseña

# Crear una nueva reseña:
 verbo http POST + http://localhost/tucarpetalocal/TPE2-REST/api/reviews

Agregar BODY
Ejemplo:
  {
    "author": "name",
    "comment": "just a great and awesome spin off, like breaking bad or even better.",
    "id_Serie": "11"
  }


 # Editar una nueva reseña:

 verbo http PUT + http://localhost/tucarpetalocal/TPE2-REST/api/reviews/id


# Eliminar una reseña:
 verbo http DELETE + http://localhost/tucarpetalocal/TPE2-REST/api/reviews/id


Verbo http GET:

# Filtrar por nombre, en reseñas
https://localhost/tucarpetalocal/TPE2-REST/api/reviews?filter=:registro

# Obtener las reseñas descendentemente por id.
http://localhost/tucarpetalocal/TPE2-REST/api/reviews?order=asc/desc

# Obtener las reseñas descendentemente por campo.
http://localhost/tucarpetalocal/TPE2-REST/api/reviews?sortby=campo&order=asc/desc

# Paginacion de reseñas
 
http://localhost/tucarpetalocal/TPE2-REST/api/reviews?page=:numero&limit=numero 

# Ordenar y paginar

http://localhost/tucarpetalocal/TPE2-REST/api/reviews?sortby=campo&order=asc/desc&page=:numero&limit=numero 

# Filtrar y Ordenar

http://localhost/tucarpetalocal/TPE2-REST/api/reviews?filter=:registro&sortby=campo&order=asc/desc

# Filtrar y paginar

http://localhost/tucarpetalocal/TPE2-REST/api/reviews?filter=:registro&page=:numero&limit=numero 

# Filtrar, Ordenar y Paginar

http://localhost/tucarpetalocal/TPE2-REST/api/reviews?filter=:registro&sortby=campo&spage=numero&limit=numero 

//Ejemplos de codigos 200, etc...


