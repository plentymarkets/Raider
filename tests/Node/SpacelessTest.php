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

use Raider\Node\Node;
use Raider\Node\SpacelessNode;
use Raider\Node\TextNode;
use Raider\Test\NodeTestCase;

class SpacelessTest extends NodeTestCase
{
    public function testConstructor()
    {
        $body = new Node([new TextNode('<div>   <div>   foo   </div>   </div>', 1)]);
        $node = new SpacelessNode($body, 1);

        $this->assertEquals($body, $node->getNode('body'));
    }

    public function getTests()
    {
        $body = new Node([new TextNode('<div>   <div>   foo   </div>   </div>', 1)]);
        $node = new SpacelessNode($body, 1);

        return [
            [$node, <<<EOF
// line 1
ob_start(function () { return ''; });
echo "<div>   <div>   foo   </div>   </div>";
echo trim(preg_replace('/>\s+</', '><', ob_get_clean()));
EOF
            ],
        ];
    }
}
