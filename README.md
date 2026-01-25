# Ghaib Network - MikroTik Management System

## Refactored to Clean Architecture (MVC)

This application has been refactored from a monolithic structure to a clean, maintainable MVC (Model-View-Controller) architecture.

## Architecture Overview

```
gnet/
├── app/
│   ├── Controllers/     # Request handlers
│   ├── Models/          # Database interactions
│   ├── Services/        # Business logic
│   └── Views/           # HTML templates
├── config/              # Configuration files
├── public/              # Web-accessible directory
│   └── index.php        # Entry point
├── bootstrap/           # Application initialization
└── package/             # Third-party packages
```

## Requirements

- PHP 7.4 or higher
- MySQL/MariaDB
- Apache with mod_rewrite (or Nginx with URL rewriting)
- MikroTik RouterOS device

## Installation

### 1. Clone/Download

Place the `gnet` directory in your web server's document root.

### 2. Configure Database

Edit `config/database.php`:

```php
return [
    'host' => 'localhost',
    'port' => 3306,
    'database' => 'ghaibnet_mikrobill',
    'username' => 'root',
    'password' => '',
];
```

### 3. Initialize Database

Run the installation script to create tables:

```
http://your-domain/install.php
```

Or import the schema manually:

```bash
mysql -u root -p ghaibnet_mikrobill < db_schema/schema.sql
```

### 4. Configure Web Server

#### Apache

The `.htaccess` file in `public/` should handle URL rewriting automatically.

Make sure `mod_rewrite` is enabled:

```bash
sudo a2enmod rewrite
sudo systemctl restart apache2
```

#### PHP Built-in Server (Development Only)

```bash
cd gnet
php -S localhost:8000 -t public
```

Access the application at: `http://localhost:8000`

### 5. Configure MikroTik

- Log in to the application
- Go to Settings
- Add your MikroTik device credentials
- Set it as active

## URL Structure

### Web Pages

- `/` or `/dashboard` - Dashboard with user search
- `/login` - Login page
- `/logout` - Logout
- `/ppp/active` - Active PPP users
- `/ppp/non-active` - Inactive PPP users
- `/settings` - MikroTik settings

### API Endpoints (JSON)

All API endpoints are under `/api` and accept POST requests:

- `/api/ppp/active` - Get all active users
- `/api/ppp/non-active` - Get inactive users
- `/api/ppp/search` - Search users
- `/api/ppp/disconnect` - Disconnect a user
- `/api/ppp/disconnect-multiple` - Disconnect multiple users
- `/api/mikrotik/test-connection` - Test MikroTik connection
- `/api/mikrotik/interfaces` - Get interface statistics
- `/api/mikrotik/logs` - Get system logs
- `/api/mikrotik/ping` - Ping an address

## Key Improvements

### Before (Monolithic)
- ❌ 1400+ lines per file
- ❌ Mixed HTML, PHP, and SQL
- ❌ Duplicate code everywhere
- ❌ Hard to maintain
- ❌ No separation of concerns

### After (Clean Architecture)
- ✅ 200-400 lines per file
- ✅ Separated layers (Model, View, Controller, Service)
- ✅ Reusable components
- ✅ Easy to maintain and debug
- ✅ Clear project structure

## File Size Comparison

| File | Before | After |
|------|--------|-------|
| `dashboard.php` | 1422 lines | ~300 lines (View only) |
| `ppp_active.php` | 1410 lines | ~350 lines (View only) |
| `api_ppp.php` | 398 lines | Integrated into `PppController` |

Logic is now properly separated into:
- **Models**: Database queries
- **Services**: Business logic
- **Controllers**: Request handling
- **Views**: Presentation only

## Troubleshooting

### "404 Not Found" errors

Check that URL rewriting is enabled and `.htaccess` is being read.

### "Database connection failed"

Verify database credentials in `config/database.php`.

### "Class not found" errors

Ensure the autoloader in `bootstrap/app.php` is working correctly.

### API endpoints return HTML instead of JSON

Make sure you're accessing routes correctly under `/api/`.

## Development

### Adding New Routes

Edit `config/routes.php`:

```php
$router->get('/new-page', 'NewController@index');
$router->post('/api/new-endpoint', 'NewController@apiMethod');
```

### Adding New Controllers

Create a file in `app/Controllers/`:

```php
<?php
namespace Controllers;

class NewController extends BaseController {
    public function index() {
        $this->view('new/index', ['data' => $someData]);
    }
}
```

### Adding New Models

Create a file in `app/Models/`:

```php
<?php
namespace Models;

class NewModel extends BaseModel {
    protected static $table = 'table_name';
}
```

## License

Copyright © 2026 Ghaib Network. All rights reserved.

## Support

For issues or questions, please contact the development team.
