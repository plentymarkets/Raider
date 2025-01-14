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
use Raider\Node\Expression\NameExpression;
use Raider\Node\MacroNode;
use Raider\Node\Node;
use Raider\Node\TextNode;
use Raider\Test\NodeTestCase;

class MacroTest extends NodeTestCase
{
    public function testConstructor()
    {
        $body = new TextNode('foo', 1);
        $arguments = new Node([new NameExpression('foo', 1)], [], 1);
        $node = new MacroNode('foo', $body, $arguments, 1);

        $this->assertEquals($body, $node->getNode('body'));
        $this->assertEquals($arguments, $node->getNode('arguments'));
        $this->assertEquals('foo', $node->getAttribute('name'));
    }

    public function getTests()
    {
        $body = new TextNode('foo', 1);
        $arguments = new Node([
            'foo' => new ConstantExpression(null, 1),
            'bar' => new ConstantExpression('Foo', 1),
        ], [], 1);
        $node = new MacroNode('foo', $body, $arguments, 1);

        if (\PHP_VERSION_ID >= 50600) {
            $declaration = ', ...$__varargs__';
            $varargs = '$__varargs__';
        } else {
            $declaration = '';
            $varargs = 'func_num_args() > 2 ? array_slice(func_get_args(), 2) : []';
        }

        return [
            [$node, <<<EOF
// line 1
public function getfoo(\$__foo__ = null, \$__bar__ = "Foo"$declaration)
{
    \$context = \$this->env->mergeGlobals([
        "foo" => \$__foo__,
        "bar" => \$__bar__,
        "varargs" => $varargs,
    ]);

    \$blocks = [];

    ob_start(function () { return ''; });
    try {
        echo "foo";
    } catch (\Exception \$e) {
        ob_end_clean();

        throw \$e;
    } catch (\Throwable \$e) {
        ob_end_clean();

        throw \$e;
    }

    return ('' === \$tmp = ob_get_clean()) ? '' : new Markup(\$tmp, \$this->env->getCharset());
}
EOF
            ],
        ];
    }
}
