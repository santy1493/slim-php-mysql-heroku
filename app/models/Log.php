<?php

class Log
{
    public $id;
    public $empleado_id;
    public $sector;
    public $path;
    public $method;
    public $fecha_alta;

    public static function crearLog($empleado_id, $sector, $path, $method)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("INSERT INTO logs (empleado_id, sector, path, method, fecha_alta) VALUES (:empleado_id, :sector, :path, :method, :fecha_alta)");
        $fecha = new DateTime(date("d-m-Y H:i:s"));
        $consulta->bindValue(':empleado_id', $empleado_id, PDO::PARAM_INT);
        $consulta->bindValue(':sector', $sector, PDO::PARAM_INT);
        $consulta->bindValue(':path', $path, PDO::PARAM_STR);
        $consulta->bindValue(':method', $method, PDO::PARAM_STR);
        $consulta->bindValue(':fecha_alta', date("Y-m-d H:i:s"), PDO::PARAM_STR);
        $consulta->execute();

        return $objAccesoDato->obtenerUltimoId();
    }

    public function obtenerLogs()
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("SELECT * FROM logs");
        $consulta->execute();
        return $consulta->fetchObject("Log");
    }
}