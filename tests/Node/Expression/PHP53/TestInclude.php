<?php

namespace Twig\Tests\Node\Expression\PHP53;

$env = new \Raider\Environment(new \Raider\Loader\ArrayLoader([]));
$env->addTest(new \Raider\TwigTest('anonymous', function () {}));

return $env;
