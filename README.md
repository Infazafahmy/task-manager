# Task Management System  

## Overview  
The Task Management System is a Laravel 10 web application built with Laravel Breeze for authentication. It allows users to create, update, delete, and manage tasks, with dashboards to view tasks grouped by status (Pending, In Progress, Completed). The interactive frontend dashboard is integrated into the Laravel project using Blade and TailwindCSS, accessible via a sidebar link.

---

## Features  
- User registration and login (Laravel Breeze)
- Add, edit, delete, and mark tasks as completed
- Assign tasks to other users and remove assignees
- Postpone tasks with reason and history log
- Introduce priority levels (High, Medium, Low) with visual highlighting
- Comments on tasks in chronological order with history view
- Dashboard grouped by task status
- Search and filter tasks by title, description, status, and priority
- SweetAlert confirmation popups
- Responsive UI with TailwindCSS
- REST API for tasks, comments, assignment, and dashboard with Sanctum token authentication
- API filtering, sorting, and pagination for tasks
- Error handling for task assignment and completion
- Interactive Dashboard:
      - Displays total tasks, Pending, In Progress, Completed counts
      - Highlights high-priority tasks due soon
      - Includes charts (Doughnut for task status, Bar for priority counts)
- Interactive Frontend Dashboard (/dashboard-frontend):
     - Full task management: add, edit, delete, and mark tasks as completed via API
     - Task assignment: assign users to tasks and remove assignees via API
     - Comment system: add comments and view comment history via API
     - Postpone functionality: postpone tasks with new due dates and view postpone history via API
     - Real-time search and filtering by title, description, status, and priority
     - Kanban board for visual task organization by status (Pending, In Progress, Completed)
     - Interactive charts: Doughnut for task status distribution, Bar for priority counts
     - Priority level highlighting for tasks (High, Medium, Low) in task table and Kanban board
---

## Installation Instructions  

### 1. Requirements  
Make sure the following are installed:  
- XAMPP (PHP 8.1+ and MySQL 8.x)  
- Laravel 10.x (PHP framework)  
- Composer 2.x  
- Node.js (v18+) and NPM (v9+)  

Check versions with:  

php -v

php artisan --version

composer -v

node -v

npm -v

---

### 2. Setup Steps

#### 1. Unzip or clone the project into C:\xampp\htdocs (Windows) or your web server root.
Example:

C:\xampp\htdocs\task-manager

#### 2. Configure database in `.env` 
Example configuration (change as needed):  

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=task_manager
DB_USERNAME=root
DB_PASSWORD=
APP_URL=http://localhost:8000
SESSION_DRIVER=file
SANCTUM_STATEFUL_DOMAINS=localhost:8000

#### 3. Change MySQL port (if needed)
This project uses port 3306. Update your .env file:

DB_PORT=3306


#### 4. Start XAMPP services

Start Apache and MySQL in the XAMPP Control Panel.


#### 5. Install PHP dependencies

composer install


#### 6. Install Node.js dependencies

npm install


#### 7. Run migrations

php artisan migrate


#### 8. Run the application (open two terminals):

##### Terminal 1 – Start Laravel server

php artisan serve

##### Terminal 2 – Compile frontend assets

npm run dev

#### 9. Access the application at:
http://127.0.0.1:8000


It shows:

- Total tasks, Pending, In Progress, Completed counts

- High-priority tasks due soon with notifications

- Interactive charts: Doughnut for task status, Bar for priority counts

- Full task management: add, edit, delete, mark as completed

- Task assignment: assign and remove users

- Comment system with comment history

- Postpone functionality with postpone history

- Real-time search and filtering by title, description, status, and priority

- Kanban board for task organization by status

- Priority level highlighting for tasks in table and Kanban board

---

## API Endpoints

The frontend dashboard consumes the following Laravel API endpoints (protected by Sanctum authentication):
- GET /api/tasks: Fetch tasks with filtering (search, status, priority)
- POST /api/tasks: Create a new task
- PUT /api/tasks/{id}: Update a task
- DELETE /api/tasks/{id}: Delete a task
- POST /api/tasks/{id}/complete: Mark a task as completed
- POST /api/tasks/{id}/postpone: Postpone a task with a new due date and reason
- POST /api/tasks/{id}/comments: Add a comment to a task
- POST /api/tasks/{id}/assign: Assign users to a task
- POST /api/tasks/{id}/remove-members: Remove users from a task
- GET /api/dashboard-data: Fetch dashboard statistics (total, pending, in progress, completed, high-priority counts)

Use a tool like Postman to test these endpoints with a Sanctum token. Ensure the Authorization header includes Bearer <token> after authenticating via /sanctum/csrf-cookie and /api/login.


## Deliverables

- Updated Laravel project in GitHub repository
- Integrated frontend dashboard in resources/views/dashboard-frontend.blade.php
- Postman collection for API testing (available in repository or upon request)
- Updated README.md with backend and frontend instructions
- Clear commit history with feature-based commits
- System plan update with API endpoints (listed above)

---

## Author

Developed by: M.F.F. Infaza

Date: 19th September 2025

---





