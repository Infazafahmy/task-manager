# Task Management System - Backend Management

## Overview
The Backend Management Project is a Laravel 10 web application that manages tasks and users directly through backend interfaces. It provides pages for task management, assignment, editing, and dashboards.  

**Note:** Authentication is shared via Laravel Breeze, using the same database as the frontend dashboard project. This project does not consume the API but interacts with the database directly.  

**Important:** If running both frontend and backend on the same machine, you may need to change the Laravel server port (`APP_URL`) and database port (`DB_PORT`) to avoid conflicts.

---

## Features
- Login and registration (Laravel Breeze)
- Dashboard to view tasks by status (Pending, In Progress, Completed)
- Create, edit, delete, and mark tasks as completed
- Assign users to tasks and remove assignees
- Comment system with history
- Postpone tasks with reason and history
- Search and filter tasks
- SweetAlert confirmation popups
- Responsive UI with Blade and TailwindCSS
- File attachments for tasks (PDF, images, docs)
- Multiple attachments per task with download & delete functionality
- File validation (max 5MB, pdf/doc/jpg/png)

---

## Technology Stack
| Component        | Technology / Version |
|-----------------|--------------------|
| Backend          | Laravel 10+        |
| PHP              | 8.1+               |
| Database         | MySQL 8.x          |
| Frontend         | Blade Templates, TailwindCSS 3.x |
| Authentication   | Laravel Breeze     |
| JavaScript       | Alpine.js          |
| Node.js & NPM    | Node.js 18+, NPM 9+ |
| Other Tools      | Composer 2.x, SweetAlert |
| Version Control  | Git / GitHub       |

---

## Installation Instructions
1. Clone or unzip the project into your web server root, e.g., `C:\xampp\htdocs\backend-management`.
2. Configure the `.env` file to connect to the **same database as the frontend**. If the database is using a **different port**, update `DB_PORT` (e.g., `3307`).
3. Start XAMPP (Apache + MySQL). If using a custom port, update `.env` `APP_URL=http://localhost:8000`.
4. Install PHP dependencies: `composer install`
5. Install Node.js dependencies: `npm install`
6. Run migrations (if needed for local tables): `php artisan migrate`
7. Start Laravel server on port 8000: `php artisan serve --port=8000`
8. Compile frontend assets: `npm run dev`
9. Access the backend at `http://127.0.0.1:8000`

---

## Deliverables
- Fully functional Backend Management project
- Backend pages for task management, user assignment, and editing
- Integration with the same database as frontend project
- README.md with installation and setup instructions

---

## Author
Developed by: M.F.F. Infaza  
Date: 19th September 2025
