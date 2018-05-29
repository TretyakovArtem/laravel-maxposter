<?php


use Illuminate\Support\Facades\Facade;

class Xml extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'Collect';
    }
}