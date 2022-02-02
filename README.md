# Venus Framework
Lightweight and lightning fast PHP framework. Focus on your business code, we take care of the rest.

## Installation

To install and use this framework, just create your project folder, `cd` inside it and run [Composer](https://getcomposer.org).

```
composer require codevia/venus
```

## Usage

Your `index.php` should look like the following:

```php
use Codevia\Venus\Application;
use Codevia\Venus\TestHandler;
use Codevia\Venus\Utils\Http\Input\JsonInput;
use FastRoute\RouteCollector;

// Fixes for the PhpSession middleware
ini_set('session.use_cookies', '0');
ini_set('session.cache_limiter', '');

require_once __DIR__ . '/vendor/bootstrap.php';

// Create your application
$app = new Application();

// Load your routes with a FastRoute Dispatcher
$dispatcher = require __DIR__ . '/dispatcher.php';

$app->setInputAdapter(new JsonInput); // Accept JSON requests
$app->setDispatcher($dispatcher);

$app->run(); // Run the middleware queue

```