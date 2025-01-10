<?php

namespace app\models;

use app\core\Application;

class Permission {
    public  $name = null;

    public function __construct($name){
            $this->name = $name;
    }
    public static function getAll(){
        $permissions = Application::$app->db->query("select name from permissions")->getAll();
        $permissionInstances = [];
        foreach ($permissions as $permission){
            $permissionInstances[] =  new self($permission['name']);
        }
        return $permissionInstances;
    }
    public static function getOne($name){
        $permission = Application::$app->db->query("select name from permissions where name = ?",[$name])->getOne();
        return new self( $permission["name"] );
    }
    public function save() {
        Application::$app->db->query("INSERT INTO permissions (name) 
             VALUES (?)",[$this->name]);
        return true;
    }
    public function delete() {
        Application::$app->db->query("delete from permissions where name = ?",[$this->name]);
        return true;
    }
}
