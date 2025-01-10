<?php

namespace app\controllers;

use app\core\Application;
use app\core\Controller;
use app\helpers\Dump;
use app\helpers\IsPermited;
use app\models\Project;
use app\models\Task;
use app\models\User;

class ContributionController extends Controller {
    public function addContribution($request){
        $_SESSION['cf_open'] = true;
        foreach ($request->getBody() as $key => $value) {
            $$key = $value;
        }
        if (!(isset($user_id) && isset($project_id))) {
            header("Location: /kanban?id=$project_id");
            exit();
        };
        if(IsPermited::verify($project_id,$_SESSION['user']['id'],'manage contributors')){
            $project = Project::findOne( $project_id );
            if($project->addContribution( $user_id )){
        }  
            else $_SESSION['error'] = "not authorized";
        }
        header("Location: /kanban?id=$project_id");
        exit;
         
    }
    public function deleteContribution($request){
        $_SESSION['cf_open'] = true;
        foreach ($request->getBody() as $key => $value) {
            $$key = $value;
        }
        if (!(isset($user_id) && isset($project_id))) {
            header("Location: /kanban?id=$project_id");
            exit();
        };
        if(IsPermited::verify($project_id,$_SESSION['user']['id'],'manage contributors')){
            $project = Project::findOne( $project_id );
            if($project->deleteContribution($request->getBody()["user_id"])){
            }else $_SESSION['error'] = "not autorized";
        } 
        header("Location: /kanban?id=$project_id");
        exit;
    }
}
