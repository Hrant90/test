# Test Project

This repository contains a sample task management application built with PHP and JavaScript, implementing features such as task list retrieval, task details viewing, search functionality, pagination, and an API for task management.

## Features

- **Task List Display**: Shows a paginated list of tasks.
- **Task Details**: View detailed information of tasks in a popup by clicking on a specific task.
- **Search by Title**: Allows users to search tasks by title.
- **Pagination Support**: Supports pagination for task lists to improve performance.
- **API Endpoints**: Provides API methods for retrieving task lists and task details.
- **Local Storage**: Task details are cached using cookies with a 1-hour TTL (Time to Live) to reduce redundant API calls.
- **MVC Pattern**: The project is structured around the Model-View-Controller (MVC) design pattern.
- **Dynamic Routing**: Handles dynamic routes with task IDs.
- **SOLID Principles**: Code is written following SOLID principles and uses Dependency Injection for database and repository management.

## Technologies Used

- **PHP**: Version 8.3
- **JavaScript**
- **MySQL**: Database with PDO for secure interactions.
- **HTML/CSS**: Frontend structure and styling.
- **JSON API**: RESTful API responses.

## Installation

### Prerequisites

- PHP 8.3 or later
- MySQL
- Composer

### Setup Instructions

1. **Clone the repository**
2. **Use PhpStorm or web server to redirect all calls to /public/index.php**
3. **Run composer install**
4. **Open /public/view/index.html in browser**
5. **Add DB credentials to .env file**
6. **Create tasks table in DB using sql script located in /scripts/script.sql**
7. **Generate tasks using /scripts/GenerateTasks.php**

