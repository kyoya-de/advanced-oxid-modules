<?php
/**
 * Created by PhpStorm.
 * User: Stefan
 * Date: 15.09.14
 * Time: 01:22
 */

namespace D4rk4ng3l\Oxid\Compiler;


class TokenParser
{
    /**
     * The token list.
     *
     * @var array
     */
    private $tokens;

    /**
     * The number of tokens.
     *
     * @var int
     */
    private $numTokens;

    private $tokenPointer = 0;

    private $namespace;

    private $className;

    private $useStatements = array();

    public $isClass = false;

    public function parse($contents)
    {
        $this->tokens = token_get_all($contents);

        // The PHP parser sets internal compiler globals for certain things. Annoyingly, the last docblock comment it
        // saw gets stored in doc_comment. When it comes to compile the next thing to be include()d this stored
        // doc_comment becomes owned by the first thing the compiler sees in the file that it considers might have a
        // docblock. If the first thing in the file is a class without a doc block this would cause calls to
        // getDocBlock() on said class to return our long lost doc_comment. Argh.
        // To workaround, cause the parser to parse an empty docblock. Sure getDocBlock() will return this, but at least
        // it's harmless to us.
        token_get_all("<?php\n/**\n *\n */");

        $this->numTokens = count($this->tokens);

        for (;$this->tokenPointer < $this->numTokens; $this->tokenPointer++) {
            $token = $this->tokens[$this->tokenPointer];
            if (T_NAMESPACE === $token[0]) {
                $this->namespace = $this->parseFQN();
            }

            if (T_USE === $token[0]) {
                $this->useStatements[] = $this->parseFQN();
            }

            if (T_CLASS === $token[0]) {
                $this->tokenPointer += 2;
                $this->className = $this->tokens[$this->tokenPointer][1];
                $this->isClass = true;
            }
        }
    }

    private function parseFQN()
    {
        $this->tokenPointer += 2;
        $token = $this->tokens[$this->tokenPointer];
        $fqn = '';
        while ($token !== ";" && T_WHITESPACE != $token[0]) {
            $fqn .= $token[1];
            $token = $this->tokens[++$this->tokenPointer];
        }

        return $fqn;
    }

    /**
     * @return array
     */
    public function getUseStatements()
    {
        return $this->useStatements;
    }

    /**
     * @return mixed
     */
    public function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * @return mixed
     */
    public function getClassName()
    {
        return $this->className;
    }

    public function getFullClassName()
    {
        return (strlen($this->namespace) > 0)
            ? ($this->namespace . "\\" . $this->className)
            : $this->className;
    }

    public function hasClass()
    {
        return $this->isClass;
    }
} 