<?php

namespace app\models;

use app\core\Application;

class User {
    public  $id = null;
    public  $firstname;
    public  $lastname;
    public  $email;
    public  $password = '';

    //$id, $firstname, $lastname, $email, $password
    public function __construct($user){
        foreach ($user as $key => $value) {
            $this->$key = $value;
        }
    }
    public static function getAll(){
        $allUsers = Application::$app->db->query("select * from users")->getAll();
        return $allUsers;
    }
    public static function validate($credentials){
        foreach ($credentials as $cred) {
            if(empty($cred)) return false;
        }
        return true;
    }
    public static function findByEmail($email) {
        return Application::$app->db->query("SELECT * FROM users WHERE email = ?",[$email])->getOne();
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
    private static function prepare($sql) {
        return Application::$app->db->pdo->prepare($sql);
    }
    
}
