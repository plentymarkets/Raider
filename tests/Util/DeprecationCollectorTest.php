<?php

namespace Twig\Tests\Util;

/*
 * This file is part of Twig.
 *
 * (c) Fabien Potencier
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Raider\Environment;
use Raider\TwigFunction;
use Raider\Util\DeprecationCollector;

class DeprecationCollectorTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @requires PHP 5.3
     */
    public function testCollect()
    {
        $twig = new Environment($this->createMock('\Raider\Loader\LoaderInterface'));
        $twig->addFunction(new TwigFunction('deprec', [$this, 'deprec'], ['deprecated' => true]));

        $collector = new DeprecationCollector($twig);
        $deprecations = $collector->collect(new Twig_Tests_Util_Iterator());

        $this->assertEquals(['Twig Function "deprec" is deprecated in deprec.twig at line 1.'], $deprecations);
    }

    public function deprec()
    {
    }
}

class Twig_Tests_Util_Iterator implements \IteratorAggregate
{
    public function getIterator()
    {
        return new \ArrayIterator([
            'ok.twig' => '{{ foo }}',
            'deprec.twig' => '{{ deprec("foo") }}',
        ]);
    }
}
