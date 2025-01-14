<?php

namespace Twig\Tests\Node\Expression\Unary;

/*
 * This file is part of Twig.
 *
 * (c) Fabien Potencier
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Raider\Node\Expression\ConstantExpression;
use Raider\Node\Expression\Unary\PosUnary;
use Raider\Test\NodeTestCase;

class PosTest extends NodeTestCase
{
    public function testConstructor()
    {
        $expr = new ConstantExpression(1, 1);
        $node = new PosUnary($expr, 1);

        $this->assertEquals($expr, $node->getNode('node'));
    }

    public function getTests()
    {
        $node = new ConstantExpression(1, 1);
        $node = new PosUnary($node, 1);

        return [
            [$node, '+1'],
        ];
    }
}
