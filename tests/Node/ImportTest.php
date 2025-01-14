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

use Raider\Node\Expression\AssignNameExpression;
use Raider\Node\Expression\ConstantExpression;
use Raider\Node\ImportNode;
use Raider\Test\NodeTestCase;

class ImportTest extends NodeTestCase
{
    public function testConstructor()
    {
        $macro = new ConstantExpression('foo.twig', 1);
        $var = new AssignNameExpression('macro', 1);
        $node = new ImportNode($macro, $var, 1);

        $this->assertEquals($macro, $node->getNode('expr'));
        $this->assertEquals($var, $node->getNode('var'));
    }

    public function getTests()
    {
        $tests = [];

        $macro = new ConstantExpression('foo.twig', 1);
        $var = new AssignNameExpression('macro', 1);
        $node = new ImportNode($macro, $var, 1);

        $tests[] = [$node, <<<EOF
// line 1
\$context["macro"] = \$this->loadTemplate("foo.twig", null, 1)->unwrap();
EOF
        ];

        return $tests;
    }
}
