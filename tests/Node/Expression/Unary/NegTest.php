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
use Raider\Node\Expression\Unary\NegUnary;
use Raider\Test\NodeTestCase;

class NegTest extends NodeTestCase
{
    public function testConstructor()
    {
        $expr = new ConstantExpression(1, 1);
        $node = new NegUnary($expr, 1);

        $this->assertEquals($expr, $node->getNode('node'));
    }

    public function getTests()
    {
        $node = new ConstantExpression(1, 1);
        $node = new NegUnary($node, 1);

        return [
            [$node, '-1'],
            [new NegUnary($node, 1), '- -1'],
        ];
    }
}
