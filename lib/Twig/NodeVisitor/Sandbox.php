<?php

use Raider\NodeVisitor\SandboxNodeVisitor;

class_exists('Twig\NodeVisitor\SandboxNodeVisitor');

if (\false) {
    class Twig_NodeVisitor_Sandbox extends SandboxNodeVisitor
    {
    }
}
