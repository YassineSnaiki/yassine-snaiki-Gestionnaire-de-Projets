<?php

namespace app\core;

class Router{
    private $routes = [];
    private $request;
    private $response;
    public function __construct(Request $request, Response $response){
        $this->request = $request;
        $this->response = $response;
    }
    public function get($path,$callback){
        $this->routes['get'][$path]=$callback;
    }
    public function post($path,$callback){
        $this->routes['post'][$path]=$callback;
    }
    public function resolve(){

        $path = $this->request->getPath();
        $method = $this->request->getMethod();
        $callback = $this->routes[$method][$path] ?? false;
        if(!$callback) {
            $this->response->setStatusCode(404);
            return $this->renderContent("<H1>not found 404</H1>");
        }
        if(is_string($callback)) 
        return $this->renderView($callback);
        if(is_array($callback)) {
            $callback[0] = new $callback[0]();
            return call_user_func($callback,$this->request);
        }
        return call_user_func($callback);
    }
    public function renderView($view,$params=[]){

        $layoutContent = $this->renderLayout();
        $viewContent = $this->renderOnlyView($view, $params);
        return str_replace('{{content}}', $viewContent, $layoutContent);
    }
    private function renderContent($content) {
        $layoutContent = $this->renderLayout();
        return str_replace('{{content}}', $content, $layoutContent);
    }
    private function renderLayout(){
        ob_start();
        include Application::$ROOT_DIR."/views/layouts/main.php";
        return ob_get_clean();
    }
    private function renderOnlyView($view, $params=[]){
        foreach($params as $key=>$value){
            $$key = $value;
        }
        ob_start();
        include Application::$ROOT_DIR."/views/$view.php";
        return ob_get_clean();
    }
    
}