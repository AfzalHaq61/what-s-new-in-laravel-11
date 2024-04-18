video-1 (Fewer Config Files)

You don't have to change anything in your skeleton to upgrade to Laravel 11. However, the first thing you'll likely notice when creating a new Laravel 11 project is the lack of... files! For example, a number of config files have been removed out of the box. How will this affect you? Let's take a look.

you can take it back by command 
```php artisan config:publish```

second change is that you dont have to register providers in config/app. you have make provider by command
```php artisan make:provider TestingServiceProvider```
and laravel will directly register it.
but if you want to register provider manually then you can register it in providers file in bootstrap folder.

-----------------------------------------------------------------------------------------------------------------------------------