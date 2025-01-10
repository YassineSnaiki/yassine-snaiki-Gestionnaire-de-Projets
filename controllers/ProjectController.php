<?php

namespace app\controllers;

use app\core\Application;
use app\core\Controller;
use app\helpers\Dump;
use app\models\Project;
use app\models\Task;
use app\models\User;

class ProjectController extends Controller {
    
    public function __construct() {
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }
    }
    public function projects(){
        $projects = Project::findAll(); 
        return $this->render('projects', [
            'projects' => $projects,   
        ]);
    }
    public function addProject($request) {
       
        foreach($request->getBody() as $key => $value) {
            $$key = $value;
        }
        if (!empty($title) && !empty($description)) {
            $project = new Project($request->getBody());
            if ($project->save()) {
                header('Location: /');
                exit;
            }
        }
        $projects = Project::findAll();
        return $this->render('projects', [
            'projects' => $projects
        ]);
    }

    public function deleteProject($request){
        $project = Project::findOne($request->getBody()['project_id']);
        echo $project->id;
        if($project->user_id !== $_SESSION['user']['id']) {
            $_SESSION['error'] = 'not authorized';
            header('Location: /');
            exit;
        }
        if($project->delete()){
            header('Location: /');
            exit;
        }
    }
    public function kanban($request){
       
        $project_id = $request->getBody()['id'];
        if (!$project_id) {
            header('Location: /');
            exit;
        }
        $project = Project::findOne($project_id);
        if(!$project) {
            header('Location: /');
            exit;
        }
        // Check if user is owner or contributor
        $contributorsIds = array_map(function ($contributor) {
            return $contributor->id;
        }, $project->contributors);

        if ($project->user_id !== $_SESSION['user']['id'] && !in_array($_SESSION['user']['id'], $contributorsIds)) {
            header('Location: /');
            exit;
        }
        
        $tasks = Task::findByProject($project_id);
        $allUsers = User::getAll();

        return $this->render("kanban",[
            "project"=> $project,
            "tasks"=> $tasks,
            'allUsers'=>$allUsers
        ]);
    }
}
