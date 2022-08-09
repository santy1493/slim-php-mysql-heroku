<?php
require_once './models/Pedido.php';
require_once './models/Item.php';
require_once './models/ItemDTO.php';
require_once './models/Mesa.php';
require_once './interfaces/IApiUsable.php';

class PedidoController extends Pedido implements IApiUsable
{
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $id_mesa = $parametros['id_mesa'];
        $mesa = Mesa::obtenerMesa(intval($id_mesa));

        if($mesa->estado != 'cerrada') {

          $payload = json_encode(array("mensaje" => "La mesa ya esta abierta"));

          $response->getBody()->write($payload);
          return $response
            ->withHeader('Content-Type', 'application/json');
        }

        $items = $parametros['items'];
        
        // Creamos el usuario
        $pedido = new Pedido();
        $id_pedido = $pedido->crearPedido();
        $pedido->id = $id_pedido;

        Mesa::abrirMesa($mesa->id, $id_pedido);

        foreach($items as $item) {

            $newItem = new Item();
            $newItem->id_producto = $item['id_producto'];
            $newItem->cantidad = $item['cantidad'];
            $newItem->id_pedido = $id_pedido;

            $newItem->crearItem();
        }

        $pedido->calcularPrecioPedido();

        $payload = json_encode(array("mensaje" => "Pedido creado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno($request, $response, $args)
    {
        $id = $args['id'];
        $pedido = Pedido::obtenerPedido($id);
        $pedido->items = ItemDTO::obtenerItemsDTOPorIdPedido($pedido->id);
        $payload = json_encode($pedido);

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {
        $lista = Pedido::obtenerTodos();
        foreach($lista as $pedido) {
          $pedido->items = ItemDTO::obtenerItemsDTOPorIdPedido($pedido->id);
        }
        $payload = json_encode(array("listaUsuario" => $lista));

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

    public function calcularPrecioTotal($id)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta(
            "UPDATE items SET 
            estado = 'en preparacion' 
            WHERE id = :id");

        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();
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
