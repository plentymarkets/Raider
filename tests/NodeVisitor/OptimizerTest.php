<?php

namespace Twig\Tests\NodeVisitor;

/*
 * This file is part of Twig.
 *
 * (c) Fabien Potencier
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Raider\Environment;
use Raider\Node\ForNode;
use Raider\Source;

class OptimizerTest extends \PHPUnit\Framework\TestCase
{
    public function testRenderBlockOptimizer()
    {
        $env = new Environment($this->createMock('\Raider\Loader\LoaderInterface'), ['cache' => false, 'autoescape' => false]);

        $stream = $env->parse($env->tokenize(new Source('{{ block("foo") }}', 'index')));

        $node = $stream->getNode('body')->getNode(0);

        $this->assertInstanceOf('\Raider\Node\Expression\BlockReferenceExpression', $node);
        $this->assertTrue($node->getAttribute('output'));
    }

    public function testRenderParentBlockOptimizer()
    {
        $env = new Environment($this->createMock('\Raider\Loader\LoaderInterface'), ['cache' => false, 'autoescape' => false]);

        $stream = $env->parse($env->tokenize(new Source('{% extends "foo" %}{% block content %}{{ parent() }}{% endblock %}', 'index')));

        $node = $stream->getNode('blocks')->getNode('content')->getNode(0)->getNode('body');

        $this->assertInstanceOf('\Raider\Node\Expression\ParentExpression', $node);
        $this->assertTrue($node->getAttribute('output'));
    }

    /**
     * @dataProvider getTestsForForOptimizer
     */
    public function testForOptimizer($template, $expected)
    {
        $env = new Environment($this->createMock('\Raider\Loader\LoaderInterface'), ['cache' => false]);

        $stream = $env->parse($env->tokenize(new Source($template, 'index')));

        foreach ($expected as $target => $withLoop) {
            $this->assertTrue($this->checkForConfiguration($stream, $target, $withLoop), sprintf('variable %s is %soptimized', $target, $withLoop ? 'not ' : ''));
        }
    }

    public function getTestsForForOptimizer()
    {
        return [
            ['{% for i in foo %}{% endfor %}', ['i' => false]],

            ['{% for i in foo %}{{ loop.index }}{% endfor %}', ['i' => true]],

            ['{% for i in foo %}{% for j in foo %}{% endfor %}{% endfor %}', ['i' => false, 'j' => false]],

            ['{% for i in foo %}{% include "foo" %}{% endfor %}', ['i' => true]],

            ['{% for i in foo %}{% include "foo" only %}{% endfor %}', ['i' => false]],

            ['{% for i in foo %}{% include "foo" with { "foo": "bar" } only %}{% endfor %}', ['i' => false]],

            ['{% for i in foo %}{% include "foo" with { "foo": loop.index } only %}{% endfor %}', ['i' => true]],

            ['{% for i in foo %}{% for j in foo %}{{ loop.index }}{% endfor %}{% endfor %}', ['i' => false, 'j' => true]],

            ['{% for i in foo %}{% for j in foo %}{{ loop.parent.loop.index }}{% endfor %}{% endfor %}', ['i' => true, 'j' => true]],

            ['{% for i in foo %}{% set l = loop %}{% for j in foo %}{{ l.index }}{% endfor %}{% endfor %}', ['i' => true, 'j' => false]],

            ['{% for i in foo %}{% for j in foo %}{{ foo.parent.loop.index }}{% endfor %}{% endfor %}', ['i' => false, 'j' => false]],

            ['{% for i in foo %}{% for j in foo %}{{ loop["parent"].loop.index }}{% endfor %}{% endfor %}', ['i' => true, 'j' => true]],

            ['{% for i in foo %}{{ include("foo") }}{% endfor %}', ['i' => true]],

            ['{% for i in foo %}{{ include("foo", with_context = false) }}{% endfor %}', ['i' => false]],

            ['{% for i in foo %}{{ include("foo", with_context = true) }}{% endfor %}', ['i' => true]],

            ['{% for i in foo %}{{ include("foo", { "foo": "bar" }, with_context = false) }}{% endfor %}', ['i' => false]],

            ['{% for i in foo %}{{ include("foo", { "foo": loop.index }, with_context = false) }}{% endfor %}', ['i' => true]],
        ];
    }

    public function checkForConfiguration(\Twig_NodeInterface $node = null, $target, $withLoop)
    {
        if (null === $node) {
            return;
        }

        foreach ($node as $n) {
            if ($n instanceof ForNode) {
                if ($target === $n->getNode('value_target')->getAttribute('name')) {
                    return $withLoop == $n->getAttribute('with_loop');
                }
            }

            $ret = $this->checkForConfiguration($n, $target, $withLoop);
            if (null !== $ret) {
                return $ret;
            }
        }
    }
}
