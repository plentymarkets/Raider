<?php

/*
 * This file is part of Twig.
 *
 * (c) Fabien Potencier
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Raider\Extension {
use Raider\TwigFunction;

/**
 * @final
 */
class StringLoaderExtension extends AbstractExtension
{
    public function getFunctions()
    {
        return [
            new TwigFunction('template_from_string', 'twig_template_from_string', ['needs_environment' => true]),
        ];
    }

    public function getName()
    {
        return 'string_loader';
    }
}

class_alias('Raider\Extension\StringLoaderExtension', 'Twig_Extension_StringLoader');
}

namespace {
use Raider\Environment;
use Raider\TemplateWrapper;

/**
 * Loads a template from a string.
 *
 *     {{ include(template_from_string("Hello {{ name }}")) }}
 *
 * @param string $template A template as a string or object implementing __toString()
 * @param string $name     An optional name of the template to be used in error messages
 *
 * @return TemplateWrapper
 */
function twig_template_from_string(Environment $env, $template, $name = null)
{
    return $env->createTemplate((string) $template, $name);
}
}
