<?php

use Raider\Sandbox\SecurityNotAllowedFunctionError;

class_exists('Raider\Sandbox\SecurityNotAllowedFunctionError');

if (\false) {
    class Twig_Sandbox_SecurityNotAllowedFunctionError extends SecurityNotAllowedFunctionError
    {
    }
}
