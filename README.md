# Venus Framework
Lightweight and lightning fast PHP framework. Focus on your business code, we take care of the rest.

## Installation

To install and use this framework, just create your project folder, `cd` inside it and run [Composer](https://getcomposer.org).

```
composer require codevia/venus
```

## Usage

### Init your app

Your `index.php` should look like the following:

```php
use Codevia\Venus\Application;
use Codevia\Venus\TestHandler;
use Codevia\Venus\Utils\Http\Input\JsonInput;
use FastRoute\RouteCollector;

// Fixes for the PhpSession middleware
ini_set('session.use_cookies', '0');
ini_set('session.cache_limiter', '');

require_once __DIR__ . '/bootstrap.php';

// Create your application
$app = new Application();

// Load your routes with a FastRoute Dispatcher
$dispatcher = require __DIR__ . '/dispatcher.php';

$app->setInputAdapter(new JsonInput); // Accept JSON requests
$app->setDispatcher($dispatcher);

$app->run(); // Run the middleware queue
```

### Create a controller

Your controller should be as simple as the following:

```php
namespace MyNamespace;

use Codevia\Venus\Controller\Controller;
use Laminas\Diactoros\ServerRequest;
use Middlewares\Utils\RequestHandler;
use Psr\Http\Message\ResponseInterface;

class FooController extends Controller
{
    public function getBar(ServerRequest $request, RequestHandler $handler): ResponseInterface
    {
        // Your code ...
        return $this->createResponse($request, $handler, $content);
    }

    // Other controllers ...
}
```

### Register a route entry to call this method

Inside your `dispatcher.php`, return a dispatcher as you can learn it from the [FastRoute README](https://github.com/nikic/FastRoute#readme):

```php
require_once __DIR__ . '/bootstrap.php';

use MyNamespace\Foo;

return FastRoute\simpleDispatcher(function (RouteCollector $r) {
    $r->addRoute('GET', '/bar', [Foo::class, 'getBar']);
    // All your routes here ...
});
```

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
RewriteRule ^ example.php [QSA,L]
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
