<?php

namespace App\Controller;


use PDO;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class Restaurantes{
    
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
            $sql = "CALL obtener_restaurantes(" . $args['id'] . ")";
        } else {
            $sql = "CALL obtener_restaurantes(NULL)";
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
            $sql = "CALL eliminar_Restaurante(" . $args['id'] . ")";

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
    
    if (isset($args['nombre_restaurantes'])) {

        $sql = "CALL filtrar_restaurantes(:pnombre_restaurantes)";

   
        $con = $this->container->get('bd');

        try {
            // Preparar y ejecutar la consulta
            $query = $con->prepare($sql);
            // Asignar el valor del parámetro 'pnombre_comidas'
            $query->bindParam(':pnombre_restaurantes', $args['nombre_restaurantes'], PDO::PARAM_STR);
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
        $response->getBody()->write(json_encode(['error' => 'No hay nombre de comida en los argumentos']));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(400);
    }
}



// UPDATE 

public function update(Request $request, Response $response, $args)
{
    // Obtener el ID de la URL
    $id_restaurante = $args['id'];

    // Obtener el cuerpo de la solicitud
    $body = $request->getBody();
    $data = json_decode($body, true);

    // Validar que el ID de restaurante sea válido
    if (!$id_restaurante) {
        $response->getBody()->write(json_encode(['error' => 'ID de restaurante no válido']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    // Obtener los valores de los parámetros
    $nombre_restaurante = isset($data['nombre_restaurante']) ? $data['nombre_restaurante'] : null;
    $descripcion_restaurante = isset($data['descripcion_restaurante']) ? $data['descripcion_restaurante'] : null;
    $direccion = isset($data['direccion']) ? $data['direccion'] : null;
    $imagen = isset($data['imagen']) ? $data['imagen'] : null;

    // Conectar a la base de datos
    $con = $this->container->get('bd');

    try {
        // Preparar el SQL para llamar al proceso almacenado
        $sql = "CALL actualizar_restaurante(
            :id_restaurante,
            :nombre_restaurante,
            :descripcion_restaurante,
            :direccion,
            :imagen
        )";

        // Preparar la consulta
        $query = $con->prepare($sql);

        // Vincular los parámetros con los valores correctos
        $query->bindParam(':id_restaurante', $id_restaurante);
        $query->bindParam(':nombre_restaurante', $nombre_restaurante);
        $query->bindParam(':descripcion_restaurante', $descripcion_restaurante);
        $query->bindParam(':direccion', $direccion);
        $query->bindParam(':imagen', $imagen);

        // Ejecutar la consulta
        $query->execute();

        // Verificar si hubo un cambio y devolver una respuesta adecuada
        $status = $query->rowCount() > 0 ? 200 : 204;

        // Responder con el código de estado y un mensaje de éxito
        $response->getBody()->write(json_encode(['status' => $status, 'message' => 'Restaurante actualizado correctamente.']));

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

  
    $nombre_restaurante = isset($data['nombre_restaurante']) ? $data['nombre_restaurante'] : null;
    $descripcion_restaurante = isset($data['descripcion_restaurante']) ? $data['descripcion_restaurante'] : null;
    $direccion = isset($data['direccion']) ? $data['direccion'] : null;
    $imagen = isset($data['imagen']) ? $data['imagen'] : null;
    $usuario_id = isset($data['usuario_id']) ? $data['usuario_id'] : null;

    $fecha_creacion = date('Y-m-d H:i:s');
    $fecha_actualizacion = $fecha_creacion;

    // Verificar que no haya campos nulos
    if ($nombre_restaurante == null || $descripcion_restaurante == null || $direccion == null || $imagen == null || $usuario_id == null) {
        $response->getBody()->write(json_encode(['error' => 'Todos los campos son obligatorios']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    try {
        // Conectar a la base de datos
        $con = $this->container->get('bd');

        // Preparar la consulta para ejecutar el procedimiento almacenado
        $query = $con->prepare('CALL crear_restaurante(:nombre_restaurante, :descripcion_restaurante, :direccion, :imagen, :usuario_id, :fecha_creacion, :fecha_actualizacion)');

        $query->bindParam(':nombre_restaurante', $nombre_restaurante);
        $query->bindParam(':descripcion_restaurante', $descripcion_restaurante);
        $query->bindParam(':direccion', $direccion);
        $query->bindParam(':imagen', $imagen);
        $query->bindParam(':usuario_id', $usuario_id);
        $query->bindParam(':fecha_creacion', $fecha_creacion);
        $query->bindParam(':fecha_actualizacion', $fecha_actualizacion);

        // Ejecutar la consulta
        $query->execute();

        var_dump($nombre_restaurante,$descripcion_restaurante,$direccion,$imagen,$usuario_id );
        $status = $query->rowCount() > 0 ? 200 : 204;

        $response->getBody()->write(json_encode(['status' => $status, 'message' => 'Restaurante creado correctamente.']));

    } catch (\PDOException $e) {
        // Algun error del SP o de la base como tal
        $response->getBody()->write(json_encode(['error' => 'Error en la base de datos: ' . $e->getMessage()]));
        $status = 500;
    }

    return $response->withHeader('Content-Type', 'application/json')->withStatus($status);
}































































}
?>
