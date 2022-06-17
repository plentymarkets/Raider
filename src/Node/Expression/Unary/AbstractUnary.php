<?php

/*
 * This file is part of Twig.
 *
 * (c) Fabien Potencier
 * (c) Armin Ronacher
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Raider\Node\Expression\Unary;

use Raider\Compiler;
use Raider\Node\Expression\AbstractExpression;

abstract class AbstractUnary extends AbstractExpression
{
    public function __construct(\Twig_NodeInterface $node, $lineno)
    {
        parent::__construct(['node' => $node], [], $lineno);
    }

    public function compile(Compiler $compiler)
    {
        $compiler->raw(' ');
        $this->operator($compiler);
        $compiler->subcompile($this->getNode('node'));
    }

    abstract public function operator(Compiler $compiler);
}

class_alias('Raider\Node\Expression\Unary\AbstractUnary', 'Twig_Node_Expression_Unary');
