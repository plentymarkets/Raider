<?php

namespace Twig\Tests;

/*
 * This file is part of Twig.
 *
 * (c) Fabien Potencier
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Raider\Compiler;
use Raider\Environment;

class CompilerTest extends \PHPUnit\Framework\TestCase
{
    public function testReprNumericValueWithLocale()
    {
        $compiler = new Compiler(new Environment($this->createMock('\Raider\Loader\LoaderInterface')));

        $locale = setlocale(\LC_NUMERIC, 0);
        if (false === $locale) {
            $this->markTestSkipped('Your platform does not support locales.');
        }

        $required_locales = ['fr_FR.UTF-8', 'fr_FR.UTF8', 'fr_FR.utf-8', 'fr_FR.utf8', 'French_France.1252'];
        if (false === setlocale(\LC_NUMERIC, $required_locales)) {
            $this->markTestSkipped('Could not set any of required locales: '.implode(', ', $required_locales));
        }

        $this->assertEquals('1.2', $compiler->repr(1.2)->getSource());
        $this->assertStringContainsString('fr', strtolower(setlocale(\LC_NUMERIC, 0)));

        setlocale(\LC_NUMERIC, $locale);
    }
}
