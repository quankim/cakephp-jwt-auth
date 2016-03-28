# cakephp-jwt-auth
Clone and custom cakephp 3 jwt auth from https://github.com/ADmad/cakephp-jwt-auth
Thanks for @ADmad

# QuanKim/JwtAuth custom plugin for CakePHP
[![Build Status](https://img.shields.io/travis/QuanKim/cakephp-jwt-auth/master.svg?style=flat-square)](https://travis-ci.org/QuanKim/cakephp-jwt-auth)
[![Coverage](https://img.shields.io/codecov/c/github/QuanKim/cakephp-jwt-auth.svg?style=flat-square)](https://codecov.io/github/QuanKim/cakephp-jwt-auth)
[![Total Downloads](https://img.shields.io/packagist/dt/QuanKim/cakephp-jwt-auth.svg?style=flat-square)](https://packagist.org/packages/QuanKim/cakephp-jwt-auth)
[![License](https://img.shields.io/badge/license-MIT-blue.svg?style=flat-square)](LICENSE.txt)
## Installation

You can install this plugin into your CakePHP application using [composer](http://getcomposer.org).

The recommended way to install composer packages is:

```
composer require quankim/cakephp-jwt-auth
```

## Usage

In your app's `config/bootstrap.php` add:

```php
// In config/bootstrap.php
Plugin::load('QuanKim/JwtAuth');
```

or using cake's console:

```sh
./bin/cake plugin load QuanKim/JwtAuth
```
Migrate AuthToken table:
```sh
./bin/cake migrations migrate -p QuanKim/JwtAuth
```
## Configuration:

Setup `AuthComponent`:

```php
    // In your controller, for e.g. src/Api/AppController.php
    public function initialize()
    {
        parent::initialize();

        $this->loadComponent('Auth', [
            'storage' => 'Memory',
            'authenticate', [
                'QuanKim/JwtAuth.Jwt' => [
                    'userModel' => 'Users',
                    'fields' => [
                        'username' => 'id'
                    ],

                    'parameter' => 'token',

                    // Boolean indicating whether the "sub" claim of JWT payload
                    // should be used to query the Users model and get user info.
                    // If set to `false` JWT's payload is directly returned.
                    'queryDatasource' => true,
                ]
            ],

            'unauthorizedRedirect' => false,
            'checkAuthIn' => 'Controller.initialize',

            // If you don't have a login action in your application set
            // 'loginAction' to false to prevent getting a MissingRouteException.
            'loginAction' => false
        ]);
    }
```

Setup `Config/app.php`
Add in bottom of file:
```php
'AuthToken'=>[
        'expire'=>3600
    ]
```
## Working

The authentication class checks for the token in two locations:

- `HTTP_AUTHORIZATION` environment variable:

  It first checks if token is passed using `Authorization` request header.
  The value should be of form `Bearer <token>`. The `Authorization` header name
  and token prefix `Bearer` can be customzied using options `header` and `prefix`
  respectively.

  **Note:** Some servers don't populate `$_SERVER['HTTP_AUTHORIZATION']` when
  `Authorization` header is set. So it's upto you to ensure that either
  `$_SERVER['HTTP_AUTHORIZATION']` or `$_ENV['HTTP_AUTHORIZATION']` is set.

  For e.g. for apache you could use the following:

  ```
  RewriteEngine On
  RewriteCond %{HTTP:Authorization} ^(.*)
  RewriteRule .* - [e=HTTP_AUTHORIZATION:%1]
  ```

- The query string variable specified using `parameter` config:

  Next it checks if the token is present in query string. The default variable
  name is `token` and can be customzied by using the `parameter` config shown
  above.

## Token Generation

You can use `\Firebase\JWT\JWT::encode()` of the [firebase/php-jwt](https://github.com/firebase/php-jwt)
lib, which this plugin depends on, to generate tokens.

**The payload should have the "sub" (subject) claim whos value is used to query the
Users model and find record matching the "id" field.**

Example:
```php
$access_token = JWT::encode([
                'sub' => $user['id'],
                'exp' =>  time() + $expire
            ],Security::salt());
$refresh_token = JWT::encode([
                'sub' => $user['id'],
                'ref'=>time()
            ],Security::salt());
```
You can set the `queryDatasource` option to `false` to directly return the token's
payload as user info without querying datasource for matching user record.

## Further reading

For an end to end usage example check out [this](http://www.bravo-kernel.com/2015/04/how-to-add-jwt-authentication-to-a-cakephp-3-rest-api/) blog post by Bravo Kernel.
