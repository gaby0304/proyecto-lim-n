<?php

namespace App\controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class Administrador extends Persona {
    const RECURSO = 'administrador';
    const ROL = 1;

    function create(Request $request, Response $response, $args) {
        $body = json_decode($request->getBody(), 1);
        $status = $this->createP(self::RECURSO, self::ROL, $body);
        return $response->withStatus($status);
    }

    function read(Request $request, Response $response, $args) {
        if (isset($args['id'])) {
            $resp = $this->readP(self::RECURSO, $args['id']);
        }else{
            $resp = $this->readP(self::RECURSO);
        }
        $response->getBody()->write(json_encode($resp['resp']));
        return $response
            ->withHeader('Content-type', 'Application/json')
            ->withStatus($resp['status']);
    }

    function update(Request $request, Response $response, $args) {
        $body = json_decode($request->getBody());
      
        $status = $this->updateP(self::RECURSO,$body, $args['id']);
        return $response->withStatus($status);

    }

    function delete(Request $request, Response $response, $args) {
        return $response
                ->withStatus($this
                ->deleteP(self::RECURSO,$args['id']));
    }

    function filtrar(Request $request, Response $response, $args) {
        $datos = $request->getQueryParams();
        $res = $this->filtrarP($datos,self::RECURSO);
        $response->getBody()->write(json_encode($res['resp']));
        return $response
            ->withHeader('Content-type', 'Application/json')
            ->withStatus($res['status']);

    }



    
    function hi() {
        return "Ola";
    }

}
