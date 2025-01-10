<?php

namespace app\controllers;

use app\core\Application;
use app\core\Controller;
use app\helpers\Dump;
use app\helpers\IsPermited;
use app\models\Project;
use app\models\Task;
use app\models\User;

class TaskController extends Controller {

    public function updateTaskStatus($request) {
        if (isset($request->getBody()['task_id']) && isset($request->getBody()['status'])) {
            $task = Task::findById($request->getBody()['task_id']);
            if(IsPermited::verify($task->project_id, $_SESSION['user']['id'], 'update task status')){
                $task->updateStatus($request->getBody()['status']);
                echo json_encode(['success' => true]);
                exit;
            } else {
                $_SESSION['error'] = "not authorized";
                header("Location: /kanban?id={$task->project_id}");
                exit;
            }
        }
        echo json_encode(['success' => false]);
        exit;
    }
    public function changeTaskTag($request) {
        if (isset($request->getBody()['task_id']) && isset($request->getBody()['tag'])) {
            $task = Task::findById($request->getBody()['task_id']);
            if(IsPermited::verify($task->project_id, $_SESSION['user']['id'], 'change tag')){
                $task->changeTag($request->getBody()['tag']);
                header("Location: /kanban?id={$task->project_id}");
                echo json_encode(['success' => true]);
                exit;
            } else {
                $_SESSION['error'] = "not authorized";
                header("Location: /kanban?id={$task->project_id}");
                exit;
            }
        }
        echo json_encode(['success' => false]);
        exit;
    }
    public function addTask($request){
        foreach ($request->getBody() as $key => $value) {
            $$key = $value;
        }
        if (!(isset($title) && isset($project_id))) {
            header("Location: /kanban?id=$project_id");
            exit();
        };
        if(IsPermited::verify($project_id, $_SESSION['user']['id'], 'create task')){
            $task = new Task($request->getBody());
            if($task->save()){
                header("Location: /kanban?id=$task->project_id");
                exit;
            }
        } else {
            $_SESSION['error'] = "not authorized";
            header("Location: /kanban?id=$project_id");
            exit;
        }
    }
    public function deleteTask($request){
        foreach ($request->getBody() as $key => $value) {
            $$key = $value;
        }
        if (!isset($task_id)) {
            header("Location: /kanban?id=$project_id");
            exit();
        }
        $task = Task::findById($task_id);
        if(IsPermited::verify($task->project_id, $_SESSION['user']['id'], 'delete task')){
            if($task->delete()){
                header("Location: /kanban?id=$task->project_id");
                exit;
            }
        } else {
            $_SESSION['error'] = "not authorized";
            header("Location: /kanban?id=$task->project_id");
            exit;
        }
    }
    public function assignTask($request){
        foreach ($request->getBody() as $key => $value) {
            $$key = $value;
        }
        if (!(isset($task_id) && isset($user_id))) {
            header("Location: /kanban?id=$project_id");
            exit();
        }
        $task = Task::findById($task_id);
        if(IsPermited::verify($task->project_id, $_SESSION['user']['id'], 'assign task')){
            if($task->assignTask($user_id)) {
                header("Location: /kanban?id=$task->project_id");
                exit;
            }
        } else {
            $_SESSION['error'] = "not authorized";
            header("Location: /kanban?id=$task->project_id");
            exit;
        }
    }
    public function unassignTask($request){
        foreach ($request->getBody() as $key => $value) {
            $$key = $value;
        }
        if (!(isset($task_id) && isset($user_id))) {
            header("Location: /kanban?id=$project_id");
            exit();
        }
        $task = Task::findById($task_id);
        if(IsPermited::verify($task->project_id, $_SESSION['user']['id'], 'assign task')){
            if($task->unassignTask($user_id)) {
                header("Location: /kanban?id=$task->project_id");
                exit;
            }
        } else {
            $_SESSION['error'] = "not authorized";
            header("Location: /kanban?id=$task->project_id");
            exit;
        }
    }
}