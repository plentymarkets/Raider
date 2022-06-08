<?php

use Raider\Sandbox\SecurityNotAllowedFilterError;

class_exists('Raider\Sandbox\SecurityNotAllowedFilterError');

if (\false) {
    class Raider_Sandbox_SecurityNotAllowedFilterError extends SecurityNotAllowedFilterError
    {
    }
}
