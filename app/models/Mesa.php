<?php

class Mesa
{
    public $id;
    public $estado;
    public $foto;
    public $id_pedido;
    public $contador_pedidos;
    public $montos_acumulados;

    public function crearMesa()
    {

        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO mesas (estado, foto, id_pedido, contador_pedidos, montos_acumulados) VALUES (:estado, :foto, :id_pedido, 0, 0)");
        $consulta->bindValue(':estado', 'cerrada',PDO::PARAM_STR);
        $consulta->bindValue(':foto', null, PDO::PARAM_NULL);
        $consulta->bindValue(':id_pedido', null, PDO::PARAM_NULL);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, estado, foto FROM mesas");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Mesa');
    }

    public static function obtenerMesa($id)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, estado, foto, id_pedido, contador_pedidos, montos_acumulados FROM mesas WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchObject('Mesa');
    }

    public static function obtenerNumeroDePedido($id)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, estado, foto, id_pedido FROM mesas WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchObject('Mesa');
    }

    public static function abrirMesa($id, $id_pedido)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE mesas SET estado = 'con cliente esperando pedido', id_pedido = :id_pedido WHERE id = :id");
        $consulta->bindValue(':id_pedido', $id_pedido, PDO::PARAM_INT);
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();
    }

    public function modificarMesa()
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE mesas SET estado = :estado, foto = :foto WHERE id = :id");
        $consulta->bindValue(':estado', $this->usuario, PDO::PARAM_STR);
        $consulta->bindValue(':foto', $this->clave, PDO::PARAM_STR);
        $consulta->bindValue(':id', $this->id, PDO::PARAM_INT);
        $consulta->execute();
    }

    public static function cerrandoMesa($id)
    {
        $mesa = Mesa::obtenerMesa($id);
        var_dump($mesa);
        $pedido = Pedido::obtenerPedido($mesa->id_pedido);
        $contador_pedidos = intval($mesa->contador_pedidos) + 1;
        $montos_acumulados = floatval($mesa->montos_acumulados) + floatval($pedido->precio_total);

        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE mesas SET estado = 'cerrada', foto = null, id_pedido = 0, contador_pedidos = :contador_pedidos, montos_acumulados = :montos_acumulados WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->bindValue(':contador_pedidos', $contador_pedidos, PDO::PARAM_INT);
        $consulta->bindValue(':montos_acumulados', $montos_acumulados, PDO::PARAM_STR);
        $consulta->execute();
    }

    public static function guardarFoto($id, $foto)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE mesas SET foto = :foto WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->bindValue(':foto', $foto, PDO::PARAM_STR);
        $consulta->execute();
    }
}