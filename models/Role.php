<?php

namespace app\models;

use app\core\Application;

class Role {
    public  $name = null;

    public function __construct($name){
            $this->$name = $name;
    }
    public static function getAll(){
        $allRoles = Application::$app->db->query("select * from roles")->getAll();
        return $allRoles;
    }
    public static function getRole($user_id,$project_id) {
        return Application::$app->db->query("SELECT r.name FROM roles r join contributions c on r.name = c.role_name WHERE c.user_id = ? and c.project_id = ?",[$user_id,$project_id])->getOne()["role_name"];
    }

    
    public function checkPassword($password) {
        return password_verify($password,$this->password);
    }
    public function save() {
        $id = Application::$app->db->query("INSERT INTO users (firstname, lastname, email, password) 
             VALUES (?, ?, ?, ?) RETURNING id",[$this->firstname,$this->lastname,$this->email,$this->password])->getOne()['id'];
        $this->id = $id;
        return true;
    }
}
