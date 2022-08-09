<?php
require_once './models/Mesa.php';
require_once './models/UploadManager.php';
require_once './interfaces/IApiUsable.php';

class MesaController extends Mesa implements IApiUsable
{
    public function CargarUno($request, $response, $args)
    {

        $mesa = new Mesa();
        
        $id = $mesa->crearMesa();

        $payload = json_encode(array("mensaje" => "Mesa creada con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function SacarFoto($request, $response, $args)
    {
        $id_mesa = $args['id'];
        $mesa = Mesa::obtenerMesa(intval($id_mesa));

        if($mesa->estado == 'cerrada') {

          $payload = json_encode(array("mensaje" => "La mesa esta cerrada"));

          $response->getBody()->write($payload);
          return $response
            ->withHeader('Content-Type', 'application/json');
        }

        $imagesDirectory = "./Fotos_de_la_Mesa/";

        $id = $args['id'];
        $mesa = Mesa::obtenerMesa($id);

        if($mesa->id>0) {

          $fileManager = new UploadManager($imagesDirectory, $mesa->id, $_FILES);
          $path = UploadManager::getOrderImageNameExt($fileManager, $mesa->id);
          Mesa::guardarFoto($mesa->id, $path);

          $payload = json_encode(array("mensaje" => "Se guardo la foto con exito"));

          $response->getBody()->write($payload);
          return $response
            ->withHeader('Content-Type', 'application/json');

        }

        $payload = json_encode(array("mensaje" => "Error al guardar la foto"));

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
