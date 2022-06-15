
# Laravel User Management API
Laravel package for user management. Includes user, user profile and user role.

## Table of Contents

[Getting Started](#getting-started)<br>
[Prerequisite(s)](#prerequisites)<br>
[Installation](#installation)<br>
[How to Use](#how-to-use)<br>
[Download Postman API](#download-postman-api)<br>
[Authenticator](#authenticator)<br>
[Login API](#login-api)<br>
[Register API](#register-api)<br>
[Logout API](#logout-api)<br>
[Change Password API](#change-password-api)<br>
[Forgot Password (Send Request) API](#forgot-password-request-api)<br>
[Forgot Password (Reset) API](#forgot-password-reset-api)<br>
[Support](#support)

<a name="getting-started"></a>
## Getting Started
Below are the steps in order to integrate authenticator API to your Laravel project.

<a name="prerequisites"></a>
## Prerequisite(s)
### Laravel Sanctum

Install Laravel Sanctum via Composer:

```bash
composer require laravel/sanctum
```

Uncomment ***\Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class*** located in the ***app/Http/Kernel.php*** file

```bash
'api' => [
  \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
  'throttle:api',
  \Illuminate\Routing\Middleware\SubstituteBindings::class,
]
```

Add the following lines of code to the ***$routeMiddleware*** variable in the ***app/Http/Kernel.php*** file

```bash
'abilities' => \Laravel\Sanctum\Http\Middleware\CheckAbilities::class,
'ability' => \Laravel\Sanctum\Http\Middleware\CheckForAnyAbility::class
```

<a name="installation"></a>
## Installation
Install the package using composer:

```bash
composer require jscustom/laravel-authenticator
```

Export the configuration file:

```bash
php artisan vendor:publish --provider="JSCustom\LaravelAuthenticator\Providers\LaravelAuthenticatorServiceProvider" --tag="config"
```

Export the migration files:

```bash
php artisan vendor:publish --provider="JSCustom\LaravelAuthenticator\Providers\LaravelAuthenticatorServiceProvider" --tag="migrations"
```

Do a quick migration:

```bash
php artisan migrate --path=/database/migrations/laravel-authenticator
```

<a name="how-to-use"></a>
## How To Use

<a name="download-postman-api"></a>
### Download Postman API

Download the Postman API Collection [here](https://minhaskamal.github.io/DownGit/#/home?url=https://github.com/JSCustom/laravel-authenticator/blob/master/src/assets/postman/Laravel_User_Management.postman_collection.json).

<a name="authenticator"></a>
### Authenticator

**Features**

- Login
- Register
- Logout
- Change Password
- Forgot Password (Send Request)
- Forgot Password (Reset)

**Models**

```bash
JSCustom\LaravelAuthenticator\Models\User
JSCustom\LaravelAuthenticator\Models\UserProfile
JSCustom\LaravelAuthenticator\Models\UserRole
JSCustom\LaravelAuthenticator\Models\PasswordReset
```

<a name="login-api"></a>
### Login API

**Controller**

```bash
\JSCustom\LaravelAuthenticator\Http\Controllers\Authenticator\AuthenticatorController
```

**URL**

```bash
{{url}}/api/auth/login
```

**Form Data**

```bash
{
  "username": "stevengrant",
  "email": "stevengrant@mail.com",
  "password": "yourpasswordhere",
}
```

**Method**

```bash
POST
```

**Headers**

```bash
{
  "Accept": "application/json"
}
```

**Response**

```bash
{
  "status": true,
  "message": "Welcome, Steven!",
  "payload": {
    "user": {
      "id": 1,
      "username": "stevengrant",
      "email": "stevengrant@mail.com",
      "status": 1,
      "role_id": 2,
      "email_verified_at": null,
      "created_at": "2022-06-14T23:27:07.000000Z",
      "updated_at": "2022-06-15T14:14:18.000000Z",
      "user_profile": {
        "id": 1,
        "user_id": 1,
        "first_name": "Steven",
        "last_name": "Grant",
        "created_at": "2022-06-14T23:24:42.000000Z",
        "updated_at": "2022-06-14T23:24:42.000000Z"
      },
      "user_role": {
        "id": 2,
        "role": "Regular User",
        "description": "Regular User description here",
        "created_at": "2022-06-14T23:24:42.000000Z",
        "updated_at": "2022-06-14T23:24:42.000000Z"
      }
    },
    "access_token": "4|SvOITBX0p79AujrZQFse75TJJgONp3kYuid7Q0uP"
  }
}
```

<a name="register-api"></a>
### Register API

**Controller**

```bash
\JSCustom\LaravelAuthenticator\Http\Controllers\Authenticator\AuthenticatorController
```

**URL**

```bash
{{url}}/api/auth/register
```

**Form Data**

```bash
{
  "username": "markanthony",
  "email": "markanthony@mail.com",
  "status": 1,
  "role_id": 1,
  "first_name": "Mark",
  "last_name": "Anthony",
  "password": "yourpasswordhere"
}
```

**Method**

```bash
POST
```

**Headers**

```bash
{
  "Accept": "application/json"
}
```

**Response**

```bash
{
  "status": true,
  "message": "Register success.",
  "payload": {
    "user": {
      "username": "markanthony",
      "email": "markanthony@mail.com",
      "status": 1,
      "role_id": 1,
      "updated_at": "2022-06-15T14:33:56.000000Z",
      "created_at": "2022-06-15T14:33:56.000000Z",
      "id": 8,
      "password_unhashed": "yourpasswordhere",
      "user_role": {
        "id": 1,
        "role": "Regular User",
        "description": "Regular User description here",
        "created_at": "2022-06-14T23:24:42.000000Z",
        "updated_at": "2022-06-14T23:24:42.000000Z"
      },
      "user_profile": {
        "id": 4,
        "user_id": 8,
        "first_name": "Mark",
        "last_name": "Anthony",
        "created_at": "2022-06-15T14:33:56.000000Z",
        "updated_at": "2022-06-15T14:33:56.000000Z"
      }
    }
  }
}
```

<a name="logout-api"></a>
### Logout API

**Controller**

```bash
\JSCustom\LaravelAuthenticator\Http\Controllers\Authenticator\AuthenticatorController
```

**URL**

```bash
{{url}}/api/auth/logout
```

**Method**

```bash
POST
```

**Headers**

```bash
{
  "Authorization": "Bearer ...",
  "Accept": "application/json"
}
```

**Response**

```bash
{
  "status": true,
  "message": "Logout successful."
}
```

<a name="change-password-api"></a>
### Change Password API

**Controller**

```bash
\JSCustom\LaravelAuthenticator\Http\Controllers\Authenticator\AuthenticatorController
```

**URL**

```bash
{{url}}/api/auth/change-password
```

**Parameters**

```bash
{
  "current_password": "yourcurrentpassword",
  "new_password": "yournewpassword",
  "new_password_confirmation": "yournewpassword"
}
```

**Method**

```bash
POST
```

**Headers**

```bash
{
  "Authorization": "Bearer ...",
  "Accept": "application/json"
}
```

**Response**

```bash
{
  "status": true,
  "message": "Password updated successfully."
}
```

<a name="forgot-password-request-api"></a>
### Forgot Password (Send Request) API

**Controller**

```bash
\JSCustom\LaravelAuthenticator\Http\Controllers\Authenticator\AuthenticatorController
```

**URL**

```bash
{{url}}/api/auth/forgot-password
```

**Parameters**

```bash
{
  "email": "markanthony@mail.com"
}
```

**Method**

```bash
POST
```

**Headers**

```bash
{
  "Accept": "application/json"
}
```

**Response**

```bash
{
  "status": true,
  "message": "A forgot password request was sent to your email.",
  "payload": {
    "reset_password_token": "$2y$10$ELeKX3zUOBMQJSmuFdaAwOf7id7NNuxXwydfwL/.sIUdEZ35gwi7y"
  }
}
```

<a name="forgot-password-reset-api"></a>
### Forgot Password (Reset) API

**Controller**

```bash
\JSCustom\LaravelAuthenticator\Http\Controllers\Authenticator\AuthenticatorController
```

**URL**

```bash
{{url}}/api/auth/reset-password
```

**Parameters**

```bash
{
  "new_password": "yournewpassword",
  "new_password_confirmation": "yournewpassword",
  "reset_password_token": "$2y$10$IqsuRxB5ugH/aui3PCNzPeWBBKilUc3Cgy1K/F48NkgJ/O4kbO0bC"
}
```

**Method**

```bash
POST
```

**Headers**

```bash
{
  "Accept": "application/json"
}
```

**Response**

```bash
{
  "status": true,
  "message": "Password reset successfully."
}
```

<a name="support"></a>
### Support
For support, email developer.jeddsaliba@gmail.com.