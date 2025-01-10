<?php

namespace app\helpers;

use app\models\Project;
use app\models\Role;


class IsPermited{

    public static function verify($project_id,$operator_id,$permission){
        $project = Project::findOne($project_id);
        $role = Role::getRole($operator_id, $project_id);
        if(!$role && $project->user_id !== $operator_id) return false;
        if($project->user_id === $operator_id || $role->hasPermission($permission)) return true;
    }
}