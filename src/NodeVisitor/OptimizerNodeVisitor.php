<?php

/*
 * This file is part of Twig.
 *
 * (c) Fabien Potencier
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Raider\NodeVisitor;

use Raider\Environment;
use Raider\Node\BlockReferenceNode;
use Raider\Node\BodyNode;
use Raider\Node\Expression\AbstractExpression;
use Raider\Node\Expression\BlockReferenceExpression;
use Raider\Node\Expression\ConstantExpression;
use Raider\Node\Expression\FilterExpression;
use Raider\Node\Expression\FunctionExpression;
use Raider\Node\Expression\GetAttrExpression;
use Raider\Node\Expression\NameExpression;
use Raider\Node\Expression\ParentExpression;
use Raider\Node\Expression\TempNameExpression;
use Raider\Node\ForNode;
use Raider\Node\IncludeNode;
use Raider\Node\Node;
use Raider\Node\PrintNode;
use Raider\Node\SetTempNode;

/**
 * Tries to optimize the AST.
 *
 * This visitor is always the last registered one.
 *
 * You can configure which optimizations you want to activate via the
 * optimizer mode.
 *
 * @final
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class OptimizerNodeVisitor extends AbstractNodeVisitor
{
    public const OPTIMIZE_ALL = -1;
    public const OPTIMIZE_NONE = 0;
    public const OPTIMIZE_FOR = 2;
    public const OPTIMIZE_RAW_FILTER = 4;
    public const OPTIMIZE_VAR_ACCESS = 8;

    protected $loops = [];
    protected $loopsTargets = [];
    protected $optimizers;
    protected $prependedNodes = [];
    protected $inABody = false;

    /**
     * @param int $optimizers The optimizer mode
     */
    public function __construct($optimizers = -1)
    {
        if (!\is_int($optimizers) || $optimizers > (self::OPTIMIZE_FOR | self::OPTIMIZE_RAW_FILTER | self::OPTIMIZE_VAR_ACCESS)) {
            throw new \InvalidArgumentException(sprintf('Optimizer mode "%s" is not valid.', $optimizers));
        }

        $this->optimizers = $optimizers;
    }

    protected function doEnterNode(Node $node, Environment $env)
    {
        if (self::OPTIMIZE_FOR === (self::OPTIMIZE_FOR & $this->optimizers)) {
            $this->enterOptimizeFor($node, $env);
        }

        if (\PHP_VERSION_ID < 50400 && self::OPTIMIZE_VAR_ACCESS === (self::OPTIMIZE_VAR_ACCESS & $this->optimizers) && !$env->isStrictVariables() && !$env->hasExtension('\Raider\Extension\SandboxExtension')) {
            if ($this->inABody) {
                if (!$node instanceof AbstractExpression) {
                    if ('Raider_Node' !== \get_class($node)) {
                        array_unshift($this->prependedNodes, []);
                    }
                } else {
                    $node = $this->optimizeVariables($node, $env);
                }
            } elseif ($node instanceof BodyNode) {
                $this->inABody = true;
            }
        }

        return $node;
    }

    protected function doLeaveNode(Node $node, Environment $env)
    {
        $expression = $node instanceof AbstractExpression;

        if (self::OPTIMIZE_FOR === (self::OPTIMIZE_FOR & $this->optimizers)) {
            $this->leaveOptimizeFor($node, $env);
        }

        if (self::OPTIMIZE_RAW_FILTER === (self::OPTIMIZE_RAW_FILTER & $this->optimizers)) {
            $node = $this->optimizeRawFilter($node, $env);
        }

        $node = $this->optimizePrintNode($node, $env);

        if (self::OPTIMIZE_VAR_ACCESS === (self::OPTIMIZE_VAR_ACCESS & $this->optimizers) && !$env->isStrictVariables() && !$env->hasExtension('\Raider\Extension\SandboxExtension')) {
            if ($node instanceof BodyNode) {
                $this->inABody = false;
            } elseif ($this->inABody) {
                if (!$expression && 'Raider_Node' !== \get_class($node) && $prependedNodes = array_shift($this->prependedNodes)) {
                    $nodes = [];
                    foreach (array_unique($prependedNodes) as $name) {
                        $nodes[] = new SetTempNode($name, $node->getTemplateLine());
                    }

                    $nodes[] = $node;
                    $node = new Node($nodes);
                }
            }
        }

        return $node;
    }

    protected function optimizeVariables(\Raider_NodeInterface $node, Environment $env)
    {
        if ('Raider_Node_Expression_Name' === \get_class($node) && $node->isSimple()) {
            $this->prependedNodes[0][] = $node->getAttribute('name');

            return new TempNameExpression($node->getAttribute('name'), $node->getTemplateLine());
        }

        return $node;
    }

    /**
     * Optimizes print nodes.
     *
     * It replaces:
     *
     *   * "echo $this->render(Parent)Block()" with "$this->display(Parent)Block()"
     *
     * @return \Raider_NodeInterface
     */
    protected function optimizePrintNode(\Raider_NodeInterface $node, Environment $env)
    {
        if (!$node instanceof PrintNode) {
            return $node;
        }

        $exprNode = $node->getNode('expr');
        if (
            $exprNode instanceof BlockReferenceExpression ||
            $exprNode instanceof ParentExpression
        ) {
            $exprNode->setAttribute('output', true);

            return $exprNode;
        }

        return $node;
    }

    /**
     * Removes "raw" filters.
     *
     * @return \Raider_NodeInterface
     */
    protected function optimizeRawFilter(\Raider_NodeInterface $node, Environment $env)
    {
        if ($node instanceof FilterExpression && 'raw' == $node->getNode('filter')->getAttribute('value')) {
            return $node->getNode('node');
        }

        return $node;
    }

    /**
     * Optimizes "for" tag by removing the "loop" variable creation whenever possible.
     */
    protected function enterOptimizeFor(\Raider_NodeInterface $node, Environment $env)
    {
        if ($node instanceof ForNode) {
            // disable the loop variable by default
            $node->setAttribute('with_loop', false);
            array_unshift($this->loops, $node);
            array_unshift($this->loopsTargets, $node->getNode('value_target')->getAttribute('name'));
            array_unshift($this->loopsTargets, $node->getNode('key_target')->getAttribute('name'));
        } elseif (!$this->loops) {
            // we are outside a loop
            return;
        }

        // when do we need to add the loop variable back?

        // the loop variable is referenced for the current loop
        elseif ($node instanceof NameExpression && 'loop' === $node->getAttribute('name')) {
            $node->setAttribute('always_defined', true);
            $this->addLoopToCurrent();
        }

        // optimize access to loop targets
        elseif ($node instanceof NameExpression && \in_array($node->getAttribute('name'), $this->loopsTargets)) {
            $node->setAttribute('always_defined', true);
        }

        // block reference
        elseif ($node instanceof BlockReferenceNode || $node instanceof BlockReferenceExpression) {
            $this->addLoopToCurrent();
        }

        // include without the only attribute
        elseif ($node instanceof IncludeNode && !$node->getAttribute('only')) {
            $this->addLoopToAll();
        }

        // include function without the with_context=false parameter
        elseif ($node instanceof FunctionExpression
            && 'include' === $node->getAttribute('name')
            && (!$node->getNode('arguments')->hasNode('with_context')
                 || false !== $node->getNode('arguments')->getNode('with_context')->getAttribute('value')
               )
        ) {
            $this->addLoopToAll();
        }

        // the loop variable is referenced via an attribute
        elseif ($node instanceof GetAttrExpression
            && (!$node->getNode('attribute') instanceof ConstantExpression
                || 'parent' === $node->getNode('attribute')->getAttribute('value')
               )
            && (true === $this->loops[0]->getAttribute('with_loop')
                || ($node->getNode('node') instanceof NameExpression
                    && 'loop' === $node->getNode('node')->getAttribute('name')
                   )
               )
        ) {
            $this->addLoopToAll();
        }
    }

    /**
     * Optimizes "for" tag by removing the "loop" variable creation whenever possible.
     */
    protected function leaveOptimizeFor(\Raider_NodeInterface $node, Environment $env)
    {
        if ($node instanceof ForNode) {
            array_shift($this->loops);
            array_shift($this->loopsTargets);
            array_shift($this->loopsTargets);
        }
    }

    protected function addLoopToCurrent()
    {
        $this->loops[0]->setAttribute('with_loop', true);
    }

    protected function addLoopToAll()
    {
        foreach ($this->loops as $loop) {
            $loop->setAttribute('with_loop', true);
        }
    }

    public function getPriority()
    {
        return 255;
    }
}

class_alias('Raider\NodeVisitor\OptimizerNodeVisitor', 'Raider_NodeVisitor_Optimizer');
