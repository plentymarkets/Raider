<?php

use Raider\Sandbox\SecurityNotAllowedMethodError;

class_exists('Raider\Sandbox\SecurityNotAllowedMethodError');

if (\false) {
    class Twig_Sandbox_SecurityNotAllowedMethodError extends SecurityNotAllowedMethodError
    {
    }
}
