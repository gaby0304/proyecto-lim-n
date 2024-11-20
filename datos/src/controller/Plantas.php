<?php

namespace App\Controller;


use PDO;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class Plantas{
    
    private $container;
    public function __construct(ContainerInterface $c) {
        $this->container = $c;
    }

 

   //Solo se deben cambiar el nombre de los procesos almacenados para otros apartados.



    //metodo para probar que no hayan errores en los endpoints.
    public function hi(Request $request, Response $response, $args) {
        $response->getBody()->write("Ola");
        return $response;
    }



   //  READ 
    function read(Request $request, Response $response, $args) {
        // si hay id hace que el SP haga busqueda especifica sino trae todos.
        if (isset($args['id'])) {
            $sql = "CALL obtener_plantas(" . $args['id'] . ")";
        } else {
            $sql = "CALL obtener_plantas(NULL)";
        }
       
        $con = $this->container->get('bd');
        
        try {
            
            $query = $con->prepare($sql);
            $query->execute();
            
            // Obtener los resultados
            $result = $query->fetchAll(PDO::FETCH_ASSOC);  
            
            // 200 funco, 204 no funco :(
            $status = $query->rowCount() > 0 ? 200 : 204;
            
            // se le da forma JSON
            $response->getBody()->write(json_encode(['data' => $result]));
            
        } catch (\PDOException $e) {
             // Obtiene el error del SP o la base de datos
            $response->getBody()->write(json_encode(['error' => 'Database error: ' . $e->getMessage()]));
            $status = 500;
        }
        
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus($status);
    }



    // DELETE
    
    function delete(Request $request, Response $response, $args) {
        // si hay id hace que el SP haga busqueda especifica sino trae todos.
        if (isset($args['id'])) {
            $sql = "CALL eliminar_planta(" . $args['id'] . ")";

            $con = $this->container->get('bd');

            try {
            
                $query = $con->prepare($sql);
                $query->execute();
                
                // Obtener los resultados
                $result = $query->fetchAll(PDO::FETCH_ASSOC);  
                
                // 200 funco, 204 no funco :(
                $status = $query->rowCount() > 0 ? 200 : 204;
                
                // se le da forma JSON
                $response->getBody()->write('Se elimino el id seleccionado');
                
            } catch (\PDOException $e) {
                 // Obtiene el error del SP o la base de datos
                $response->getBody()->write(json_encode(['error' => 'Database error: ' . $e->getMessage()]));
                $status = 500;
            }
            
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus($status);

        } else {
            $response->getBody()->write(json_encode(['error' => 'No hay id en los argumentos']));
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(400);  
        }
    }


 // Filtro
 function filtro(Request $request, Response $response, $args) {
    
    if (isset($args['nombre_plantas'])) {

        $sql = "CALL filtrar_plantas(:pnombre_plantas)";

   
        $con = $this->container->get('bd');

        try {
            // Preparar y ejecutar la consulta
            $query = $con->prepare($sql);
            // Asignar el valor del parámetro 'pnombre_comidas'
            $query->bindParam(':pnombre_plantas', $args['nombre_plantas'], PDO::PARAM_STR);
            $query->execute();

            // Obtener los resultados
            $result = $query->fetchAll(PDO::FETCH_ASSOC);  // Usar FETCH_ASSOC para evitar objetos

            // 200 funco, 204 no funco
            $status = $query->rowCount() > 0 ? 200 : 204;

            // Se le da forma JSON a la respuesta
            $response->getBody()->write(json_encode(['data' => $result]));

        } catch (\PDOException $e) {
            // error de la base de datos
            $response->getBody()->write(json_encode(['error' => 'Database error: ' . $e->getMessage()]));
            $status = 500;
        }

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus($status);

    } else {
        // sin nombrecomidas da estp 
        $response->getBody()->write(json_encode(['error' => 'No hay nombre de plantas en los argumentos']));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(400);
    }
}



// UPDATE 
public function update(Request $request, Response $response, $args)
{
    // Obtener el ID de la URL
    $id_planta = $args['id'];

    // Obtener el cuerpo de la solicitud
    $body = $request->getBody();
    $data = json_decode($body, true);

    // Validar que el ID de planta sea válido
    if (!$id_planta) {
        $response->getBody()->write(json_encode(['error' => 'ID de planta no válido']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    // Obtener los valores de los parámetros
    $nombre_planta = isset($data['nombre_planta']) ? $data['nombre_planta'] : null;
    $descripcion_planta = isset($data['descripcion_planta']) ? $data['descripcion_planta'] : null;
    $imagen = isset($data['imagen']) ? $data['imagen'] : null;
    $elaboracion = isset($data['elaboracion']) ? $data['elaboracion'] : null; // Nuevo parámetro

    try {
        // Conectar a la base de datos
        $con = $this->container->get('bd');

        // Preparar el SQL para llamar al proceso almacenado
        $sql = "CALL actualizar_planta(
            :id_planta,
            :nombre_planta,
            :descripcion_planta,
            :imagen,
            :elaboracion
        )";

        // Preparar la consulta
        $query = $con->prepare($sql);

        // Vincular los parámetros con los valores
        $query->bindParam(':id_planta', $id_planta);
        $query->bindParam(':nombre_planta', $nombre_planta);
        $query->bindParam(':descripcion_planta', $descripcion_planta);
        $query->bindParam(':imagen', $imagen);
        $query->bindParam(':elaboracion', $elaboracion); // Vinculación de la columna 'elaboracion'

        // Ejecutar la consulta
        $query->execute();

        // Verificar si hubo un cambio y devolver una respuesta adecuada
        $status = $query->rowCount() > 0 ? 200 : 204;

        // Responder con el código de estado y un mensaje de éxito
        $response->getBody()->write(json_encode(['status' => $status, 'message' => 'Planta actualizada correctamente.']));

    } catch (\PDOException $e) {
        // Manejar errores de base de datos
        $response->getBody()->write(json_encode(['error' => 'Error en la base de datos: ' . $e->getMessage()]));
        $status = 500;
    }

    // Retornar la respuesta con el encabezado adecuado
    return $response
        ->withHeader('Content-Type', 'application/json')
        ->withStatus($status);
}





// CREATE
public function create(Request $request, Response $response, $args)
{
    $body = $request->getBody();
    $data = json_decode($body, true);

    // Asignar los valores de los parámetros
    $nombre_planta = isset($data['nombre_planta']) ? $data['nombre_planta'] : null;
    $descripcion_planta = isset($data['descripcion_planta']) ? $data['descripcion_planta'] : null;
    $imagen = isset($data['imagen']) ? $data['imagen'] : null;
    $usuario_id = isset($data['usuario_id']) ? $data['usuario_id'] : null;
    $elaboracion = isset($data['elaboracion']) ? $data['elaboracion'] : null;

    $fecha_creacion = date('Y-m-d H:i:s');
    $fecha_actualizacion = $fecha_creacion;

    // Verificar que no haya campos nulos
    if ($nombre_planta == null || $descripcion_planta == null || $imagen == null || $usuario_id == null || $elaboracion == null) {
        $response->getBody()->write(json_encode(['error' => 'Todos los campos son obligatorios']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    try {
        // Conectar a la base de datos
        $con = $this->container->get('bd');

        // Preparar la consulta para ejecutar el procedimiento almacenado
        $query = $con->prepare('CALL crear_planta(:nombre_planta, :descripcion_planta, :imagen, :usuario_id, :fecha_creacion, :fecha_actualizacion, :elaboracion)');

        $query->bindParam(':nombre_planta', $nombre_planta);
        $query->bindParam(':descripcion_planta', $descripcion_planta);
        $query->bindParam(':imagen', $imagen);
        $query->bindParam(':usuario_id', $usuario_id);
        $query->bindParam(':fecha_creacion', $fecha_creacion);
        $query->bindParam(':fecha_actualizacion', $fecha_actualizacion);
        $query->bindParam(':elaboracion', $elaboracion);

        $query->execute();

        // Verificar si hubo un cambio y devolver una respuesta adecuada
        $status = $query->rowCount() > 0 ? 200 : 204;

        $response->getBody()->write(json_encode(['status' => $status, 'message' => 'Planta creada correctamente.']));
    } catch (\PDOException $e) {
        // Algun error del SP o de la base como tal
        $response->getBody()->write(json_encode(['error' => 'Error en la base de datos: ' . $e->getMessage()]));
        $status = 500;
    }

    return $response->withHeader('Content-Type', 'application/json')->withStatus($status);
}






























































}
?>
