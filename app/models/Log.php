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

    public static function crearLogArchivo($empleado_id, $sector, $path, $method, $fecha_alta)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("INSERT INTO logs (empleado_id, sector, path, method, fecha_alta) VALUES (:empleado_id, :sector, :path, :method, :fecha_alta)");
        $fecha = new DateTime(date("d-m-Y H:i:s"));
        $consulta->bindValue(':empleado_id', $empleado_id, PDO::PARAM_INT);
        $consulta->bindValue(':sector', $sector, PDO::PARAM_INT);
        $consulta->bindValue(':path', $path, PDO::PARAM_STR);
        $consulta->bindValue(':method', $method, PDO::PARAM_STR);
        $consulta->bindValue(':fecha_alta', $fecha_alta, PDO::PARAM_STR);
        $consulta->execute();

        return $objAccesoDato->obtenerUltimoId();
    }

    public static function obtenerLogs()
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("SELECT * FROM logs");
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Log');
    }

    public static function borrarTabla(){
        $objDataAccess = AccesoDatos::obtenerInstancia();
        $query = $objDataAccess->prepararConsulta("DELETE FROM logs WHERE 1=1;");
        $query->execute();

        return $query->rowCount() > 0;
    }

    public static function GuardarCSV($entitiesList, $filename = './Archivos/logs.csv'):bool{
        $success = false;
        $directory = dirname($filename, 1);
        echo "GuardarCSV";
        
        try {
            if(!file_exists($directory)){
                mkdir($directory, 0777, true);
            }
            $file = fopen($filename, "w");
            if ($file) {
                foreach ($entitiesList as $entity) {
                    $line = $entity->id . "," . $entity->empleado_id . "," . $entity->sector . "," . $entity->path . "," . $entity->method . "," . $entity->fecha_alta .PHP_EOL;
                    fwrite($file, $line);
                    $success = true;
                }
            }
        } catch (\Throwable $th) {
            echo "Error al guardar el archivo<br>";
        }finally{
            fclose($file);
        }

        return $success;
    }

    public static function LeerCSV($filename="./Archivos/logs.csv"){
        $file = fopen($filename, "r");
        $array = array();
        try {
            if (!is_null($file) && self::borrarTabla() > 0){
                echo "<h2>Tabla borrada con exito. Insertando datos del archivo</h3>";
            }
            while (!feof($file)) {
                $line = fgets($file);
                
                if (!empty($line)) {
                    $line = str_replace(PHP_EOL, "", $line);
                    $loginsArray = explode(",", $line);
                    var_dump($loginsArray);
                    Log::crearLogArchivo($loginsArray[1], $loginsArray[2], $loginsArray[3], $loginsArray[4], $loginsArray[5]);
                }
            }

            $array = self::obtenerLogs();

        } catch (\Throwable $th) {
            echo "Error while reading the file";
        }finally{
            fclose($file);
            return $array;
        }
    }
}