<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::view('/', 'welcome');

Route::get('/test',function (){
    $namespace = app()->getNamespace();

    $resources = [];
    $directory = app_path('Http/Controllers');
    foreach ((new \Symfony\Component\Finder\Finder())->in($directory)->files() as $resource) {
        $resource = $namespace.str_replace(
                ['/', '.php'],
                ['\\', ''],
                \Illuminate\Support\Str::after($resource->getPathname(), app_path().DIRECTORY_SEPARATOR)
            );
        $resources[] = $resource;
//        if (is_subclass_of($resource, Resource::class) &&
//            ! (new ReflectionClass($resource))->isAbstract()) {
//            $resources[] = $resource;
//        }
    }
    dd($resources);
});
//Route::get('/home', 'HomeController@index')->name('home');

//Route::get('/users/{user}', 'UserController@show')->name('user.show');

//Route::get('/users/profile', 'UserController@profile')->name('user.profile');

