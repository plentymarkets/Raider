<?php

namespace Twig\Tests\Node\Expression;

/*
 * This file is part of Twig.
 *
 * (c) Fabien Potencier
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Raider\Node\Expression\ArrayExpression;
use Raider\Node\Expression\ConstantExpression;
use Raider\Node\Expression\GetAttrExpression;
use Raider\Node\Expression\NameExpression;
use Raider\Template;
use Raider\Test\NodeTestCase;

class GetAttrTest extends NodeTestCase
{
    public function testConstructor()
    {
        $expr = new NameExpression('foo', 1);
        $attr = new ConstantExpression('bar', 1);
        $args = new ArrayExpression([], 1);
        $args->addElement(new NameExpression('foo', 1));
        $args->addElement(new ConstantExpression('bar', 1));
        $node = new GetAttrExpression($expr, $attr, $args, Template::ARRAY_CALL, 1);

        $this->assertEquals($expr, $node->getNode('node'));
        $this->assertEquals($attr, $node->getNode('attribute'));
        $this->assertEquals($args, $node->getNode('arguments'));
        $this->assertEquals(Template::ARRAY_CALL, $node->getAttribute('type'));
    }

    public function getTests()
    {
        $tests = [];

        $expr = new NameExpression('foo', 1);
        $attr = new ConstantExpression('bar', 1);
        $args = new ArrayExpression([], 1);
        $node = new GetAttrExpression($expr, $attr, $args, Template::ANY_CALL, 1);
        $tests[] = [$node, sprintf('%s%s, "bar", [])', $this->getAttributeGetter(), $this->getVariableGetter('foo', 1))];

        $node = new GetAttrExpression($expr, $attr, $args, Template::ARRAY_CALL, 1);
        $tests[] = [$node, sprintf('%s%s, "bar", [], "array")', $this->getAttributeGetter(), $this->getVariableGetter('foo', 1))];

        $args = new ArrayExpression([], 1);
        $args->addElement(new NameExpression('foo', 1));
        $args->addElement(new ConstantExpression('bar', 1));
        $node = new GetAttrExpression($expr, $attr, $args, Template::METHOD_CALL, 1);
        $tests[] = [$node, sprintf('%s%s, "bar", [0 => %s, 1 => "bar"], "method")', $this->getAttributeGetter(), $this->getVariableGetter('foo', 1), $this->getVariableGetter('foo'))];

        return $tests;
    }
}
