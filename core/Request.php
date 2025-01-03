<?php

namespace app\core;

class Request{


    public function getPath(){
        $url = parse_url($_SERVER['REQUEST_URI'])['path'];
        return $url;
    }
    public function getMethod(){
        return strtolower($_SERVER['REQUEST_METHOD']);
    }
    public function getBody(){
        $body = [];
        if($this->getMethod() == 'get'){
            foreach($_GET as $key => $value){
                $body[$key] = htmlspecialchars($_GET[$key], ENT_QUOTES, 'UTF-8');
            }
        }else if($this->getMethod() == 'post'){
            foreach($_POST as $key => $value){
                $body[$key] = htmlspecialchars($_POST[$key], ENT_QUOTES, 'UTF-8');
            }
        }
        return $body;
    }
}