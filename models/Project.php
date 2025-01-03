<?php

namespace app\models;

use app\core\Application;
use app\helpers\Dump;


class Project {
    public $id = null;
    public string $title;
    public string $description;
    public string $user_id;
    public string $firstname;
    public string $lastname;
    public string $created_at;

    public function __construct($project){
        foreach ($project as $key => $value) {
            $this->$key = $value;
        }
        $this->user_id = $_SESSION['user']['id'];
    }
    public static function findAll() {
        $projects = [];
        $projectsIds = Application::$app->db->query("select id from projects")->getAll();
        foreach ($projectsIds as $projectId) {
            $projects[] = Project::findOne($projectId["id"]);
        }
        $projectInstances = [];
        foreach ($projects as $project) {
            $projectInstances[]= new self($project);
        }
        return $projectInstances;
    }

    public static function findOne($id) {
        $project = Application::$app->db->query("select * from projects where id = ?",[$id])->getOne();
        $admin = Application::$app->db->query("select firstname,lastname from users where id = ?",[$project['user_id']])->getOne();
        $project['firstname'] = $admin['firstname'];
        $project['lastname'] = $admin['lastname'];
        $projectInstance = new self($project);
        return $projectInstance;
    }

    public function save() {
        $id = Application::$app->db->query("INSERT INTO projects(title,description,user_id) VALUES(?,?,?) returning id"
    ,[$this->title,$this->description,$this->user_id])->getOne()["id"];
    $this->id = $id;
    return true;
    }


}
