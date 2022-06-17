<?php

use Raider\NodeVisitor\SandboxNodeVisitor;

class_exists('Raider\NodeVisitor\SandboxNodeVisitor');

if (\false) {
    class Twig_NodeVisitor_Sandbox extends SandboxNodeVisitor
    {
    }
}
