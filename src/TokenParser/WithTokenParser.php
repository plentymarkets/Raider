<?php

/*
 * This file is part of Twig.
 *
 * (c) Fabien Potencier
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Raider\TokenParser;

use Raider\Node\WithNode;
use Raider\Token;

/**
 * Creates a nested scope.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 *
 * @final
 */
class WithTokenParser extends AbstractTokenParser
{
    public function parse(Token $token)
    {
        $stream = $this->parser->getStream();

        $variables = null;
        $only = false;
        if (!$stream->test(Token::BLOCK_END_TYPE)) {
            $variables = $this->parser->getExpressionParser()->parseExpression();
            $only = $stream->nextIf(Token::NAME_TYPE, 'only');
        }

        $stream->expect(Token::BLOCK_END_TYPE);

        $body = $this->parser->subparse([$this, 'decideWithEnd'], true);

        $stream->expect(Token::BLOCK_END_TYPE);

        return new WithNode($body, $variables, $only, $token->getLine(), $this->getTag());
    }

    public function decideWithEnd(Token $token)
    {
        return $token->test('endwith');
    }

    public function getTag()
    {
        return 'with';
    }
}

class_alias('Raider\TokenParser\WithTokenParser', 'Twig_TokenParser_With');
