<?php

namespace app\controllers;

use app\core\Request;
use app\core\Controller;

class SiteController extends Controller {

    public  function home() {
        $params = [
            "name"=>"chila3ba"
        ];
        return $this->render('home',$params);
    }
    public  function contact() {
        $params = [
            "name"=>"chila3ba"
        ];
        return self::render('contact',$params);
    }
    public static function handleContact(Request $request) {
        return 'handeling submitted data';
    }
}