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

use Raider\Node\BlockNode;
use Raider\Node\TextNode;
use Raider\Test\NodeTestCase;

class BlockTest extends NodeTestCase
{
    public function testConstructor()
    {
        $body = new TextNode('foo', 1);
        $node = new BlockNode('foo', $body, 1);

        $this->assertEquals($body, $node->getNode('body'));
        $this->assertEquals('foo', $node->getAttribute('name'));
    }

    public function getTests()
    {
        $body = new TextNode('foo', 1);
        $node = new BlockNode('foo', $body, 1);

        return [
            [$node, <<<EOF
// line 1
public function block_foo(\$context, array \$blocks = [])
{
    echo "foo";
}
EOF
            ],
        ];
    }
}
