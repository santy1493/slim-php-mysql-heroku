<?php
require_once './controllers/EmpleadoController.php';
require_once './controllers/LogController.php';
require_once './models/Empleado.php';
require_once './middlewares/AutJWT.php';

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class Validaciones
{
    public static function verificarToken($request, $handler)
    {
        $datos = $request->getHeaderLine('token');
        $response = new Response();
       // try {
            AutJWT::VerificarToken($datos);
            $response = $handler->handle($request);
            return $response
                ->withHeader('Content-Type', 'application/json');

        /*} catch (Exception $e) {
            $payload = json_encode(array("mensaje" => "ERROR: Hubo un error con el TOKEN"));
            $response->getBody()->write($payload);
            return $response
                ->withHeader('Content-Type', 'application/json');
        }
        return $response;*/
    }

    public static function verificarParametrosLogin($request, $handler)
    {
        $parametros = $request->getParsedBody();
        $response = new Response();
        if (isset($parametros['usuario']) && isset($parametros['clave'])) {
            $response = $handler->handle($request);
            return $response
                ->withHeader('Content-Type', 'application/json');
        }
        $payload = json_encode(array("mensaje" => "ERROR: Request incorrecta"));
        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }



    public static function verificarAdmin($request, $handler)
    {
        $token = $request->getHeaderLine('token');
        $empl = AutJWT::ObtenerData($token);
        $response = new Response();

        if ($empl->sector == "admin") { // Socios
            return $handler->handle($request);
        }

        $payload = json_encode(array("mensaje" => "ERROR: Debes ser Administrador para realizar esta accion"));
        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public static function verificarMozo($request, $handler)
    {
        $token = $request->getHeaderLine('token');
        $empl = AutJWT::ObtenerData($token);
        $response = new Response();

        if ($empl->sector == "admin" || $empl->sector == "mozos") {
            return $handler->handle($request);
        }

        $payload = json_encode(array("mensaje" => "ERROR: Debes ser Mozo o Administrador para realizar esta accion"));
        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }


    public static function verificarEmpleado($request, $response, $next)
    {
        $token = $request->getHeaderLine('token');
        $empl = AutJWT::ObtenerData($token);

        if ($empl->sector == "bartender" || $empl->sector == "cocineros" || $empl->sector == "cerveceros") { // Cocinero, bartender o cervecero
            return $next($request, $response);
        }
        return $response->withJson(array("mensaje" => "ERROR: Debes ser empleado para realizar esta accion"));
    }
}