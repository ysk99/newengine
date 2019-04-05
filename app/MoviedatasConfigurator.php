<?php

namespace App;

use ScoutElastic\IndexConfigurator;
use ScoutElastic\Migratable;

class MoviedatasConfigurator extends IndexConfigurator
{
    use Migratable;

    /**
     * @var array
     */
     // It's not obligatory to determine name. By default it'll be a snaked class name without `IndexConfigurator` part.
       protected $name = 'searchdata';

       // You can specify any settings you want, for example, analyzers.
       protected $settings = [
         'refresh_interval' => '5s',
         'number_of_shards' => 1,
         'number_of_replicas' => 0,
       ];
}
