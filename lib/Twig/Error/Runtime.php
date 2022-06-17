<?php

use Raider\Error\RuntimeError;

class_exists('Raider\Error\RuntimeError');

if (\false) {
    class Twig_Error_Runtime extends RuntimeError
    {
    }
}
