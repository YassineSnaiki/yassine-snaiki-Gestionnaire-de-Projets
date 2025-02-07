# Kando Project Management Platform

A robust project management system built with PHP 8, focusing on team collaboration and efficient project tracking.

## 🚀 Features

### For Project Managers
- **Project Management**
  - Create, update, and delete projects
  - Track project progress
  - Manage team members

- **Task Management**
  - Create and assign tasks
  - Track task status
  - Set priorities and deadlines

- **User Management**
  - Role-based access control
  - Permission management
  - Team member administration

### For Team Members
- **Task Handling**
  - View assigned tasks
  - Update task status
  - Track personal progress

- **Authentication**
  - Secure login system
  - Profile management
  - Password recovery

### For Guests
- View public projects
- Register for an account

## 🛠️ Technologies Used

- **Backend**
  - PHP 8 (Object-Oriented)
  - PDO for database operations
  - MVC Architecture

- **Frontend**
  - HTML5/CSS3
  - TailwindCSS
  - JavaScript

- **Security**
  - XSS Protection
  - CSRF Protection
  - SQL Injection Prevention
  - Input Validation

## 📋 Prerequisites

- PHP 8.0 or higher
- Postgres 14.0 or higher
- Composer
- Node.js and npm

## 🔧 Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/yourusername/project-management-platform.git
   ```

2. Install PHP dependencies:
   ```bash
   composer install
   ```

3. Install frontend dependencies:
   ```bash
   npm install
   ```

4. Configure your database in `config/database.php`

5. Run the application:
   ```bash
   php -S localhost:8000 -t public
   ```

## 📊 Database Structure

The project uses PostgreSQL with the following key features:


### Core Features
- Singleton pattern for database connection
- PDO for secure database operations
- Automatic database and tables creation
- UUID for primary keys
- Proper indexing for performance optimization

### Database Schema
The database is automatically created with the following structure:

### Default Admin Account
When the database is first created, a default admin account is automatically generated with the following credentials:
- **Email**: admin@gmail.com
- **Password**: admin
- **Role**: Administrator (Full system access)


### Database Tables
1. **Users Table**
   - UUID primary key
   - firstname, lastname
   - email (unique)
   - password (hashed)
   - isadmin flag
   - created_at timestamp

2. **Projects Table**
   - UUID primary key
   - title, description
   - user_id (foreign key to users)
   - created_at timestamp

3. **Tasks Table**
   - UUID primary key
   - title, description
   - project_id (foreign key to projects)
   - status (todo, doing, review, done)
   - tag (basic, feature, bug)
   - created_at timestamp

4. **Assignments Table** (Many-to-Many: Users-Tasks)
   - user_id (foreign key to users)
   - task_id (foreign key to tasks)
   - Composite primary key (user_id, task_id)

5. **Roles Table**
   - name as primary key (read, write, manage)

6. **Permissions Table**
   - name as primary key
   - Predefined permissions: create task, delete task, assign task, change tag, manage contributors, delete project

7. **Roles_Permissions Table** (Many-to-Many: Roles-Permissions)
   - role_name (foreign key to roles)
   - permission_name (foreign key to permissions)
   - Composite primary key (role_name, permission_name)

8. **Contributions Table** (Project-User-Role relationship)
   - user_id (foreign key to users)
   - project_id (foreign key to projects)
   - role_name (foreign key to roles)
   - Composite primary key (user_id, project_id)

### Performance Optimizations
- Indexes on frequently queried columns:
  - projects(user_id)
  - tasks(project_id)
  - tasks(status)

### Security Features
- Password hashing
- Prepared statements
- Foreign key constraints
- Input validation through CHECK constraints

## 🔒 Security Features

- Prepared statements for database queries
- Input sanitization
- Session management
- Password hashing
- Role-based access control


## 📧 Contact

Yassine Snaiki - yassnaiki@gmail.com

Project Link: [https://github.com/yourusername/project-management-platform](https://github.com/yourusername/project-management-platform)
