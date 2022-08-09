<?php

class Mesa
{
    public $id;
    public $estado;
    public $foto;
    public $id_pedido;

    public function crearMesa()
    {

        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO mesas (estado, foto, id_pedido) VALUES (:estado, :foto, :id_pedido)");
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
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, estado, foto FROM mesas WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchObject('Pedido');
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

    public static function cerrarMesa($mesa)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE mesas SET estado = cerrada WHERE id = :id");
        $consulta->bindValue(':id', $mesa, PDO::PARAM_INT);
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