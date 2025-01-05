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

    public $contributers = [];



    public function __construct($project){
        foreach ($project as $key => $value) {
            $this->$key = $value;
        }
        if(empty($this->id)){
            $this->user_id = $_SESSION['user']['id'];
        }
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
        $contributers = Application::$app->db->query('select u.id,u.email,u.firstname,u.lastname from users u join contributions c on u.id = c.user_id join projects p on c.project_id = p.id where p.id = ?',[$id])->getAll();
       
        $project['contributers'] = [];
        foreach ($contributers as $contributer) {
            $project['contributers'][]= new User($contributer);
        }
        $project['firstname'] = $admin['firstname'];
        $project['lastname'] = $admin['lastname'];
        
        $projectInstance = new self($project);
        return $projectInstance;
    }
    public function addContribution($user_id) {
        Application::$app->db->query('insert into contributions(user_id,project_id) values(?,?)',[$user_id,$this->id]);
        return true;
    }
    public function deleteContribution($user_id) {
        Application::$app->db->query('delete from contributions where user_id = ? and project_id = ?',[$user_id,$this->id]);
        return true;
    }

    public function save() {
        $id = Application::$app->db->query("INSERT INTO projects(title,description,user_id) VALUES(?,?,?) returning id"
    ,[$this->title,$this->description,$this->user_id])->getOne()["id"];
    $this->id = $id;
    return true;
    }
    public function delete() {
        Application::$app->db->query("delete from projects where id = ?",[$this->id]);
        return true;
    }
}
