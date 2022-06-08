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

/**
 * Interface for node visitor classes.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
interface NodeVisitorInterface
{
    /**
     * Called before child nodes are visited.
     *
     * @return \Raider_NodeInterface The modified node
     */
    public function enterNode(\Raider_NodeInterface $node, Environment $env);

    /**
     * Called after child nodes are visited.
     *
     * @return \Raider_NodeInterface|false|null The modified node or null if the node must be removed
     */
    public function leaveNode(\Raider_NodeInterface $node, Environment $env);

    /**
     * Returns the priority for this visitor.
     *
     * Priority should be between -10 and 10 (0 is the default).
     *
     * @return int The priority level
     */
    public function getPriority();
}

class_alias('Raider\NodeVisitor\NodeVisitorInterface', 'Raider_NodeVisitorInterface');

// Ensure that the aliased name is loaded to keep BC for classes implementing the typehint with the old aliased name.
class_exists('Raider\Environment');
