<?php

namespace app\controllers;

use app\core\Application;
use app\core\Controller;
use app\helpers\Dump;
use app\helpers\IsPermited;
use app\models\Permission;
use app\models\Project;
use app\models\Role;
use app\models\Task;
use app\models\User;

class RoleController extends Controller {

    public function changeRole($request) {
        $_SESSION['cf_open'] = true;
        foreach ($request->getBody() as $key => $value) {
            $$key = $value;
        }
        if (!(isset($user_id) && isset($role) && isset($project_id))) {
            header("Location: /kanban?id=$project_id");
            exit();
        };
        if(!IsPermited::verify($project_id,$_SESSION['user']['id'],'manage contributors')) {
            echo "jjjjjjjjjjjjjj";
            $_SESSION['error'] = 'not authorized';
            header("Location: /kanban?id=$project_id");
            exit(); 
        }
            if(Role::changeRole($user_id,$project_id,$role)){
                $_SESSION['error'] = "changes have been commited";
            }else{
                $_SESSION['error'] = "changes have not been commited";  
            }
            header("Location: /kanban?id=$project_id");
            exit();
    }
    public static function addRole($request) {
        $name = $request->getBody()['role_name'];
        if(isset($name) && isset($_SESSION['admin'])) {
            $role = new Role($name);
            if($role->save()){
                header('Location: /admin-dashboard');
                exit();
            }
        }
    }
    public static function addPermission($request) {
        
        $roleName = $request->getBody()['role_name'];
        $permissionName = $request->getBody()['permission_name'];
        $_SESSION['current_role'] = $roleName;
        if(isset($roleName) && isset($permissionName) && isset($_SESSION['admin'])) {
            $role = Role::getOne($roleName);
            $permission = Permission::getOne($permissionName); 
            if($role->addPermission($permission)){
                header('Location: /admin-dashboard');
                exit();
            }              
        }
    }
    
    public static function removePermission($request) {
        
        $roleName = $request->getBody()['role_name'];
        $permissionName = $request->getBody()['permission_name'];
        $_SESSION['current_role'] = $roleName;
        if(isset($roleName) && isset($permissionName) && isset($_SESSION['admin'])) {
            $role = Role::getOne($roleName);
            $permission = Permission::getOne($permissionName);
            if($role->removePermission($permission)){
                header('Location: /admin-dashboard');
                exit();
            }
        }
    }
    public static function deleteRole($request) {
        $name = $request->getBody()['role_name'];
        if(isset($name) && isset($_SESSION['admin'])) {
            $role = Role::getOne($name);
            if($role->delete()){
                header('Location: /admin-dashboard');
                exit();
            }
        }
    }
}