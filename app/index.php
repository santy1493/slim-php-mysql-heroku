<?php
// Error Handling
error_reporting(-1);
ini_set('display_errors', 1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;
use Slim\Routing\RouteContext;

require __DIR__ . '/../vendor/autoload.php';

require_once './db/AccesoDatos.php';
require_once './middlewares/AutJWT.php';
require_once './middlewares/Validaciones.php';
require_once './middlewares/Logger.php';

require_once './controllers/PedidoController.php';
require_once './controllers/EmpleadoController.php';
require_once './controllers/ProductoController.php';
require_once './controllers/ItemController.php';
require_once './controllers/MesaController.php';
require_once './controllers/ArchivoController.php';
require_once './models/ItemDTO.php';

// Load ENV
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

// Instantiate App
$app = AppFactory::create();

// Add error middleware
$app->addErrorMiddleware(true, true, true);

// Add parse body
$app->addBodyParsingMiddleware();

// Routes

$app->group('/login', function (RouteCollectorProxy $group) {
  $group->post('[/]', \EmpleadoController::class . ':Login');
})//->add(\Logger::class . ':LogOperacion');
  ->add(\Validaciones::class . ':verificarParametrosLogin');

$app->group('/pedidos', function (RouteCollectorProxy $group) {
    $group->get('[/]', \PedidoController::class . ':TraerTodos');
    $group->get('/{id}', \PedidoController::class . ':TraerUno');
    $group->post('[/]', \PedidoController::class . ':CargarUno')
      ->add(\Validaciones::class . ':verificarMozo');
  })
    ->add(\Logger::class . ':LogOperacion')
    ->add(\Validaciones::class . ':verificarToken');

$app->group('/items', function (RouteCollectorProxy $group) {
    $group->get('[/]', \PedidoController::class . ':TraerTodos');
    $group->get('/pendientes', \ItemController::class . ':TraerPendientesDTO');
    $group->get('/pendientesDTO', \ItemController::class . ':TraerPendientes');
    $group->post('/cambiar_a_en_preparacion/{id}', \ItemController::class . ':CambiarItemAEnPreparacion');
    $group->post('/cambiar_a_listo_para_servir/{id}', \ItemController::class . ':CambiarItemAListoParaServir');
    $group->get('/{id}', \PedidoController::class . ':TraerUno');
    $group->post('[/]', \PedidoController::class . ':CargarUno');
  });
$app->group('/productos', function (RouteCollectorProxy $group) {
    $group->get('[/]', \ProductoController::class . ':TraerTodos');
    $group->get('/{id}', \ProductoController::class . ':TraerUno');
    $group->post('/{sector}', \ProductoController::class . ':CargarUno');
  });
$app->group('/mesas', function (RouteCollectorProxy $group) {
    $group->get('[/]', \ProductoController::class . ':TraerTodos');
    $group->get('/{id}', \ProductoController::class . ':TraerUno');
    $group->post('/sacar_foto/{id}', \MesaController::class . ':SacarFoto');
    $group->post('/cerrar_mesa/{id}', \MesaController::class . ':CerrarMesa')
    ->add(\Validaciones::class . ':verificarAdmin');
    $group->post('[/]', \MesaController::class . ':CargarUno');
  })
  ->add(\Validaciones::class . ':verificarToken');
$app->group('/itemDTO', function (RouteCollectorProxy $group) {
    $group->get('[/]', \ProductoController::class . ':TraerTodos');
    $group->get('/{id}', \ItemController::class . ':TraerUnoDTO');
    $group->post('/{sector}', \ProductoController::class . ':CargarUno');
  });
$app->group('/encuestas', function (RouteCollectorProxy $group) {
    $group->get('[/]', \ProductoController::class . ':TraerTodos');
    $group->get('/{id}', \ItemController::class . ':TraerUnoDTO');
    $group->post('/{sector}', \ProductoController::class . ':CargarUno');
    $group->post('/puntuar_encuesta/{cod_pedido}', \MesaController::class . ':PuntuarEncuesta');
  });
$app->group('/empleados', function (RouteCollectorProxy $group) {
    $group->get('[/]', \EmpleadoController::class . ':TraerTodos');
    $group->get('/{id}', \EmpleadoController::class . ':TraerUno');
    $group->post('/{sector}', \EmpleadoController::class . ':CargarUno')
      ->add(\Validaciones::class . ':verificarAdmin');
  })
    ->add(\Logger::class . ':LogOperacion')
    ->add(\Validaciones::class . ':verificarToken');

$app->group('/archivo', function (RouteCollectorProxy $group) {
  $group->get('/guardar', \ArchivoController::class . ':Guardar');
  $group->get('/leer', \ArchivoController::class . ':Leer');
  $group->post('/descargar_pdf', \ArchivoController::class . ':DescargarPDF');
});

$app->get('[/]', function (Request $request, Response $response) {    
    $response->getBody()->write("Slim Framework 4 PHP");
    return $response;

});

$app->run();
