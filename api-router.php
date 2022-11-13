<?php

require_once './libs/Router.php';
require_once './app/controllers/review-api.controller.php';
require_once './app/controllers/auth-api.controller.php';

//creacion del router

$router = new Router();

//tabla de ruteo.

$router->addRoute('reviews', 'GET','ReviewApiController','getReviews');
$router->addRoute('reviews/:ID', 'GET','ReviewApiController','getReview');
$router->addRoute('reviews/:ID', 'DELETE', 'ReviewApiController', 'deleteReview');
$router->addRoute('reviews', 'POST', 'ReviewApiController', 'insertReview'); 
$router->addRoute('reviews/:ID', 'PUT', 'ReviewApiController', 'updateReview'); 

$router->addRoute("auth/token", 'GET', 'AuthApiController', 'getToken');

//ejecuta la ruta, sea la que sea

$router->route($_GET["resource"], $_SERVER['REQUEST_METHOD']);


