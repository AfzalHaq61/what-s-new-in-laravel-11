1. (Changes gor new projects)

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

video-2 (Streamlined Scheduling)

With all Kernel classes removed in Laravel 11, how do we schedule background tasks? It’s super simple, thanks to a shiny new Schedule facade.

just write this command of schedule facade in console file in routes directory.
```Schedule::command('module:prune')->daily();```

then you can check in cmd by write command
```php artisan schedule:list```

--------------------------------------------------------------------------------

video-4 (Installing an API)

If you look in the routes directory, you’ll notice that there are a couple of files missing, including api.php. Don’t panic, when you need an API for your app, it’s just an Artisan command away.

paste this command in terminal for api
```php artisan install:api```

i will install all the neceassray files including sanctum if you need another provider for api then use that one.

added HasApiToken to User model

if you wan to added middleware to it then do it like this

->withMiddleware(function (Middleware $middleware) {
        $middleware->web([LoginMiddleware::class])->statefulApi();
    })


paste this command in terminal for broadcasting routes
```php artisan install:broadcast```

it will install all the files related to broadcast events

--------------------------------------------------------------------------------

video-5 (Sqlite Out of the Box)

Here's a little change that I think explains the entire ethos behind the skeleton changes found in Laravel 11 - SQLite is now configured out of the box.

mean that by default the database will be sqlite you have to change it to mysql.

--------------------------------------------------------------------------------

video-6 (The Dumpable Trait)

A number of classes in Laravel have dump and dd methods available for quick debugging. With Laravel 11, it’s a breeze to add the same functionality to your own classes.

dump
It's a helper function used for debugging purposes. When you call dump() in your Laravel code, it will display the contents of the variable or expression passed to it but the script will not terminated.

dd
which stands for "Dump and Die." It's a helper function used for debugging purposes. When you call dd() in your Laravel code, it will display the contents of the variable or expression passed to it and then terminate the script execution.

we cam also use in between the queries like this
we can chain it to query
but its only work in laravel 11
User::latest()->limit(5)->dd()->get();

in laravel 11 its not a helper funciton but its a trait of name dumpable trait and its alreay avaiible in number of clasess for debugging but if we make our own class then we have to include dumpabale trait in that class. 

we have add dumpable trait in this custom class without trait it will not work. and you can overwrite these functins.
<?php

namespace App;

use Illuminate\Support\Traits\Dumpable;

class Car
{
    use Dumpable;

    private $make;
    private $model;
    private $year;

    public function __construct($make, $model, $year)
    {
        $this->make = $make;
        $this->model = $model;
        $this->year = $year;
    }

    public function dump(...$args) {
        dump($this, ...$args);

        return $this;
    }

    public function getMake()
    {
        return $this->make;
    }

    public function getModel()
    {
        return $this->model;
    }

    public function getYear()
    {
        return $this->year;
    }

    public function drive()
    {
        return "Driving the {$this->year} {$this->make} {$this->model}";
    }
}

----------------------------------------------------------------------------------

video-7 (Limitless Limits for Eager Loading)

Limiting the number of records on an eager loaded relationship in Laravel has historically been a pain, with a number of unexpected side effects. In Laravel 11, we can finally lay that issue to rest, because it now works exactly as you’d expect.

return User::with(['posts' => fn($query) => $query->latest()->limit(2)])->get();

here we apply array syntax for posts so we can apply custom logic to it.
in previous version it just load the logic for the first index but not for others because og limits in SQL. you have to install severl pacakges for it.
in laravel 11 the problem is solved now it changes it work directly.

we can also achive this on relation in model.
public function latestPosts(): HasMany
{
    return $this->hasMany(Post::class)->latest()->limit(2);
}

----------------------------------------------------------------------------------

video-8 (Super Simple Memoization)

Closures are often used as callback functions.

We all have different methods for memoization - the technique of caching an expensive operation for the lifecycle of a request. In Laravel 11, we can memoize any value with a single, simple function called once.

this is custom method to memorize a value.

class MemoizationService
{
    private $cachedResult = null;

    public function getResult()
    {
        if ($this->cachedResult === null) {
            $this->cachedResult = $this->expensiveOperation();
        }

        return $this->cachedResult;
    }

    private function expensiveOperation()
    {
        // Simulating an expensive operation
        sleep(2);
        return "Result of expensive operation";
    }
}

here we used laravel once functin to memoize a value

class MemoizationService
{
    public function getResult()
    {
        once(fn() => $this->cachedResult = $this->expensiveOperation());
    }

    private function expensiveOperation()
    {
        sleep(2);
        return "Result of expensive operation";
    }
}

another example

public function boot(): void
    {
        Request::macro('identifier', function() {
            return once(fn() => Str::uuid());
        } );

    }

    dump($request->identifier());
    dump($request->identifier());
    dump($request->identifier());


Macro: In Laravel, the Request::macro() method is used to define custom macros on the Illuminate\Http\Request class. This method allows you to extend the functionality of the Request object by adding custom methods that can be used throughout your application.

If you use once in a class and declare different instances of that class then the once cache value must be different of the same class.

----------------------------------------------------------------------------------------

video-9 (A Minor Tweak to Model Casts)

All Laravel developers are familiar with the $casts property. That property will still work in Laravel 11, but the new default is a casts method instead. What's the reason for the change? Let me quickly demonstrate!

old
protected $casts = [
        'start_time' => 'time:H:i:s',
        'end_time' => 'time:H:i:s',
    ];

new (by default)

This method is useful when you need more flexibility in defining the casts, or when you want to generate the casts dynamically based on certain factors.
there is some error given when generate dynamic casts attributes. so if we use this method than that error will resolved automatically.

protected function casts(): array
    {
        return [
            'published_at' => 'datetime'
        ];
    }

----------------------------------------------------------------------------------------

video-10 (Per Second Rate Limits)

Have you had chance to use Laravel's rate limiter? It's pretty powerful. However, it's historically only been able to limit requests by the minute. Laravel 11 allows you to go further with per-second rate limiting, allowing for even more fine-grained control.

Laravel now supports "per-second" rate limiting for all rate limiters, including those for HTTP requests and queued jobs. Previously, Laravel's rate limiters were limited to "per-minute" granularity:

- This should be define in appServiceProvider

RateLimiter::for('invoices', function (Request $request) {
    return Limit::perSecond(1);
});

RateLimiter::for('login', function (Request $request) {
    return [
        Limit::perMinute(500),
        Limit::perMinute(3)->by($request->input('email')),
    ];
});

- globally we can apply to the whole apis by this method in apps.php in bootstrap folder.
->withMiddleware(function (Middleware $middleware) {
        $middleware->api('throttle:api');
    })

- apply to specific routes or groups of routes.
Route::middleware(['throttle:uploads'])->group(function () {
    Route::post('/audio', function () {
        // ...
    });
 
    Route::post('/video', function () {
        // ...
    });
});

----------------------------------------------------------------------------------------
