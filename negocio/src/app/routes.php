<?php
namespace App\controllers;

use Slim\Routing\RouteCollectorProxy;

// require __DIR__ . '/../controllers/Cliente.php';
$app->group('/api',function(RouteCollectorProxy $api){
    $api->group('/cliente',function(RouteCollectorProxy $cliente){
        $cliente->post('', Cliente::class . ':create'); //Crea un cliente
        $cliente->get('/read[/{id}]', Cliente::class . ':read'); //Consulta todos los clientes
        $cliente->get('/filtro', Cliente::class . ':filtrar');
        $cliente->put('/{id}', Cliente::class . ':update'); //Actualiza todos los atributos, patch actualiza solo uno o algunos
        $cliente->delete('/{id}', Cliente::class . ':delete'); //Elimina un registro x su id
        // $cliente->get('/{id}', Cliente::class . ':buscar'); //Consulta un cliente x su id
    });
    $api->group('/administrador', function (RouteCollectorProxy $administrador) {
        $administrador->post('', Administrador::class . ':create');
        $administrador->get('/read[/{id}]', Administrador::class . ':read');
        $administrador->get('/filtro', Administrador::class . ':filtrar');
        $administrador->put('/{id}', Administrador::class . ':update');
        $administrador->delete('/{id}', Administrador::class . ':delete');
    });

    $api->group('/supervisor', function (RouteCollectorProxy $supervisor) {
        $supervisor->post('', supervisor::class . ':create');
        $supervisor->get('/read[/{id}]', supervisor::class . ':read');
        $supervisor->get('/filtro', supervisor::class . ':filtrar');
        $supervisor->put('/{id}', supervisor::class . ':update');
        $supervisor->delete('/{id}', supervisor::class . ':delete');
    });
   
    // Autenticacion
    $api->group('/auth',function(RouteCollectorProxy $auth){
        $auth->post('/iniciar', Auth::class . ':iniciar'); 
        $auth->patch('/cerrar/{idUsuario}', Auth::class . ':cerrar');
        $auth->patch('/refrescar', Auth::class . ':refrescar');
    });
    // Usuario
    $api->group('/usr',function(RouteCollectorProxy $usr){
        $usr->patch('/reset/passw/{idUsuario}', Usuario::class . ':resetPassw'); 
        $usr->patch('/change/passw/{idUsuario}', Usuario::class . ':changePassw');
        $usr->patch('/change/rol/{idUsuario}', Usuario::class . ':changeRol');
    });
    $api->group('/categories', function (RouteCollectorProxy $categories) {
        $categories->post('', categories::class . ':create');
        $categories->get('/read[/{id}]', categories::class . ':read');
        $categories->get('/filtro', categories::class . ':filtrar');
        $categories->put('/{id}', categories::class . ':update');
        $categories->delete('/{id}', categories::class . ':delete');
    });
  



    $api->group('/comidas', function (RouteCollectorProxy $class) {
        $class->get('/read[/{id}]', Comidas::class . ':read');
        $class->delete('/{id}', Comidas::class . ':delete');
        $class->get('/filtro/{nombre_comidas}', Comidas::class . ':filtro');
        $class->put('/{id}',Comidas::class . ':update');
        $class->post('', Comidas::class . ':create');
    });

    

    $api->group('/plantas', function (RouteCollectorProxy $class) {
        $class->get('/read[/{id}]', Plantas::class . ':read');
        $class->delete('/{id}', Plantas::class . ':delete');
        $class->get('/filtro/{nombre_plantas}', Plantas::class . ':filtro');
        $class->put('/{id}',Plantas::class . ':update');
        $class->post('', Plantas::class . ':create');
    });
    

    $api->group('/restaurantes', function (RouteCollectorProxy $class) {
        $class->get('/read[/{id}]', Restaurantes::class . ':read');
        $class->delete('/{id}', Restaurantes::class . ':delete');
        $class->get('/filtro/{nombre_restaurantes}', Restaurantes::class . ':filtro');
        $class->put('/{id}',Restaurantes::class . ':update');
        $class->post('', Restaurantes::class . ':create');
    });










    

    
   
    
   

});
