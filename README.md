# Filament Reports Demo

This is a demo project showcasing the [Filament Reports Plugin](https://filamentphp.com/plugins/eightynine-reports) by Eightynine. The plugin allows you to easily generate elegant reports in your Laravel Filament application.

## Features Demonstrated

- Basic Filament setup with admin panel
- Integration of the Reports plugin
- Two sample reports:
  1. **User Registration Report**: Lists all registered users with filtering capabilities
  2. **Monthly Registrations Report**: Shows user registrations grouped by month with additional metrics

## Installation

1. Clone this repository
2. Run `composer install`
3. Configure your database in `.env`
4. Run `php artisan migrate --seed` to set up the database with sample data
5. Run `php artisan serve` to start the application
6. Visit `http://localhost:8000/admin/login` to access the admin panel

Default login credentials:
- Email: admin@admin.com (or the email you set during the `make:filament-user` command)
- Password: password (the password you set during the `make:filament-user` command)

## Reports Overview

### User Registration Report

This report provides a detailed list of all users registered in the system. It includes:
- User ID
- Name
- Email
- Registration Date

Filtering options:
- Search by name or email
- Filter by date range

### Monthly Registrations Report

This report provides an aggregated view of user registrations by month. It includes:
- Monthly registration counts
- Latest registered users

Filtering options:
- Filter by year
- Filter by date range

## Key Components Used

The reports are built using various components provided by the Filament Reports plugin:
- Header with title, subtitle and generation timestamp
- Body with tables displaying the report data
- Footer with page information
- Filter form for interactive filtering of the report data

## Credits

This demo uses the [Filament Reports Plugin](https://filamentphp.com/plugins/eightynine-reports) by [Eightynine](https://filamentphp.com/plugins/authors/eightynine).
