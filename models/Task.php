<?php

namespace app\models;

use app\core\Application;

class Task {
    public  $id = null;
    public string $title;
    public string $description;
    public string $project_id;
    public string $status; // 'todo', 'doing', 'review', 'done'
    public string $tag;
    public string $created_at;
    public $assignees = [];

    public function __construct($task){
        foreach ($task as $key => $value) {
            $this->$key = $value;
        }
    }
    public static function findByProject($project_id) {
        // $tasks = Application::$app->db->query("SELECT * FROM tasks WHERE project_id = ? ORDER BY created_at ASC", [$project_id])->getAll();
        // $taskInstances = [];
        // foreach ($tasks as $task) {
        //     $assignees = Application::$app->db->query('select u.firstname,u.lastname,u.email from users u join assignments a on u.id = a.user_id join tasks t on a.task_id = t.id where t.id = ?',[$task['id']])->getAll();
        //     $task['assignees'] = [];
        //     foreach ($assignees as $assignee) {
        //     $task['assignees'][]= new User($assignee);
        // }
        //     $taskInstances[] = new self($task);
        // }
        // return $taskInstances;
        $tasksIds = Application::$app->db->query("SELECT id FROM tasks WHERE project_id = ? ORDER BY created_at ASC", [$project_id])->getAll();
        $tasks = [];
        foreach ($tasksIds as $taskId) {
            $tasks[] = Task::findById($taskId['id']);
        }
        
        $taskInstances = [];
        foreach ($tasks as $task) {
            $taskInstances[] = new self($task);
        }
        return $taskInstances;
    }



    public static function findById($id) {
        $task = Application::$app->db->query("select * from tasks where id = ?",[$id])->getOne();
        $assignees = Application::$app->db->query('select u.id, u.firstname,u.lastname,u.email from users u join assignments a on u.id = a.user_id join tasks t on a.task_id = t.id where t.id = ?',[$id])->getAll();
        $task['assignees'] = [];
        foreach ($assignees as $assignee) {
            $task['assignees'][]= new User($assignee);
        }
        $taskInstance = new self( $task );
        return $taskInstance;
    }

    public function save() {
        $row = Application::$app->db->query("INSERT INTO tasks (title, description, project_id, status) 
             VALUES (?,?,?,?) RETURNING id,created_at",[
            $this->title,
            $this->description,
            $this->project_id,
            $this->status ?? 'todo'
        ])->getOne();
        $this->id = $row['id'];
        $this->created_at = $row['created_at'];
        return true;
    }
    public function delete() {
        Application::$app->db->query('DELETE FROM tasks WHERE id = ?',[$this->id]);
        return true;
    }
    public function updateStatus($status) {
        Application::$app->db->query("UPDATE tasks SET status = ? WHERE id = ?",[$status, $this->id]);
        return true;
    }
    public function changeTag($tag) {
        Application::$app->db->query("UPDATE tasks SET tag = ? WHERE id = ?",[$tag, $this->id]);
        return true;
    }
    public function assignTask($user_id) {
        Application::$app->db->query("insert into assignments(user_id,task_id) values(?,?)",[$user_id,$this->id]);
        return true;
    }
    public function unassignTask($user_id) {
        Application::$app->db->query("delete from assignments where user_id = ? and task_id = ?",[$user_id,$this->id]);
        return true;
    }
}
