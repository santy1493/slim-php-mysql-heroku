<?php
require_once './models/Empleado.php';
require_once './interfaces/IApiUsable.php';

class EmpleadoController extends Empleado implements IApiUsable
{
    public function CargarUno($request, $response, $args)
    {
      $parametros = $request->getParsedBody();

      $empleado = new Empleado();
      $empleado->nombre = $parametros['nombre'];
      $empleado->usuario = $parametros['usuario'];
      $empleado->clave = $parametros['clave'];

      if($args['sector'] == 'cerveceros') {
        $empleado->sector = 'cerveceros';
      }
      else if($args['sector'] == 'bartender') {
        $empleado->sector = 'bartender';
      }
      else if($args['sector'] == 'cocineros') {
        $empleado->sector = 'cocineros';
      }
      else if($args['sector'] == 'mozos') {
        $empleado->sector = 'mozos';
      }
      
      $empleado->crearEmpleado();

      $payload = json_encode(array("mensaje" => "Empleado cargado con exito"));

      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno($request, $response, $args)
    {
        $id = $args['id'];
        $empleado = Empleado::obtenerEmpleado($id);
        $payload = json_encode($empleado);

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {
        $lista = Empleado::obtenerTodos();
        $payload = json_encode(array("listaProductos" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
    
    public function ModificarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $nombre = $parametros['nombre'];
        Usuario::modificarUsuario($nombre);

        $payload = json_encode(array("mensaje" => "Usuario modificado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function BorrarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $usuarioId = $parametros['usuarioId'];
        Usuario::borrarUsuario($usuarioId);

        $payload = json_encode(array("mensaje" => "Usuario borrado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function Login($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $usuario = $parametros['usuario'];
        $clave = $parametros['clave'];

        $empleado = Empleado::obtenerEmpleadoPorUsuario($usuario);

        if ($empleado) { // Existe usuario en BD
            //if (password_verify($clave, $empleado->clave)) { // Validamos la clave ingresada
            if ($clave == $empleado->clave) { // Validamos la clave ingresada

                $token = AutJWT::CrearToken($empleado);

                $payload = json_encode(array("token" => $token));
                $response->getBody()->write($payload);
                return $response
                    ->withHeader('Content-Type', 'application/json');
            }

            $payload = json_encode(array("mensaje" => 'ERROR: Clave incorrecta'));
            $response->getBody()->write($payload);
            return $response
                ->withHeader('Content-Type', 'application/json');
        }

        $payload = json_encode(array("mensaje" => 'ERROR: Empleado no encontrado'));
        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }
}
