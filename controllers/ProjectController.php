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
    public function handleProjects($request) {
       
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

    public function updateTaskStatus($request) {
        if (isset($request->getBody()['task_id']) && isset($request->getBody()['status'])) {
            $task = Task::findById($request->getBody()['task_id']);
            $task->updateStatus($request->getBody()['status']);
            echo json_encode(['success' => true]);
            exit;
        }
        echo json_encode(['success' => false]);
        exit;
    }
    public function changeTaskTag($request) {
        if (isset($request->getBody()['task_id']) && isset($request->getBody()['tag'])) {
            $task = Task::findById($request->getBody()['task_id']);
            $task->changeTag($request->getBody()['tag']);
            header("Location: /kanban?id={$task->project_id}");
            // echo json_encode(['success' => true]);
            // exit;
        }
        // echo json_encode(['success' => false]);
        // exit;
    }
    public function addTask($request){
        $task = new Task($request->getBody());
        if($task->save()){
            header("Location: /kanban?id=$task->project_id");
            exit;
        }
    }
    public function deleteTask($request){
        $task = Task::findById( $request->getBody()["task_id"] );
        if($task->delete()){
            header("Location: /kanban?id=$task->project_id");
            exit;
        }
    }
    public function addContribution($request){
        $project = Project::findOne( $request->getBody()["project_id"] );
        if($project->addContribution($request->getBody()["user_id"])){
            $id = $request->getBody()['project_id'];
            $_SESSION['cf_open'] = true;
            header("Location: /kanban?id=$id");
            exit;
        }   
    }
    public function deleteContribution($request){
        $project = Project::findOne( $request->getBody()["project_id"] );
        if($project->deleteContribution($request->getBody()["user_id"])){
            $id = $request->getBody()['project_id'];
            $_SESSION['cf_open'] = true;
            header("Location: /kanban?id=$id");
            exit;
        }   
    }

    public function assignTask($request){
        $task = Task::findById( $request->getBody()["task_id"]);
        if($task->assignTask($request->getBody()["user_id"])) {
            $id = $task->project_id;
            header("Location: /kanban?id=$id");
            exit;
        }
    }
    public function unassignTask($request){
        $task = Task::findById( $request->getBody()["task_id"]);
        if($task->unassignTask($request->getBody()["user_id"])) {
            $id = $task->project_id;
            header("Location: /kanban?id=$id");
            exit;
        }
    }
}
