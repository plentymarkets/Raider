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

namespace Raider\Node\Expression\Binary;

use Raider\Compiler;

class BitwiseOrBinary extends AbstractBinary
{
    public function operator(Compiler $compiler)
    {
        return $compiler->raw('|');
    }
}

class_alias('Raider\Node\Expression\Binary\BitwiseOrBinary', 'Twig_Node_Expression_Binary_BitwiseOr');
