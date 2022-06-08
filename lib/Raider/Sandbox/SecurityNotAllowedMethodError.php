<?php

use Raider\Sandbox\SecurityNotAllowedMethodError;

class_exists('Raider\Sandbox\SecurityNotAllowedMethodError');

if (\false) {
    class Raider_Sandbox_SecurityNotAllowedMethodError extends SecurityNotAllowedMethodError
    {
    }
}
