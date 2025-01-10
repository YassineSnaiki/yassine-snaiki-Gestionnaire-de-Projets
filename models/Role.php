<?php

namespace app\models;

use app\core\Application;

class Role {
    public  $name = null;
    public $permissions = [];

    public function __construct($name){
        $this->name = $name;
        $this->permissions = [];
    }
    public static function getAll(){
        $roles = Application::$app->db->query("select name from roles")->getAll();
        $rolesInstances = [];
        foreach ($roles as $role){
            $roleInstance = new self($role['name']);
            $roleInstance->permissions = self::getPermissions($role['name']);
            $rolesInstances[] =  $roleInstance;
        }
        return $rolesInstances;
    }

    public static function getRole($user_id,$project_id) {
        $role = Application::$app->db->query("SELECT r.name FROM roles r join contributions c on r.name = c.role_name WHERE c.user_id = ? and c.project_id = ?",[$user_id,$project_id])->getOne();
        if (!$role) {
            return false;
        }
        $roleInstance = new self($role['name']);
        $permissions = self::getPermissions($role['name']);
        $roleInstance->permissions = $permissions;
        return $roleInstance;
    }
    public static function getOne($name) {
        $role = Application::$app->db->query("SELECT name from roles where name = ?",[$name])->getOne();
        if (!$role) {
            return false;
        }
        $roleInstance = new self($role['name']);
        return $roleInstance;
    }

    public static function getPermissions($role) {
        $permissionsStrs = [];
        $permissions = Application::$app->db->query("select p.name from permissions p join roles_permissions rp on p.name = rp.permission_name join roles r on rp.role_name = r.name where r.name = ?",[$role])->getAll();
        foreach ($permissions as $permission) {
            $permissionsStrs[] = $permission["name"];
        }
        return $permissionsStrs;
    }


    public function hasPermission($permission){
        return in_array($permission,$this->permissions);
    }
    public static function changeRole($user_id,$project_id,$role) {
        Application::$app->db->query("UPDATE contributions set role_name = ? where user_id = ? and project_id = ?",[$role,$user_id,$project_id]);
        return true;
    }
    public function addPermission($permission) {
        Application::$app->db->query("insert into roles_permissions(role_name,permission_name) values(?,?)",[$this->name,$permission->name]);
        return true;
    }
    public function removePermission($permission) {
        Application::$app->db->query("delete from roles_permissions where role_name = ? and permission_name = ?",[$this->name,$permission->name]);
        return true;
    }
    public function save() {
        Application::$app->db->query("INSERT INTO roles (name) 
             VALUES (?)",[$this->name]);
        return true;
    }
    public function delete() {
        Application::$app->db->query("delete from roles where name = ?",[$this->name]);
        return true;
    }
}
