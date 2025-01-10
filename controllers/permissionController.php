<?php

namespace app\controllers;

use app\core\Application;
use app\core\Controller;
use app\helpers\Dump;
use app\helpers\IsPermited;
use app\models\Project;
use app\models\Role;
use app\models\Task;
use app\models\User;
use app\models\Permission;

class PermissionController extends Controller {
    
    public static function createPermission($request) {
        $name = $request->getBody()['permission_name'];
        if(isset($name) && isset($_SESSION['admin'])) {
            $permission = new Permission($name);
            if($permission->save()){
                header('Location: /admin-dashboard');
                exit();
            }
        }
    }
    public static function deletePermission($request) {
        $name = $request->getBody()['permission_name'];
        if(isset($name) && isset($_SESSION['admin'])) {
            $permission = Permission::getOne($name);
            if($permission->delete()){
                header('Location: /admin-dashboard');
                exit();
            }
        }
    }
    
}