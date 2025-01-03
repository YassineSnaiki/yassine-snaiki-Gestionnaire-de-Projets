<?php

namespace app\core;

class Application{
public static $ROOT_DIR;
public Router $router;
public Request $request;
public Response $response;
public static $app;
public Database $db;

public function __construct($rootDir){
    self::$app = $this;
    self::$ROOT_DIR = $rootDir;
    $this->response = new Response();
    $this->request = new Request();
    $this->router = new Router($this->request,$this->response);
    $this->db = new Database();
}
public function run(){
    echo $this->router->resolve();
}
}