<?php

use Raider\Error\RuntimeError;

class_exists('Twig\Error\RuntimeError');

if (\false) {
    class Twig_Error_Runtime extends RuntimeError
    {
    }
}
