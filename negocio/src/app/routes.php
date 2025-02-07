<?php
namespace App\controllers;

use Slim\Routing\RouteCollectorProxy;

// require __DIR__ . '/../controllers/Cliente.php';
$app->group('/api',function(RouteCollectorProxy $api){
    // Agrupar todas las rutas de usuario bajo /api/usuario
    $api->group('/usuario', function (RouteCollectorProxy $usuario) {

        // Rutas estáticas primero
        // Filtrar usuarios según criterios específicos
        $usuario->get('/filtro', UserManager::class . ':filterUsers');

        // Cambiar el rol de un usuario identificado por alias o correo
        $usuario->post('/cambiarRol/{aliasOrCorreo}', UserManager::class . ':cambiarRol');

        // Crear un nuevo usuario
        $usuario->post('', UserManager::class . ':createUser');

        // Obtener todos los usuarios o un usuario específico por ID
        $usuario->get('[/{id}]', UserManager::class . ':getUsers');

        $usuario->get('/getUser/{userParam}', UserManager::class . ':getUser');

        // Actualizar información de un usuario específico (actualización parcial)
        $usuario->patch('/{id}', UserManager::class . ':updateUser');

        // Eliminar un usuario específico
        $usuario->delete('/{id}', UserManager::class . ':deleteUser');

        // Cambiar la contraseña de un usuario
        $usuario->post('/{aliasOrCorreo}/cambiarPassw', UserManager::class . ':cambiarPassw');

        // Resetear la contraseña de un usuario (por ejemplo, por un administrador)
        $usuario->post('/{aliasOrCorreo}/resetearPassw', UserManager::class . ':resetearPassw');
    
        $usuario->post('/subirFoto', UserManager::class . ':subirFoto');
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
