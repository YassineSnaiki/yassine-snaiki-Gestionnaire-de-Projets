<?php

namespace app\controllers;

use app\core\Controller;
use app\models\Role;
use app\models\Permission;

class AdminController extends Controller {

    public function __construct() {
        if (!isset($_SESSION['admin'])) {
            header('Location: /login');
            exit;
        }
    }
    public function adminDashboard() {
        $allRoles = Role::getAll();
        $allPermissions = Permission::getAll();
        return $this->render('admin-dashboard', [
            'allRoles' => $allRoles,
            'allPermissions' => $allPermissions,
        ]);
    }
}
