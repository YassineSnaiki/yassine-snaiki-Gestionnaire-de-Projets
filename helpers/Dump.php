<?php

namespace app\helpers;


class Dump{

    public static function dump($obj){
        echo "<pre>";
        var_dump($obj);
        echo "</pre>";
    }
}