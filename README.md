# ECMS - Education Consultancy  Management System

A web-based application for managing educational counseling interactions between administrators, counselors, and students.

## Tech Stack

| Layer | Technology |
|-------|------------|
| Backend | PHP |
| Database | MySQL (PDO) |
| Server | Apache (XAMPP) |
| Frontend | HTML/CSS/JavaScript |
| Architecture | Custom MVC |
| Version Control | Git |

## Project Structure

```
ECMS(rejish)/
├── index.php                    # Application entry point
├── .htaccess                    # URL rewriting rules
├── backend/
│   ├── config/
│   │   ├── constants.php        # App constants
│   │   └── database.php         # Database connection (Singleton)
│   ├── controllers/             # MVC Controllers
│   ├── core/                    # Core framework classes
│   ├── libraries/               # Helper libraries
│   ├── models/                  # Database models
│   ├── sql/
│   │   └── schema.sql           # Database schema
│   └── views/
│       ├── admin/               # Admin panel views
│       ├── auth/                # Login/Register views
│       ├── counselor/           # Counselor views
│       ├── layouts/             # Shared templates
│       └── student/             # Student views
├── frontend/
│   └── assets/
│       ├── css/                 # Stylesheets
│       ├── images/              # Image assets
│       └── js/                  # Client-side scripts
└── public/
    ├── documents/               # Uploaded documents
    └── profiles/                # Profile pictures
```

## Features

### User Roles
- **Admin** - System management, user administration
- **Counselor** - Student management, session tracking
- **Student** - View sessions, access documents, profile management

### Core Functionality
- Role-based authentication system
- Session management
- File upload system (documents & profile pictures)
- MVC architecture for clean code organization

## Prerequisites

- XAMPP (Apache + MySQL + PHP)
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Web browser

## Installation

### 1. Setup XAMPP
- Install XAMPP on your system
- Start Apache and MySQL services

### 2. Clone Repository
```bash
cd /Applications/XAMPP/xamppfiles/htdocs/
git clone <repository-url> "ECMS(rejish)"
```

### 3. Database Setup
1. Open phpMyAdmin: `http://localhost/phpmyadmin`
2. Import the schema:
   - Navigate to `backend/sql/schema.sql`
   - Import via phpMyAdmin or MySQL CLI

### 4. Configuration
Copy `.env.example` to `.env` and update if needed:
```
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ecmss
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Access Application
```
http://localhost/ECMS(rejish)/
```

## Configuration

### Database Settings
| Setting | Value |
|---------|-------|
| Host | 127.0.0.1 |
| Database | ecmss |
| Username | root |
| Password | (empty) |
| Charset | utf8mb4 |

### Environment Variables
| Variable | Description |
|----------|-------------|
| `DB_HOST` | Database host |
| `DB_PORT` | Database port |
| `DB_DATABASE` | Database name |
| `DB_USERNAME` | Database username |
| `DB_PASSWORD` | Database password |
| `APP_SECRET` | Secret key for remember-me tokens (change in production) |

## Development

### Creating a Controller
```php
// backend/controllers/ExampleController.php
class ExampleController {
    public function index() {
        // Load view
        require VIEW_PATH . '/example/index.php';
    }
}
```

### Creating a Model
```php
// backend/models/ExampleModel.php
class ExampleModel {
    private $db;

    public function __construct() {
        $this->db = getDB();
    }
}
```

### Database Connection
```php
// Get database instance
$db = getDB();

// Or use singleton
$db = Database::getInstance()->getConnection();
```

## File Upload

Uploaded files are stored in:
- Documents: `uploads/documents/`

## License

This project is for educational purposes.

## Author

Rejish Khanal
