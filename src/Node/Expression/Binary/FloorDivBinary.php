<?php

/*
 * This file is part of Twig.
 *
 * (c) Fabien Potencier
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Raider\Node\Expression\Binary;

use Raider\Compiler;

class FloorDivBinary extends AbstractBinary
{
    public function compile(Compiler $compiler)
    {
        $compiler->raw('(int) floor(');
        parent::compile($compiler);
        $compiler->raw(')');
    }

    public function operator(Compiler $compiler)
    {
        return $compiler->raw('/');
    }
}

class_alias('Raider\Node\Expression\Binary\FloorDivBinary', 'Twig_Node_Expression_Binary_FloorDiv');
