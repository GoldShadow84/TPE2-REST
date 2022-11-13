# API REST - recurso de reseñas de Series.

## Importar la base de datos
- importar desde PHPMyAdmin (o cualquiera) database/db_series.sql

# Pueba con postman, o similar.
El endpoint de la API es: http://localhost/tucarpetalocal/chocolate-rest/api/reviews

# Obtener todas las reseñas:
Method: GET, URL: http://localhost/tucarpetalocal/TPE2-REST/api/reviews.

# Obtener una reseña:
Method: GET, URL: http://localhost/tucarpetalocal/TPE2-REST/api/reviews/:id de reseña

# Crear una nueva reseña:
 Method: POST, URL: http://localhost/tucarpetalocal/TPE2-REST/api/reviews

Agregar BODY
Ejemplo:
  {
    "author": "name",
    "comment": "just a great and awesome spin off, like breaking bad or even better.",
    "id_Serie": "11"
  }

 # Editar una nueva reseña:

 Method: PUT, URL: http://localhost/tucarpetalocal/TPE2-REST/api/reviews/:id

En :id se ingresa el id de la reseña que se desea modificar.

Agregar BODY
Ejemplo:
  {
    "author": "name",
    "comment": "just a great and awesome spin off, like breaking bad or even better.",
    "id_Serie": "11"
  }

# Eliminar una reseña:
 Method: DELETE, URL: http://localhost/tucarpetalocal/TPE2-REST/api/reviews/:id

# Filtrar por nombre, en reseñas
Method: GET, URL: https://localhost/tucarpetalocal/TPE2-REST/api/reviews?filter=:registro

# Obtener las reseñas descendentemente por id.
Method: GET, URL: http://localhost/tucarpetalocal/TPE2-REST/api/reviews?order=:asc/desc

# Obtener las reseñas descendentemente por campo.
Method: GET, URL: http://localhost/tucarpetalocal/TPE2-REST/api/reviews?sortby=:campo&order=:asc/desc

# Paginacion de reseñas
Method: GET, URL: http://localhost/tucarpetalocal/TPE2-REST/api/reviews?page=:numero&limit=:numero 

# Ordenar y paginar
Method: GET, URL: http://localhost/tucarpetalocal/TPE2-REST/api/reviews?sortby=:campo&order=:asc/desc&page=:numero&limit=:numero 

# Filtrar y Ordenar
Method: GET, URL: http://localhost/tucarpetalocal/TPE2-REST/api/reviews?filter=:registro&sortby=:campo&order=:asc/desc

# Filtrar y paginar
Method: GET, URL: http://localhost/tucarpetalocal/TPE2-REST/api/reviews?filter=:registro&page=:numero&limit=:numero 

# Filtrar, Ordenar y Paginar
Method: GET, URL: http://localhost/tucarpetalocal/TPE2-REST/api/reviews?filter=:registro&sortby=:campo&page=:numero&limit=:numero 


# Ejemplos de el uso de codigos estandar 200, 201, 400 y 404

# CODIGO 200

  El uso del codigo 200, se da cuando una solicitud realizada por el usuario ha tenido exito. por ejemplo traer toda la coleccion de reseñas, paginada, filtrada, etc. o eliminando una reseña. utilizados para los metodos http GET y DELETE.

# CODIGO 201

  El uso del codigo 201, se da cuando se ha creado o modificado una reseña con exito, 
  utilizados para los metodos http POST y PUT.

# CODIGO 400

  El uso del codigo 400, se da cuando la solicitud hecha por el usuario es correcta, pero no se ha encontrado el contenido de reseñas solicitado.
  por ejemplo se utiliza cuando el dato por el que se desea filtrar en las reseñas no se encuentra, o se esta intentando ir hacia una pagina del paginado que no existe.

# CODIGO 401

El uso del codigo 401, se da cuando los datos ingresados para logearse no coinciden con los registrados en la base de datos, o mas concretamente en la tabla de Usuarios, o se intenta ingresar, modificar o eliminar una reseña si haberse logueado.

# CODIGO 404

  El uso del codigo 404, se da cuando la solicitud por el usuario es invalida, la sintaxis, la uri o los parametros son incorrectos. 
  por ejemplo cuando se intenta crear una reseña con los datos incompletos, se intenta editar una reseña con un id que no existe, se solicita ordenar por un campo de la tabla que no existe o se ingresa una letra en lugar de un numero al momento de usar el paginado.


