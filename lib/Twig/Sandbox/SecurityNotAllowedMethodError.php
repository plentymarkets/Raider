<?php

use Raider\Sandbox\SecurityNotAllowedMethodError;

class_exists('Twig\Sandbox\SecurityNotAllowedMethodError');

if (\false) {
    class Twig_Sandbox_SecurityNotAllowedMethodError extends SecurityNotAllowedMethodError
    {
    }
}
