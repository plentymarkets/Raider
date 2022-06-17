<?php

use Raider\Sandbox\SecurityNotAllowedPropertyError;

class_exists('Raider\Sandbox\SecurityNotAllowedPropertyError');

if (\false) {
    class Twig_Sandbox_SecurityNotAllowedPropertyError extends SecurityNotAllowedPropertyError
    {
    }
}
