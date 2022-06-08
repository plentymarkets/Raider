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

use Raider\Node\Expression\ConstantExpression;
use Raider\Node\PrintNode;
use Raider\Test\NodeTestCase;

class PrintTest extends NodeTestCase
{
    public function testConstructor()
    {
        $expr = new ConstantExpression('foo', 1);
        $node = new PrintNode($expr, 1);

        $this->assertEquals($expr, $node->getNode('expr'));
    }

    public function getTests()
    {
        $tests = [];
        $tests[] = [new PrintNode(new ConstantExpression('foo', 1), 1), "// line 1\necho \"foo\";"];

        return $tests;
    }
}
