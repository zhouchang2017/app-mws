<?php
/**
 * Created by PhpStorm.
 * User: zhouchang
 * Date: 2019/1/5
 * Time: 下午2:36
 */

namespace App\Traits;

use Illuminate\Notifications\Notifiable as BaseNotifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;


trait Notifiable
{
    use BaseNotifiable;

    public static function getTypes()
    {
        if(!Cache::has('notify_types')){
            $namespace = app()->getNamespace();
            $types = [];
            $directory = app_path('Notifications');
            foreach ((new \Symfony\Component\Finder\Finder())->in($directory)->files() as $resource) {
                $resource = $namespace . str_replace(
                        [ '/', '.php' ],
                        [ '\\', '' ],
                        \Illuminate\Support\Str::after($resource->getPathname(), app_path() . DIRECTORY_SEPARATOR)
                    );
                $types[] = [
                    'type' => $resource,
                    'name' => (new \ReflectionClass($resource))->getStaticPropertyValue('typeName'),
                ];
            }
            Cache::put('notify_types',$types,Carbon::now()->addMinutes(60));
        }

        return Cache::get('notify_types');
    }
}