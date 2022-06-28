<?php
require_once './controllers/LogController.php';
require_once 'AutJWT.php';

class Logger
{
    public static function LogOperacion($request, $handler)
    {
        $token = $request->getHeaderLine('token');
        $response = $handler->handle($request);
        if ($token) { // Esta logueado
            // Datos del empleado logueado
            $empl = AutJWT::ObtenerData($token);
            // Registramos todas la operaciones
            // $empleadoId, $sectorId, $path, $method
            LogController::CargarUno($empl->id, $empl->sector, $request->getUri()->getPath(), $request->getMethod());
        } else { // No Logueado
            $resp = json_decode($retorno->getBody());
            if ($resp->token) { // Verificamos si es por login
                $empl = AutJWT::ObtenerData($resp->token);
                // Registramos todas la operaciones
                // $empleadoId, $sectorId, $path, $method                
                LogController::CargarUno($empl->id, $empl->sector, $request->getUri()->getPath(), $request->getMethod());
            } else {
                // Registramos todas la operaciones
                // $empleadoId, $sectorId, $path, $method
                LogController::CargarUno(null, null, $request->getUri()->getPath(), $request->getMethod());
            }
        }
        return $response;
    }
}