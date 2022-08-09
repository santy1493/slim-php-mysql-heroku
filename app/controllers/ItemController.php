<?php
require_once './models/Pedido.php';
require_once './models/Item.php';
require_once './models/ItemDTO.php';
require_once './interfaces/IApiUsable.php';

class ItemController extends Item implements IApiUsable
{
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $items = $parametros['items'];

        // Creamos el usuario
        $pedido = new Pedido();
        $id_pedido = $pedido->crearPedido();

        foreach($items as $item) {

            $newItem = new Item();
            $newItem->descripcion = $item['descripcion'];
            $newItem->cod_sector = $item['cod_sector'];
            $newItem->id_pedido = $id_pedido;

            $newItem->crearItem();
        }

        $payload = json_encode(array("mensaje" => "Usuario creado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno($request, $response, $args)
    {
        $id = $args['id'];
        $pedido = Pedido::obtenerPedido($id);
        $pedido->items = Item::obtenerItemsPorIdPedido($pedido->id);
        $payload = json_encode($pedido);

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {
        $lista = Pedido::obtenerTodos();
        $payload = json_encode(array("listaUsuario" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerPendientesDTO($request, $response, $args)
    {
        $lista = Item::obtenerItemsPendientes();
        $payload = json_encode(array("listaItemsPendientes" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerPendientes($request, $response, $args)
    {
        $token = $request->getHeaderLine('token');
        $empl = AutJWT::ObtenerData($token);

        $lista = ItemDTO::obtenerItemsPendientes($empl->sector);
        $payload = json_encode(array("listaItemsPendientes" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerEnPreparacion($request, $response, $args)
    {
        $token = $request->getHeaderLine('token');
        $empl = AutJWT::ObtenerData($token);

        $lista = ItemDTO::obtenerItemsEnPreparacion($empl->sector);
        $payload = json_encode(array("listaItemsPendientes" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerItemsAdmin($request, $response, $args)
    {
        $token = $request->getHeaderLine('token');
        $empl = AutJWT::ObtenerData($token);

        if($empl->sector == 'admin') {
          $lista = ItemDTO::obtenerItemsAdmin($empl->sector);
          $payload = json_encode(array("listaItems" => $lista));

          $response->getBody()->write($payload);
          return $response
            ->withHeader('Content-Type', 'application/json');
        }

        $payload = json_encode(array("Error" => "Debe ser admin para ver todos los items"));

          $response->getBody()->write($payload);
          return $response
            ->withHeader('Content-Type', 'application/json');
        
    }

    public function CambiarItemAEnPreparacion($request, $response, $args)
    {
        $token = $request->getHeaderLine('token');
        $id = $args['id'];

        $item = Item::obtenerItem($id);
        $empl = AutJWT::ObtenerData($token);
        $producto = Producto::obtenerProducto($item->id_producto);

        if($item->id>0 && $empl->sector == $producto->sector) {

          $items = ItemDTO::obtenerItemsDTOPorIdPedido($item->id_pedido);
          $pedido_enpreparacion = 0;

          foreach($items as $item) {
            if($item->estado == 'en preparacion') {
              $pedido_enpreparacion = 1;
            }
          }

          if($pedido_enpreparacion == 0) {
            Pedido::CambiarEnPreparacion($item->id_pedido);
          }

          $parametros = $request->getParsedBody();

          $tiempo_estimado = $parametros['tiempo_estimado'];
  
          Item::cambiarAEnPreparacion(intval($id), intval($tiempo_estimado));

          $payload = json_encode(array("mensaje" => "Se modifico el estado del item a 'en preparacion'"));

          $response->getBody()->write($payload);
          return $response
            ->withHeader('Content-Type', 'application/json');
        }

        $payload = json_encode(array("mensaje" => "Item no encontrado o no pertenece al sector del empleado"));

          $response->getBody()->write($payload);
          return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function CambiarItemAListoParaServir($request, $response, $args)
    {
        $token = $request->getHeaderLine('token');
        $id = $args['id'];

        $item = Item::obtenerItem($id);
        $empl = AutJWT::ObtenerData($token);
        $producto = Producto::obtenerProducto($item->id_producto);

        if($item->id>0 && $empl->sector == $producto->sector) {
          Item::cambiarAListoParaServir(intval($id));

          $items = ItemDTO::obtenerItemsDTOPorIdPedido($item->id_pedido);
          $pedido_listo = 1;

          foreach($items as $item) {
            if($item->estado != 'listo para servir') {
              $pedido_listo = 0;
            }
          }

          if($pedido_listo) {
            Pedido::CambiarListoParaServir($item->id_pedido);
          }

          $payload = json_encode(array("mensaje" => "Se modifico el estado del item a 'listo para servir'"));

          $response->getBody()->write($payload);
          return $response
            ->withHeader('Content-Type', 'application/json');
        }

        $payload = json_encode(array("mensaje" => "Item no encontrado o no pertenece al sector del empleado"));

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

    public function TraerUnoDTO($request, $response, $args)
    {
        $id = $args['id'];
        $itemDTO = ItemDTO::obtenerItemDTO($id);
        $payload = json_encode($itemDTO);

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
}
