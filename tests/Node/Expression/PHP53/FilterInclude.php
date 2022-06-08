<?php

namespace Twig\Tests\Node\Expression\PHP53;

$env = new \Raider\Environment(new \Raider\Loader\ArrayLoader([]));
$env->addFilter(new \Raider\TwigFilter('anonymous', function () {}));

return $env;
