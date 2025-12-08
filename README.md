# Filament Starter Kit

[![Pint](https://github.com/sumardi/filament-starter-kit/actions/workflows/pint.yml/badge.svg)](https://github.com/sumardi/filament-starter-kit/actions/workflows/pint.yml)
[![PEST](https://github.com/sumardi/filament-starter-kit/actions/workflows/pest.yml/badge.svg)](https://github.com/sumardi/filament-starter-kit/actions/workflows/pest.yml)
[![PHPStan](https://github.com/sumardi/filament-starter-kit/actions/workflows/phpstan.yml/badge.svg)](https://github.com/sumardi/filament-starter-kit/actions/workflows/phpstan.yml)
[![PHPStan](https://github.com/sumardi/filament-starter-kit/actions/workflows/rector.yml/badge.svg)](https://github.com/sumardi/filament-starter-kit/actions/workflows/rector.yml)

Kickstart your project with pre-configured Filament admin panel. Only essential development tools are included.

> [!NOTE]
> Requires **PHP 8.4** or higher.

## What's Included

### Core Dependencies
- **Laravel 12.x** - The PHP framework
- **FilamentPHP 4.x** - Admin panel with SPA mode, custom theme, and MFA enabled
- **nunomaduro/essentials** - Better Laravel defaults (strict models, auto-eager loading, immutable dates)

### Development Tools
- **laravel/sail** - Docker-based development environment
- **larastan/larastan** - Static analysis
- **laravel/pint** - Code style fixer
- **pestphp/pest** - Testing framework
- **rector/rector** - Automated refactoring
- **vemcogroup/laravel-translation** - Scan your app for translations and create your *.json file

## Quick Start

If you have installed PHP and Composer, you may create a new project using the Composer `create-project` command:

```bash
composer create-project sumardi/filament-starter-kit your-project-name
cd your-project-name 
composer install
npm install
npm run build
php artisan serve
```

Or, you may create a new project using the Laravel Installer:

```bash
composer global require laravel/installer
laravel new your-project-name --using=sumardi/filament-starter-kit
cd your-project-name
php artisan serve
```