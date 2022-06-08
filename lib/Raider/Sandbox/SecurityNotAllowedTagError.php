<?php

use Raider\Sandbox\SecurityNotAllowedTagError;

class_exists('Raider\Sandbox\SecurityNotAllowedTagError');

if (\false) {
    class Raider_Sandbox_SecurityNotAllowedTagError extends SecurityNotAllowedTagError
    {
    }
}
