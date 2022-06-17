<?php

use Raider\Cache\NullCache;

class_exists('Raider\Cache\NullCache');

if (\false) {
    class Twig_Cache_Null extends NullCache
    {
    }
}
