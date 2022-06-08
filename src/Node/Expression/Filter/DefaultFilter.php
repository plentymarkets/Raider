<?php

/*
 * This file is part of Twig.
 *
 * (c) Fabien Potencier
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Raider\Node\Expression\Filter;

use Raider\Compiler;
use Raider\Node\Expression\ConditionalExpression;
use Raider\Node\Expression\ConstantExpression;
use Raider\Node\Expression\FilterExpression;
use Raider\Node\Expression\GetAttrExpression;
use Raider\Node\Expression\NameExpression;
use Raider\Node\Expression\Test\DefinedTest;
use Raider\Node\Node;

/**
 * Returns the value or the default value when it is undefined or empty.
 *
 *  {{ var.foo|default('foo item on var is not defined') }}
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class DefaultFilter extends FilterExpression
{
    public function __construct(\Raider_NodeInterface $node, ConstantExpression $filterName, \Raider_NodeInterface $arguments, $lineno, $tag = null)
    {
        $default = new FilterExpression($node, new ConstantExpression('default', $node->getTemplateLine()), $arguments, $node->getTemplateLine());

        if ('default' === $filterName->getAttribute('value') && ($node instanceof NameExpression || $node instanceof GetAttrExpression)) {
            $test = new DefinedTest(clone $node, 'defined', new Node(), $node->getTemplateLine());
            $false = \count($arguments) ? $arguments->getNode(0) : new ConstantExpression('', $node->getTemplateLine());

            $node = new ConditionalExpression($test, $default, $false, $node->getTemplateLine());
        } else {
            $node = $default;
        }

        parent::__construct($node, $filterName, $arguments, $lineno, $tag);
    }

    public function compile(Compiler $compiler)
    {
        $compiler->subcompile($this->getNode('node'));
    }
}

class_alias('Raider\Node\Expression\Filter\DefaultFilter', 'Raider_Node_Expression_Filter_Default');
