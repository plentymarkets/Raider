<?php

/*
 * This file is part of Twig.
 *
 * (c) Fabien Potencier
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Raider\Sandbox;

/**
 * Interface that all security policy classes must implements.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
interface SecurityPolicyInterface
{
    public function checkSecurity($tags, $filters, $functions);

    public function checkMethodAllowed($obj, $method);

    public function checkPropertyAllowed($obj, $method);
}

class_alias('Raider\Sandbox\SecurityPolicyInterface', 'Twig_Sandbox_SecurityPolicyInterface');
