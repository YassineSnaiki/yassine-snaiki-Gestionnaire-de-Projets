<?php

namespace app\controllers;

use app\core\Application;
use app\core\Controller;
use app\helpers\Dump;
use app\models\Project;
use app\models\Task;

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
            'projects' => $projects
        ]);
    }
    public function handleProjects($request) {
       
        foreach($request->getBody() as $key => $value) {
            $$key = $value;
        }
        if (!empty($title) && !empty($description)) {
            $project = new Project($request->getBody());
            if ($project->save()) {
                header('Location: /projects');
                exit;
            }
        }
        $projects = Project::findAll();
        return $this->render('projects', [
            'projects' => $projects
        ]);
    }
    public function kanban($request){
        $project_id = $request->getBody()['id'];
        if (!$project_id) {
            header('Location: /projects');
            exit;
        }
        $project = Project::findOne($project_id);
        $tasks = Task::findByProject($project_id);
        return $this->render("kanban",[
            "project"=> $project,
            "tasks"=> $tasks
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
}
