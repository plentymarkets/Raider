<?php

use Raider\Sandbox\SecurityNotAllowedTagError;

class_exists('Raider\Sandbox\SecurityNotAllowedTagError');

if (\false) {
    class Twig_Sandbox_SecurityNotAllowedTagError extends SecurityNotAllowedTagError
    {
    }
}
