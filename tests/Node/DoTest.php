<?php

namespace Twig\Tests\Node;

/*
 * This file is part of Twig.
 *
 * (c) Fabien Potencier
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Raider\Node\DoNode;
use Raider\Node\Expression\ConstantExpression;
use Raider\Test\NodeTestCase;

class DoTest extends NodeTestCase
{
    public function testConstructor()
    {
        $expr = new ConstantExpression('foo', 1);
        $node = new DoNode($expr, 1);

        $this->assertEquals($expr, $node->getNode('expr'));
    }

    public function getTests()
    {
        $tests = [];

        $expr = new ConstantExpression('foo', 1);
        $node = new DoNode($expr, 1);
        $tests[] = [$node, "// line 1\n\"foo\";"];

        return $tests;
    }
}
