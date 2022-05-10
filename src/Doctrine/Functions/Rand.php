<?php

namespace App\Doctrine\Functions;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\QueryException;
use Doctrine\ORM\Query\SqlWalker;

class Rand extends FunctionNode
{
    public function parse(Parser $parser)
    {
        try {
            $parser->match(Lexer::T_IDENTIFIER);
            $parser->match(Lexer::T_OPEN_PARENTHESIS);
            $parser->match(Lexer::T_CLOSE_PARENTHESIS);
        } catch (QueryException $e) {
            return $e->getMessage();
        }
    }

    public function getSql(SqlWalker $sqlWalker): string
    {
        return 'RAND()';
    }
}
