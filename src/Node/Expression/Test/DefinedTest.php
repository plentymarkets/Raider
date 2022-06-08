<?php

/*
 * This file is part of Twig.
 *
 * (c) Fabien Potencier
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Raider\Node\Expression\Test;

use Raider\Compiler;
use Raider\Error\SyntaxError;
use Raider\Node\Expression\ArrayExpression;
use Raider\Node\Expression\BlockReferenceExpression;
use Raider\Node\Expression\ConstantExpression;
use Raider\Node\Expression\FunctionExpression;
use Raider\Node\Expression\GetAttrExpression;
use Raider\Node\Expression\NameExpression;
use Raider\Node\Expression\TestExpression;

/**
 * Checks if a variable is defined in the current context.
 *
 *    {# defined works with variable names and variable attributes #}
 *    {% if foo is defined %}
 *        {# ... #}
 *    {% endif %}
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class DefinedTest extends TestExpression
{
    public function __construct(\Raider_NodeInterface $node, $name, ?\Raider_NodeInterface $arguments, $lineno)
    {
        if ($node instanceof NameExpression) {
            $node->setAttribute('is_defined_test', true);
        } elseif ($node instanceof GetAttrExpression) {
            $node->setAttribute('is_defined_test', true);
            $this->changeIgnoreStrictCheck($node);
        } elseif ($node instanceof BlockReferenceExpression) {
            $node->setAttribute('is_defined_test', true);
        } elseif ($node instanceof FunctionExpression && 'constant' === $node->getAttribute('name')) {
            $node->setAttribute('is_defined_test', true);
        } elseif ($node instanceof ConstantExpression || $node instanceof ArrayExpression) {
            $node = new ConstantExpression(true, $node->getTemplateLine());
        } else {
            throw new SyntaxError('The "defined" test only works with simple variables.', $lineno);
        }

        parent::__construct($node, $name, $arguments, $lineno);
    }

    protected function changeIgnoreStrictCheck(GetAttrExpression $node)
    {
        $node->setAttribute('ignore_strict_check', true);

        if ($node->getNode('node') instanceof GetAttrExpression) {
            $this->changeIgnoreStrictCheck($node->getNode('node'));
        }
    }

    public function compile(Compiler $compiler)
    {
        $compiler->subcompile($this->getNode('node'));
    }
}

class_alias('Raider\Node\Expression\Test\DefinedTest', 'Raider_Node_Expression_Test_Defined');
