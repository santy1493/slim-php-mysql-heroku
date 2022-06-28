<?php

class Item
{
    public $id;
    public $id_producto;
    public $cantidad;
    public $estado;
    public $tiempo_estimado;
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

    public static function obtenerItem($id)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, id_producto, cantidad, estado, tiempo_estimado, id_pedido FROM items WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchObject('Item');
    }

    public static function obtenerItemsPorIdPedido($id_pedido) {

        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, id_producto, cantidad, estado, tiempo_estimado, id_pedido FROM items WHERE id_pedido = :id_pedido");
        $consulta->bindValue(':id_pedido', $id_pedido, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Item');
    }

    public static function obtenerItemsPendientes() {

        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, id_producto, cantidad, estado, tiempo_estimado, id_pedido FROM items WHERE estado = 'pendiente'");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Item');
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