# Task Management System  

## Overview  
The Task Management System is a **Laravel 10** web application built with **Laravel Breeze** for authentication.  
It allows users to create, update, delete, and manage tasks, with a dashboard to view tasks grouped by status (Pending, In Progress, Completed).  

---

## Features  
- User registration and login (Laravel Breeze)  
- Add, edit, delete, and mark tasks as completed  
- Assign tasks to other users  
- Postpone tasks with reason and history log  
- Dashboard grouped by task status  
- Search and filter tasks  
- SweetAlert confirmation popups  
- Responsive UI with TailwindCSS  

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

npm -v. 


---

2. Setup Steps

1. Unzip or clone the project into C:\xampp\htdocs (Windows) or your web server root.
Example:

C:\xampp\htdocs\task-manager


2. Start XAMPP services

Start Apache and MySQL in the XAMPP Control Panel.



3. Install PHP dependencies

composer install


4. Install Node.js dependencies

npm install


5. Run migrations

php artisan migrate


6. Run the application (open two terminals):

Terminal 1 – Start Laravel server

php artisan serve

Terminal 2 – Compile frontend assets

npm run dev



7. Access the application at:
http://127.0.0.1:8000


---

Author

Developed by: M.F.F. Infaza
Date: 6th September 2025



