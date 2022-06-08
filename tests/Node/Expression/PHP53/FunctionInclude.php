<?php

namespace Twig\Tests\Node\Expression\PHP53;

$env = new \Raider\Environment(new \Raider\Loader\ArrayLoader([]));
$env->addFunction(new \Raider\TwigFunction('anonymous', function () {}));

return $env;
