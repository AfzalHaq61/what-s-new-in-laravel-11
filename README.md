video-1 (Fewer Config Files)

You don't have to change anything in your skeleton to upgrade to Laravel 11. However, the first thing you'll likely notice when creating a new Laravel 11 project is the lack of... files! For example, a number of config files have been removed out of the box. How will this affect you? Let's take a look.

you can take it back by command 
```php artisan config:publish```

second change is that you dont have to register providers in config/app. you have make provider by command
```php artisan make:provider TestingServiceProvider```
and laravel will directly register it.
but if you want to register provider manually then you can register it in providers file in bootstrap folder.

-----------------------------------------------------------------------------------------------------------------------------------

video-2 (Missing Middleware)

You might be surprised to find that new Laravel 11 projects ship with an empty app/Http/Middleware directory. All of those files now live in the framework. Let's take a look at how you can configure them should you need to, as well as how to create and register your own middleware.

TrimString middleware:
TrimString middleware specifically targets string inputs, such as form submissions or API requests, and applies the trim() function or equivalent to remove any unnecessary whitespace characters.
TrimString middleware refers to a software component often used in web applications to remove leading and trailing whitespace from string inputs before further processing.

if you want to configure default middlwares in laravel 11 then you can do it in boot method of app serverice provider in providers folder

example 1:
here i am adding attribute to the exeption method in trim string middleware to except the secret attribute.
```TrimStrings::except(['secret']);```

example 2:
in this example i want to configure route in authentication middleware after authentication.
```RedirectIfAuthenticated::redirectUsing(fn($request) => route('dashboard'));```

adding custom middleware by command
```php artisan make:middlware LoginMiddlware```

before laravel 11 we have register middlware in kernal file but now we have to register it in app file in bootstrap folder like this.
```->withMiddleware(function (Middleware $middleware) {
        $middleware->web(LoginMiddleware::class);
    })```

register more than one
```->withMiddleware(function (Middleware $middleware) {
        $middleware->web([LoginMiddleware::class]);
    })```
    
make your exception here.

```->withExceptions(function (Exceptions $exceptions) {
        //
    })```

overall code

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web([LoginMiddleware::class]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();

--------------------------------------------------------------------------------
