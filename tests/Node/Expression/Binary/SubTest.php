<?php

namespace Twig\Tests\Node\Expression\Binary;

/*
 * This file is part of Twig.
 *
 * (c) Fabien Potencier
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Raider\Node\Expression\Binary\SubBinary;
use Raider\Node\Expression\ConstantExpression;
use Raider\Test\NodeTestCase;

class SubTest extends NodeTestCase
{
    public function testConstructor()
    {
        $left = new ConstantExpression(1, 1);
        $right = new ConstantExpression(2, 1);
        $node = new SubBinary($left, $right, 1);

        $this->assertEquals($left, $node->getNode('left'));
        $this->assertEquals($right, $node->getNode('right'));
    }

    public function getTests()
    {
        $left = new ConstantExpression(1, 1);
        $right = new ConstantExpression(2, 1);
        $node = new SubBinary($left, $right, 1);

        return [
            [$node, '(1 - 2)'],
        ];
    }
}
