# Tempo – Workforce Management System

## Project Overview

Tempo is a modern Workforce Management System designed to make managing employees and workplace operations easier.

The system allows administrators to manage employee records, add, edit, and delete employees, and review and manage leave requests submitted by employees.

Employees will be able to access their personal information, view payslips, check their current hours and salary, and submit leave requests when they need time off.

Tempo is designed to make employee management more organized and accessible for both administrators and employees.

## Features

### Administrator
- Add, edit, and delete employee records.
- Manage employee information.
- Review employee leave requests.
- Approve or deny leave requests.
- Access administrative features and employee records.

### Employee
- View personal employee information.
- View payslips.
- Check current working hours and salary.
- Submit leave requests.
- Track leave request status.

## Technologies Used

- PHP
- Laravel
- MySQL
- HTML
- CSS
- JavaScript
- XAMPP
- Git / GitHub
- Postman

## Installation

### Requirements

- PHP
- Composer
- MySQL
- XAMPP
- Node.js and NPM (if required for frontend assets)
- Postman

### Setup Instructions

1. Clone the repository:

```bash
git clone https://github.com/0wpe/CSET-220-Project-Semester-3-Final.git
```

2. Navigate into the project directory:

```bash
cd CSET-220-Project-Semester-3-Final
```

3. Install the required dependencies:

```bash
composer install
```

4. Create your environment file:

```bash
cp .env.example .env
```

5. Generate your application key:

```bash
php artisan key:generate
```

6. Configure your database settings inside the `.env` file.

7. Run the database migrations:

```bash
php artisan migrate
```

8. Start the Laravel development server:

```bash
php artisan serve
```

9. Open the application in your browser:

```text
http://127.0.0.1:8000
```

Make sure your XAMPP MySQL service is running and your database has been created before starting the application.


## Contributing

This project was developed as part of a semester project. Contributions and suggestions are welcome.

## Team Member

Crosson Devon
Sayenga Owen
Grocki Jackson
Lucero Marco

## License

Distributed under the MIT License.