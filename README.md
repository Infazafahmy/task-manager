# Task Management System  

## Overview  
The Task Management System is a **Laravel 10** web application built with **Laravel Breeze** for authentication.  
It allows users to create, update, delete, and manage tasks, with a dashboard to view tasks grouped by status (**Pending, In Progress, Completed**).  

---

## Features  
- User registration and login (Laravel Breeze)  
- Add, edit, delete, and mark tasks as completed  
- Assign tasks to other users  
- Postpone tasks with reason and history log  
- Introduce priority levels (High, Medium, Low)  
- Comments on tasks in chronological order  
- Dashboard grouped by task status  
- Search and filter tasks  
- SweetAlert confirmation popups  
- Responsive UI with TailwindCSS
- REST API for tasks, comments, assignment, and dashboard with Sanctum token authentication


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


---

## Deliverables

Updated Laravel project in GitHub repository

Clear commit history with feature-based commits

Files added from GitHub, ready for local setup


---

## Author

Developed by: M.F.F. Infaza
Date: 6th September 2025

---


