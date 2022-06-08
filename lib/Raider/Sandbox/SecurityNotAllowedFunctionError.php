<?php

use Raider\Sandbox\SecurityNotAllowedFunctionError;

class_exists('Raider\Sandbox\SecurityNotAllowedFunctionError');

if (\false) {
    class Raider_Sandbox_SecurityNotAllowedFunctionError extends SecurityNotAllowedFunctionError
    {
    }
}
