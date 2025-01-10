<?php

namespace app\core;

class Database {
    private \PDO $pdo;
    private $stmt;
    private static $instance = null;

    public function __construct() {
        self::$instance = $this;
        $config = require_once Application::$ROOT_DIR.'/config/database.php'; 
        $dsn = "pgsql:host={$config['host']};port={$config['port']};dbname={$config['dbname']}";
        
        try {
            $this->pdo = new \PDO($dsn, $config['user'], $config['password'], [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
            ]);
        } catch (\PDOException $e) {
            // If database doesn't exist, create it
            if (strpos($e->getMessage() , 'does not exist')!==false) {
                $this->createDatabase($config);
            } else {
                throw $e;
            }
        }
        $this->createTables();
    }

    private function createDatabase($config) {
        $dsn = "pgsql:host={$config['host']};port={$config['port']}";
        $pdo = new \PDO($dsn, $config['user'], $config['password']);
        $pdo->exec("CREATE DATABASE {$config['dbname']}");
        
        // Reconnect to the newly created database
        $this->pdo = new \PDO($dsn.";dbname={$config['dbname']}", $config['user'], $config['password'], [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
        ]);
    }

    private function createTables() {
        // Create users table if not exists
        $this->pdo->exec('
            CREATE EXTENSION IF NOT EXISTS "uuid-ossp";
        ');
        $this->pdo->exec("
            CREATE TABLE IF NOT EXISTS users (
                id UUID DEFAULT uuid_generate_v4() PRIMARY KEY,
                firstname VARCHAR(255) NOT NULL,
                lastname VARCHAR(255) NOT NULL,
                email VARCHAR(255) UNIQUE NOT NULL,
                password VARCHAR(255) NOT NULL,
                isadmin BOOLEAN DEFAULT FALSE,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        ");

        // Create projects table if not exists
        $this->pdo->exec("
            CREATE TABLE IF NOT EXISTS projects (
                id UUID DEFAULT uuid_generate_v4() PRIMARY KEY,
                title VARCHAR(255) NOT NULL,
                description TEXT,
                user_id uuid NOT NULL REFERENCES users(id) ON DELETE CASCADE,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        ");

        // Create tasks table if not exists
        $this->pdo->exec("
            CREATE TABLE IF NOT EXISTS tasks (
                id UUID DEFAULT uuid_generate_v4() PRIMARY KEY,
                title VARCHAR(255) NOT NULL,
                description TEXT,
                project_id uuid NOT NULL REFERENCES projects(id) ON DELETE CASCADE,
                status VARCHAR(20) CHECK (status IN ('todo', 'doing', 'review', 'done')) DEFAULT 'todo',
                tag VARCHAR(20) not null check (tag in ('basic','feature','bug')) default 'basic',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        ");
        // Create assignments table if not exists
        $this->pdo->exec("
            CREATE TABLE IF NOT EXISTS assignments (
                user_id UUID NOT NULL REFERENCES users(id) ON DELETE CASCADE,
                task_id UUID NOT NULL REFERENCES tasks(id) ON DELETE CASCADE,
                PRIMARY KEY (user_id, task_id)
            )
        ");
        //roles
        $this->pdo->exec("
            CREATE TABLE IF NOT EXISTS roles (
                name VARCHAR(20) PRIMARY KEY
            );
            INSERT INTO roles (name) VALUES 
            ('read'), ('write'), ('manage')
            ON CONFLICT (name) DO NOTHING;
        ");
        //permissions
        $this->pdo->exec("
            CREATE TABLE IF NOT EXISTS permissions(
                name VARCHAR(20) PRIMARY KEY
            );
            INSERT INTO permissions (name) VALUES
            ('create task'),
            ('delete task'),
            ('assign task'),
            ('change tag'),
            ('manage contributors'),
            ('delete project')
            ON CONFLICT (name) DO NOTHING;
        ");
        //roles_permissions
        $this->pdo->exec("
            CREATE TABLE IF NOT EXISTS roles_permissions (
                role_name VARCHAR(20) NOT NULL REFERENCES roles(name) ON DELETE CASCADE,
                permission_name VARCHAR(20) NOT NULL REFERENCES permissions(name) ON DELETE CASCADE,
                PRIMARY KEY (role_name, permission_name)
            );
            INSERT INTO roles_permissions (role_name, permission_name) VALUES
            ('write', 'create task'),
            ('write', 'delete task')
            ON CONFLICT DO NOTHING;

            INSERT INTO roles_permissions (role_name, permission_name) VALUES

            ('manage', 'create task'),
            ('manage', 'delete task'),
            ('manage', 'assign task'),
            ('manage', 'change tag'),
            ('manage', 'manage contributors')
            ON CONFLICT DO NOTHING;
        ");
        // Insert admin user if users table is empty
        $statement = $this->pdo->query("SELECT COUNT(*) FROM users");
        $count = $statement->fetchColumn();
       
        if ($count === 0) {
            $hashedPassword = password_hash('admin', PASSWORD_DEFAULT);
            $this->pdo->exec("
                INSERT INTO users (firstname, lastname, email, password, isadmin) 
                VALUES ('admin', 'admin', 'admin@gmail.com', '$hashedPassword', TRUE)
            ");
        }
        // contributions
        $this->pdo->exec("
            CREATE TABLE IF NOT EXISTS contributions (
                user_id UUID NOT NULL REFERENCES users(id) ON DELETE CASCADE,
                project_id UUID NOT NULL REFERENCES projects(id) ON DELETE CASCADE,
                role_name VARCHAR(20) NOT NULL REFERENCES roles(name) ON DELETE CASCADE default 'write',
                PRIMARY KEY (user_id, project_id)
            )
        ");
        

        // Create indexes for better performance
        $this->pdo->exec("
            CREATE INDEX IF NOT EXISTS idx_projects_user_id ON projects(user_id);
            CREATE INDEX IF NOT EXISTS idx_tasks_project_id ON tasks(project_id);
            CREATE INDEX IF NOT EXISTS idx_tasks_status ON tasks(status);
        ");
    }
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    public function getConnection() {
        return $this->pdo;
    }
    public function query($query,$params=[]) {
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);
        $this->stmt = $stmt;
        return $this;
    }
    public function getOne(){
        return $this->stmt->fetch();
    }
    public function getAll(){
        return $this->stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
