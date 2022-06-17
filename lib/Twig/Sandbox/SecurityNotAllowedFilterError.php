<?php

use Raider\Sandbox\SecurityNotAllowedFilterError;

class_exists('Raider\Sandbox\SecurityNotAllowedFilterError');

if (\false) {
    class Twig_Sandbox_SecurityNotAllowedFilterError extends SecurityNotAllowedFilterError
    {
    }
}
