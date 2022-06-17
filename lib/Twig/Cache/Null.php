<?php

use Raider\Cache\NullCache;

class_exists('Twig\Cache\NullCache');

if (\false) {
    class Twig_Cache_Null extends NullCache
    {
    }
}
