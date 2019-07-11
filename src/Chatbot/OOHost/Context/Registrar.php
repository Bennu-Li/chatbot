<?php


namespace Commune\Chatbot\OOHost\Context;



interface Registrar
{
    /**
     * @param Definition $def
     * @param bool $force
     * @return bool
     */
    public function register(Definition $def, bool $force = false)  : bool;

    public function has(string $contextName) : bool;

    public function get(string $contextName) : ? Definition;

    /**
     * @param string $contextName
     * @return bool
     */
    public static function validateName(string $contextName) : bool;

    /**
     * @return \Generator of Definition
     */
    public function each() : \Generator;

    /**
     * 已注册的context 数量.
     * @return int
     */
    public function count() : int;

    /**
     * @param string $domain
     * @return string[]
     */
    public function getNamesByDomain(string $domain) : array;


    /**
     * 按 definition->getTags() 的tag 来反查.
     * @param string ...$tags
     * @return string[]
     */
    public function getNamesByTag(string ...$tags) : array;

    /**
     * 仍然使用了 placeholder 的context
     * @return string[]
     */
    public function getPlaceholders() : array;
}