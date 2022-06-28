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