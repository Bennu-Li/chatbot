<?php

/**
 * This file is part of CommuneChatbot.
 *
 * @link     https://github.com/thirdgerb/chatbot
 * @document https://github.com/thirdgerb/chatbot/blob/master/README.md
 * @contact  <thirdgerb@gmail.com>
 * @license  https://github.com/thirdgerb/chatbot/blob/master/LICENSE
 */

namespace Commune\Message\Abstracted;

use Commune\Message\Message;
use Commune\Support\Arr\ArrayAndJsonAble;

/**
 * 对输入消息的抽象理解库. 主要保存意图相关信息.
 *
 * @author thirdgerb <thirdgerb@gmail.com>
 */
interface Comprehended extends ArrayAndJsonAble
{

    /*----- matched intent -----*/

    /**
     * 获取已匹配到的 intentName
     *
     * @return null|string
     */
    public function getMatchedIntent() : ? string;

    /**
     * 设置一个匹配到的 intentName
     *
     * @param string $intentName
     */
    public function setMatchedIntent(string $intentName) : void;


    /**
     * 将所有的 PossibleIntent 进行排序, 获取置信度最高的意图
     *
     * @return null|string
     */
    public function getMostPossibleIntent() : ? string;


    /*----- possible intent -----*/

    /**
     * @param string $intentName 意图名称
     * @param int $odd 置信度, 注意是整数, 可以自己设计计算办法
     * @param bool $highlyPossible 表示置信度高于阈值了.
     *
     * @return mixed
     */
    public function addPossibleIntent(
        string $intentName,
        int $odd,
        bool $highlyPossible = true
    );

    /**
     * 获取所有的意图信息, 通常用于记录日志, 分析, 展示.
     *
     * @return string[]
     */
    public function getPossibleIntents() : array;

    /**
     * 判断某个意图是否存在
     *
     * @param string $intentName
     * @param bool $highlyOnly  只查找置信度高于阈值的.
     * @return bool
     */
    public function hasPossibleIntent(string $intentName, bool $highlyOnly = true) : bool;

    /**
     * 获取所有匹配到的意图名
     *
     * @param bool $highlyOnly
     * @return string[]
     */
    public function getPossibleIntentNames(bool $highlyOnly = true) : array;

    /**
     * 获取某个意图的置信度
     *
     * @param string $intentName
     * @return int|null
     */
    public function getOddOfPossibleIntent(string $intentName) : ? int;

    /*----- entities -----*/

    /**
     * 设置匹配到的 Entity, 是全局的 Entity
     *
     * @param array $entities
     */
    public function setEntities(array $entities) : void;

    /**
     * 合并新的 Entities 到全局的 Entities 中
     *
     * 添加 entities 信息, 到 global entity
     * @param array $entities
     */
    public function mergeEntities(array $entities) : void;

    /**
     * @param string $intentName
     * @param array $entities
     */
    public function setIntentEntities(string $intentName, array $entities) : void;

    /**
     * 全局的entity. 有些 NLU 的 entity 和 intent 是分开匹配的.
     *
     * @return array
     */
    public function getCommonEntities() : array;

    /**
     * 获取 全局entities + 命中意图的 entities
     *
     * @return array
     */
    public function getMatchedEntities() : array;

    /**
     * 获取某个意图的所有实体.
     *
     * @param string $intentName
     * @return array
     */
    public function getIntentEntities(string $intentName) : array;


    /*----- extra -----*/

    /**
     * 允许从 NLU 获取默认回复, 取代系统自带的拒答服务.
     *
     * @return Message[]
     */
    public function getDefaultReplies() : array;

    /**
     * 增加一条默认的回复消息.
     *
     * @param Message $message
     */
    public function addDefaultReply(Message $message) : void;

    /*----- choose -----*/

    /**
     * @param string|int $index
     * @param bool $only
     * @return bool
     */
    public function hasChoice($index, bool $only = false) : bool;

    /**
     * @param array $indexes
     */
    public function addChoice(array $indexes) : void;


    /*----- emotion, 还是实验中的思路 -----*/

    /**
     * @return bool
     */
    public function isPositive() : bool;

    /**
     * @return bool
     */
    public function isNegative() : bool;

    /**
     * @return string[]
     */
    public function getEmotions() : array;

    /**
     * @param string $emotionName
     */
    public function addEmotion(string $emotionName) : void;

    /**
     * 是否存在某一个 Emotion
     * @param string $emotionName
     * @return bool
     */
    public function hasEmotion(string $emotionName) : bool;

    /**
     * @param string[] $emotionNames
     */
    public function setEmotions(array $emotionNames) : void;

    /*----- 分词 -----*/

    /**
     * 设置分词
     * @param string[] $tokens
     */
    public function setTokens(array $tokens) : void;

    /**
     * 获取分词
     * @return string[]
     */
    public function getTokens() : array;

}