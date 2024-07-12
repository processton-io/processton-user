# Processton User

[![Latest Version on Packagist](https://img.shields.io/packagist/v/processton-io/processton-user.svg?style=flat-square)](https://packagist.org/packages/processton-io/processton-user)
[![Total Downloads](https://img.shields.io/packagist/dt/processton-io/processton-user.svg?style=flat-square)](https://packagist.org/packages/processton-io/processton-user)
![GitHub Actions](https://github.com/processton-io/processton-user/actions/workflows/main.yml/badge.svg)

This is a module for processton-setup provides user management feature. This module provides following functionalities to the processton setup.

-   Add User/Role
-   Users/Roles Listing
-   Allow/Block User
-   Role Permissions


## Installation

You can install the package via composer:

```bash
composer require processton-io/processton-user
```

## Usage

After installation publish configurations and resolvers.

```bash
php artisan vendor:publish
```

Run migrations

```bash
php artisan migrate
```

Extend you user model using Trait

```php
use Processton\ProcesstonUser\Models\Trait\ProcesstonUser;
```
```php
class User {
    use ProcesstonUser;
}
```

above script will add three functions to your User model

```php
$request->user()->role;
//Return role assigned to the user

$request->user()->permissions;
//Return array of permissions assign to user role.

$request->user()->havePermission($permission, $createNew = true);
//Check does user have specified permission or not. pass permission key as argument. If second argument is true checked permission will automatically get mapped from configuration file and database record will be created.
```

This package follow standard Processton App module functionality. module config file have 

* base_url (users)
-   menu_items
    *   Users
    *   Roles
-   interactions
    *   Users
    *   Roles
-   charts
    *   total_users
    *   new_users
    *   total_sessions ! not working yet
    *   pending_validation
    *   avg_sessions_duration !not working yet
-   resolvers
    *   user-invitation
    *   user-block
    *   user-un-block
    *   reset-password-email
-   permission_mappings
    *   admin.setup.users
    *   admin.setup.users.block
    *   admin.setup.users.unblock
    *   admin.setup.users.resetpassword
    *   admin.setup.roles
    *   admin.setup.roles.edit
    *   admin.setup.roles.permissions

You can publish and alter them as per your needs.

This module uses three Primary models
-   User
-   Role
-   Permission

### Testing

testing is not available for this module yet.

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please use issue tracker.

## Credits

-   [Ahmad Faryab Kokab](https://github.com/afaryab)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.