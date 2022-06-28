<?php

class Empleado
{
    public $id;
    public $nombre;
    public $sector;
    public $usuario;
    public $clave;
    public $estado;
    public $fecha_alta;

    public function crearEmpleado()
    {

        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO empleados (nombre, sector, usuario, clave, estado, fecha_alta) VALUES (:nombre, :sector, :usuario, :clave, :estado, :fecha_alta)");
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':sector', $this->sector, PDO::PARAM_STR);
        $consulta->bindValue(':usuario', $this->usuario, PDO::PARAM_STR);
        $consulta->bindValue(':clave', $this->clave, PDO::PARAM_STR);
        $consulta->bindValue(':estado', 1, PDO::PARAM_INT);
        $consulta->bindValue(':fecha_alta', date("Y-m-d"), PDO::PARAM_STR);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, nombre, sector, usuario, clave, estado, fecha_alta FROM empleados");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Empleado');
    }

    public static function obtenerEmpleado($id)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, nombre, sector, usuario, clave, estado, fecha_alta FROM empleados WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchObject('Empleado');
    }

    public static function obtenerEmpleadoPorUsuario($usuario)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, nombre, sector, usuario, clave, estado, fecha_alta FROM empleados WHERE usuario = :usuario");
        $consulta->bindValue(':usuario', $usuario, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchObject('Empleado');
    }

    public function modificarUsuario()
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE usuarios SET usuario = :usuario, clave = :clave WHERE id = :id");
        $consulta->bindValue(':usuario', $this->usuario, PDO::PARAM_STR);
        $consulta->bindValue(':clave', $this->clave, PDO::PARAM_STR);
        $consulta->bindValue(':id', $this->id, PDO::PARAM_INT);
        $consulta->execute();
    }

    public static function borrarUsuario($id)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE empleados SET estado = 0 WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        //$consulta->bindValue(':fechaBaja', date_format($fecha, 'Y-m-d H:i:s'));
        $consulta->execute();
    }
}