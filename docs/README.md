# Introduction

Venus is a middleware based framework. The minimum required to use Venus for your project is covered in this guide.

## Requirements
- PHP >= 8.0

# Install
To install and use this framework, just create your project folder, `cd` inside it and run [Composer](https://getcomposer.org).

```
composer require codevia/venus
```

# Configure
The entry point of your PHP application may be `index.php`, or anything else as long as you configure your server accordingly.

Your starter project should look like this :

```
├── composer.json
├── composer.lock
├── index.php
├── router.php
└── src
    ├── Controller
    │   ├── ExampleController.php
```

In order to use your controllers in your router, you should register a namespace in your `src/` directory in the `composer.json` file. [Learn more](https://getcomposer.org/doc/01-basic-usage.md#autoloading).

Your `index.php`, should look like what you can found in the [example](https://github.com/codevia-io/venus/blob/main/examples/common-use-case/example.php).

### Configure your server

All non-asset requests should point to your `index.php`. So you need to configure your server to do so.

#### Apache `.htaccess`

```apache
AddDefaultCharset utf-8
Options -MultiViews
RewriteEngine On

# Redirige les appels API vers le router de l'API
RewriteCond %{REQUEST_URI} ^ [NC]
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^ index.php [QSA,L]
```

#### NGINX `nginx.conf`

```nginx
 server {
 	index index.php;

 	location / {
 		try_files $uri $uri/ /index.php?$query_string;
 	}

 	location ~ \.php$ {
 		fastcgi_split_path_info ^(.+\.php)(/.+)$;
 		# NOTE: You should have "cgi.fix_pathinfo = 0;" in php.ini
 	}
 }
 ```

# Write a controller
Your controllers should extends the provided Venus controller base `Controller`.

Your actions should accept a `ServerRequestInterface` and a `RequestHandlerInterface` and always return a `ResponseInterface`. Because it can be a little bit too long to write, you can choose to use aliases like in the following example.

```php
namespace Example\Controller;

use Codevia\Venus\Controller\Controller;
use Psr\Http\Server\RequestHandlerInterface as Handler;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class LoginController extends Controller
{
    public function loginAsUser(Request $request, Handler $handler): Response
    {
        // Do your stuff here
        return $this->createResponse($request, $handler, $yourContent);
    }
}
```

# Link controller actions to routes
Your `router.php` must return an instance of `FastRoute\Dispatcher`. I suggest you to read how [FastRoute](https://github.com/nikic/FastRoute#readme) works.

```php
return FastRoute\simpleDispatcher(function (RouteCollector $r) {
    $r->addRoute('GET', '/bar', [Foo::class, 'getBar']);
    // All your routes here ...
});
```
