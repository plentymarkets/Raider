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

class PosUnary extends AbstractUnary
{
    public function operator(Compiler $compiler)
    {
        $compiler->raw('+');
    }
}

class_alias('Raider\Node\Expression\Unary\PosUnary', 'Raider_Node_Expression_Unary_Pos');
