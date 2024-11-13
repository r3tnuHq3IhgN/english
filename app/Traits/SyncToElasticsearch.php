<?php

namespace App\Traits;

trait SyncToElasticsearch
{
    // code here
    abstract function syncDataToElastic($data);

}