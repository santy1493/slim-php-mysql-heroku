<?php
require_once './models/Pedido.php';
require_once './models/Producto.php';
require_once './models/Item.php';
require_once './interfaces/IApiUsable.php';

class ProductoController extends Producto implements IApiUsable
{
    public function CargarUnoCocineros($request, $response, $args)
    {

        $parametros = $request->getParsedBody();

        $pedido = new Producto();
        $pedido->descripcion = $parametros['descripcion'];
        $pedido->precio = $parametros['precio'];
        $pedido->sector = 'cocineros';
        
        $id_pedido = $pedido->crearProducto();

        $payload = json_encode(array("mensaje" => "Producto creado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function CargarUnoBartender($request, $response, $args)
    {

        $parametros = $request->getParsedBody();

        $pedido = new Producto();
        $pedido->descripcion = $parametros['descripcion'];
        $pedido->precio = $parametros['precio'];
        $pedido->sector = 'bartender';
        
        $id_pedido = $pedido->crearProducto();

        $payload = json_encode(array("mensaje" => "Producto creado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function CargarUnoCerveceros($request, $response, $args)
    {

        $parametros = $request->getParsedBody();

        $pedido = new Producto();
        $pedido->descripcion = $parametros['descripcion'];
        $pedido->precio = $parametros['precio'];
        $pedido->sector = 'cerveceros';
        
        $id_pedido = $pedido->crearProducto();

        $payload = json_encode(array("mensaje" => "Producto creado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
    public function CargarUno($request, $response, $args)
    {
      $parametros = $request->getParsedBody();

      $pedido = new Producto();
      $pedido->descripcion = $parametros['descripcion'];
      $pedido->precio = $parametros['precio'];

      if($args['sector'] == 'cerveceros') {
        $pedido->sector = 'cerveceros';
      }
      else if($args['sector'] == 'bartender') {
        $pedido->sector = 'bartender';
      }
      else if($args['sector'] == 'cocineros') {
        $pedido->sector = 'cocineros';
      }
      
      $id_pedido = $pedido->crearProducto();

      $payload = json_encode(array("mensaje" => "Producto creado con exito"));

      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno($request, $response, $args)
    {
        $id = $args['id'];
        $producto = Producto::obtenerProducto($id);
        $payload = json_encode($producto);

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {
        $lista = Producto::obtenerTodos();
        $payload = json_encode(array("listaProductos" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerPendientes($request, $response, $args)
    {
        $lista = Item::obtenerItemsPendientes();
        $payload = json_encode(array("listaItemsPendientes" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function CambiarItemAEnPreparacion($request, $response, $args)
    {
        $id = $args['id'];
 
        Item::cambiarAEnPreparacion(intval($id));

        $payload = json_encode(array("mensaje" => "Se modifico el estado del item a 'en preparacion'"));

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
}
