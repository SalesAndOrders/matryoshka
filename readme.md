# Matryoshka

Matryoshka is a package for Laravel that provides Russian-Doll caching for your view logic.

> Want to learn how this exact package was made from scratch? [See Laracasts.com](https://laracasts.com/series/russian-doll-caching-in-laravel).

## Installation

### Step 1: Composer

From the command line, run:

```
composer require laracasts/matryoshka
```

### Step 2: Service Provider

For your Laravel app, open `config/app.php` and, within the `providers` array, append:

```
Laracasts\Matryoshka\MatryoshkaServiceProvider::class
```

This will bootstrap the package into Laravel.

### Step 3: Cache Driver

For this package to function properly, you must use a Laravel cache driver that supports tagging (like `Cache::tags('foo')`). Drivers such as Memcached and Redis support this feature.

Check your `.env` file, and ensure that your `CACHE_DRIVER` choice accomodates this requirement:

```
CACHE_DRIVER=memcached
```

> Have a look at [Laravel's cache configuration documentation](https://laravel.com/docs/5.2/cache#configuration), if you need any help.

### Step 4: Routes Middleware

For your Laravel app, open `app/Http/Kernel.php` and, within the `$routeMiddleware` array, append:

```
'matryoshkaCache' => \Laracasts\Matryoshka\Middlewares\MatryoshkaCache::class
```

### Step 5: Matryoshka config:

You can ovveride packages default config options (expiration time of cahched views, wether or not to cahche views):
Copy packages config file (config/matryoshka.php) to applicatons app/config/ folder,
then in your .env file you can set custom values:
 
```
MATRYOSHKA_CACHE_EXPIRE=604800
MATRYOSHKA_CACHE_VIEWS=false
```

## Usage

### The Basics

With the package now installed perform these actions:

### Step 1: Attach middleware in `routes/web.php`

This middleware only applies to `GET` methods. To attach middleware to a GET method follow this example:

```
Route::get('/some-url', 'SomeController@getAllEntities')
    ->middleware('matryoshkaCache:entities,view/view:view-signature');
``` 

Breakdown of the params:

`matryoshkaCache:entities` - (before coma) matryoshkaCache - middlewere name, a part that will build name of the cached item
and will be used in view blade.

`view/view:view-signature` - (after coma)  view/view - view name, view-signature - view variable,
u can attach multiple view vars if u hane multiple bolck chached, but you will have to make sure view chached names
properly passed to matryoshka view directive `@cache('cahce-name', $modelOrCahedBlock) . contents here . @endcahche($modelOrCahedBlock) `

### Step 2: Enclose your html in directeve
, you may use the provided `@cache` Blade directive anywhere in your views, like so:

```html
@cache('cache-name', $model)
    <div>
        <h1>$model->getTitle()</h1>
    </div>
@endcache($model)
```

**Note:** overhere `$model` variable will be instance of `CacheContent` and if this view exists in cache and will contain cached
html content. Middleware will then render cached content instead of passing request to controller to query db and build view
using db data. If $model is instance of laravel model then that means it html is build from laravel controller,
and once it's built it will cache the contents so next time w get cached view instead quering db.

**Warning**: Cache lives for 7 days by default, cache is not flushed in laravel application,
this repo was forked from original one and was modified to work only for sando laravel app, be aware that you will have to implement
cache flush when thing are updated/delted. As of this release cache models and collections may work or may not work, for now it's not
intended to use these features. 
