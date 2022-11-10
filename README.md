# API REST - recurso de reseñas de Series.

## Importar la base de datos
- importar desde PHPMyAdmin (o cualquiera) database/db_series.sql
# Pueba con postman, o similar.
El endpoint de la API es: http://localhost/tucarpetalocal/chocolate-rest/api/reviews

# Obtener todas las reseñas:
http://localhost/tucarpetalocal/TPE2-REST/api/reviews.
# Obtener una reseña:
http://localhost/tucarpetalocal/TPE2-REST/api/reviews/id de reseña

# Obtener las reseñas descendentemente por id.
http://localhost/tucarpetalocal/TPE2-REST/api/reviews?order=desc
# Crear una nueva reseña:
 verbo http POST + http://localhost/tucarpetalocal/TPE2-REST/api/reviews
# Eliminar una reseña:
 verbo http DELETE + http://localhost/tucarpetalocal/TPE2-REST/api/reviews/id

# Filtrar por reseña
https://localhost/tucarpetalocal/TPE2-REST/api/reviews?filter=:registro

# Paginacion

http://localhost/tucarpetalocal/TPE2-REST/api/reviews?sortby=campoelegido&order=asc o desc

//Ejemplos de codigos...


