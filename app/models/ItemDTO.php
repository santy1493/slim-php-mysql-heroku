<?php

require_once './models/Item.php';
require_once './models/Producto.php';

class ItemDTO
{
    public $id;
    public $descripcion;
    public $cantidad;
    public $estado;
    public $tiempo_estimado;
    public $precio;
    public $sector;
    public $id_pedido;

    public function crearItem()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO items (id_producto, cantidad, estado, tiempo_estimado, id_pedido) VALUES (:id_producto, :cantidad, :estado, :tiempo_estimado, :id_pedido)");
        $consulta->bindValue(':id_producto', $this->id_producto, PDO::PARAM_INT);
        $consulta->bindValue(':cantidad', $this->cantidad, PDO::PARAM_INT);
        $consulta->bindValue(':estado', 'pendiente', PDO::PARAM_STR);
        $consulta->bindValue(':tiempo_estimado', '', PDO::PARAM_STR);
        $consulta->bindValue(':id_pedido', $this->id_pedido, PDO::PARAM_INT);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, descripcion, cod_sector, estado, tiempo_estimado, id_pedido FROM items");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Item');
    }

    public static function obtenerItemDTO($id)
    {
        $item = Item::obtenerItem($id);
        
        $producto = Producto::obtenerProducto($item->id_producto);

        $itemDTO = new ItemDTO();

        $itemDTO->id = $item->id;
        $itemDTO->descripcion = $producto->descripcion;
        $itemDTO->cantidad = $item->cantidad;
        $itemDTO->estado = $item->estado;
        $itemDTO->tiempo_estimado = $item->tiempo_estimado;
        $itemDTO->precio = $producto->precio;
        $itemDTO->sector = $producto->sector;
        $itemDTO->id_pedido = $item->id_pedido;

        return $itemDTO;
    }

    public static function obtenerItemsDTOPorIdPedido($id_pedido) {

        $items = Item::obtenerItemsPorIdPedido($id_pedido);
        $itemsDTOArray = [];

        foreach($items as $item) {
            array_push($itemsDTOArray, ItemDTO::obtenerItemDTO($item->id));
        }

        return $itemsDTOArray;
    }

    public static function obtenerItemsPendientes($sector) {

        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT i.id, p.descripcion, p.sector, i.estado, i.tiempo_estimado, i.id_pedido, i.estado, i.cantidad FROM items AS i INNER JOIN productos AS p ON p.id = i.id_producto WHERE i.estado = 'pendiente' AND p.sector = :sector;");
        $consulta->bindValue(':sector', $sector, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'ItemDTO');
    }

    public static function obtenerItemsEnPreparacion($sector) {

        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT i.id, p.descripcion, p.sector, i.tiempo_estimado, i.id_pedido, i.estado, i.cantidad FROM items AS i INNER JOIN productos AS p ON p.id = i.id_producto WHERE i.estado = 'en preparacion' AND p.sector = :sector;");
        $consulta->bindValue(':sector', $sector, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'ItemDTO');
    }

    public static function obtenerItemsAdmin() {

        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT i.id, p.descripcion, p.sector, i.estado, i.cantidad FROM items AS i INNER JOIN productos AS p ON p.id = i.id_producto;");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'ItemDTO');
    }

    public static function cambiarAEnPreparacion($id)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta(
            "UPDATE items SET 
            estado = 'en preparacion' 
            WHERE id = :id");

        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();
    }

    public function modificarItem()
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta(
            "UPDATE usuarios SET 
            descripcion = :descripcion,
            cod_sector = :cod_sector, 
            estado = :estado, 
            tiempo_estimado = :tiempo_estimado
            WHERE id = :id");

        $consulta->bindValue(':usuario', $this->usuario, PDO::PARAM_STR);
        $consulta->bindValue(':clave', $this->clave, PDO::PARAM_STR);
        $consulta->bindValue(':id', $this->id, PDO::PARAM_INT);
        $consulta->execute();
    }

    public static function borrarUsuario($usuario)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE usuarios SET fechaBaja = :fechaBaja WHERE id = :id");
        $fecha = new DateTime(date("d-m-Y"));
        $consulta->bindValue(':id', $usuario, PDO::PARAM_INT);
        $consulta->bindValue(':fechaBaja', date_format($fecha, 'Y-m-d H:i:s'));
        $consulta->execute();
    }
}