<?php

namespace App\controllers;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class Restaurantes extends ServicioCURL{ 

    private const ENDPOINT = "/restaurantes";





    // READ CON CURLP
    public function read(Request $request, Response $response, $args) {
        $url = $this::ENDPOINT . '/read';
        if (isset($args['id'])) {
            $url .= "/{$args['id']}";
        }

        $respA = $this->ejecutarCURL($url, 'GET');

        $response->getBody()->write($respA['resp']);
        return $response->withHeader('Content-type', 'Application/json')
            ->withStatus($respA['status']);
    }

   
    // DELETE CON CURLP
    public function delete(Request $request, Response $response, $args){    
        $url = $this::ENDPOINT . "/{$args['id']}";
        $respA = $this->ejecutarCURL($url, 'DELETE');
        return $response->withStatus($respA['status']);
    }


    // Create CON CURLP
  
    public function create(Request $request, Response $response, $args)
    {
        $datos = $request->getBody();
        $respA = $this->ejecutarCURL($this::ENDPOINT, 'POST', $datos);
        // print_r($respA);
        // die();
        return $response->withStatus($respA['status']);
    }


    // Update CON CURLP

public function update(Request $request, Response $response, $args){
    $datos = $request->getBody();
    $url = $this::ENDPOINT . "/{$args['id']}";
    $respA = $this->ejecutarCURL($url, 'PUT', $datos);
    return $response->withStatus($respA['status']);
}
  
//  FILTRO POR NOMBRE CON CURLP
public function filtro(Request $request, Response $response, $args)
{
    if (isset($args['nombre_restaurantes'])) {
        $url = $this::ENDPOINT . '/filtro/' . urlencode($args['nombre_restaurantes']);
        $respA = $this->ejecutarCURL($url, 'GET');

        $response->getBody()->write($respA['resp']);
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus($respA['status']);
    } else {
        $response->getBody()->write(json_encode(['error' => 'No hay nombre de restaurante en los argumentos']));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(400);
    }
}









}