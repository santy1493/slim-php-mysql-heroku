<?php

 require_once './models/Log.php';

 class ArchivoController extends Producto{

    public function Leer($request, $response, $args){
        $filename = './Archivos/logs.csv';
        $dataToRead = Log::LeerCSV($filename);
        $payload = json_encode(array("Error" => 'Algo fallo'));
        if(!is_null($dataToRead)){
            echo "<h1>Archivo insertado en la base de datos</h1>";
            $payload = json_encode(array("Success" => 'Archivo insertado en la base de datos', "Logs" => $dataToRead));
        }
        
        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function Guardar($request, $response, $args){
        $logs = Log::obtenerLogs();
        $filename = './Archivos/logs.csv';
        $payload = json_encode(array("Error" => 'Archivo no guardado',"Logs" => 'Error al guardar el archivo'));
        if(Log::GuardarCSV($logs, $filename)){
            echo 'Archivo guardado en '.$filename;
            $payload = json_encode(array("Success" => 'Archivo guardado en Archivos/logs.csv',"Logs" => $logs));
        }
        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function DownloadPdf($request, $response, $args){
        $params = $request->getParsedBody();
        $directory = './Reports_Files/';
        $payload = json_encode(array("Error" => 'File not Saved',"Best Polls" => 'Error While Writing The File'));
        
        if($params['Amount_Polls']){
            $amountPolls = $params['Amount_Polls'];
            $payload = Poll::DownloadPdf($directory, $amountPolls);
            echo 'File Saved in '.$directory;
        }
        
        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
 }
?>