<?php

require __DIR__ . '/react.phar';

use React\EventLoop\Factory;
use React\Socket\Server;
use React\Http\Request;
use React\Http\Response;

require 'vendor/autoload.php';

/*
ob_start();
include 'index.php';
$string = ob_get_clean();

$loop = Factory::create();
$socket = new Server($loop);
$server = new \React\Http\Server($socket);
$server->on('request', function (Request $reques, Response $response) {

    ob_start();
    require __DIR__ . '/nlf.phar';
    $string = ob_get_clean();
    
    $response->writeHead(200, array('Content-Type' => 'text/plain'));
    
    $response->end($string);
});
$socket->listen(isset($argv[1]) ? $argv[1] : 0, '0.0.0.0');
echo 'Listening on ' . $socket->getPort() . PHP_EOL;
$loop->run();*/

$app = function ($request, $response) {
    ob_start();
    require __DIR__ . '/nlf.phar';
    $string = ob_get_clean();
    $response->writeHead(200, array('Content-Type' => 'text/plain'));
    $response->end($string);
};

$loop = React\EventLoop\Factory::create();
$socket = new React\Socket\Server($loop);
$http = new React\Http\Server($socket, $loop);

$http->on('request', $app);
echo "Server running at http://127.0.0.1:1337\n";

$socket->listen(1337);
$loop->run();
/*
$server = new Server(function (ServerRequestInterface $request) {

    //$body = "The method of the request is: " . $request->getMethod();
    //$body .= "The requested path is: " . $request->getUri()->getPath();

    return new Response(
        200,
        array('Content-Type' => 'text/plain'),
        function(){
            require __DIR__ . '/nlf.phar';
        }
        );
});

$server->run();*/