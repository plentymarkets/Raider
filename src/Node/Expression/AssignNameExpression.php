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

namespace Raider\Node\Expression;

use Raider\Compiler;

class AssignNameExpression extends NameExpression
{
    public function compile(Compiler $compiler)
    {
        $compiler
            ->raw('$context[')
            ->string($this->getAttribute('name'))
            ->raw(']')
        ;
    }
}

class_alias('Raider\Node\Expression\AssignNameExpression', 'Twig_Node_Expression_AssignName');
