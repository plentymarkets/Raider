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

use Raider\Node\DoNode;
use Raider\Token;

/**
 * Evaluates an expression, discarding the returned value.
 *
 * @final
 */
class DoTokenParser extends AbstractTokenParser
{
    public function parse(Token $token)
    {
        $expr = $this->parser->getExpressionParser()->parseExpression();

        $this->parser->getStream()->expect(Token::BLOCK_END_TYPE);

        return new DoNode($expr, $token->getLine(), $this->getTag());
    }

    public function getTag()
    {
        return 'do';
    }
}

class_alias('Raider\TokenParser\DoTokenParser', 'Twig_TokenParser_Do');
