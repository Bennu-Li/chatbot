<?php

/**
 * This file is part of CommuneChatbot.
 *
 * @link     https://github.com/thirdgerb/chatbot
 * @document https://github.com/thirdgerb/chatbot/blob/master/README.md
 * @contact  <thirdgerb@gmail.com>
 * @license  https://github.com/thirdgerb/chatbot/blob/master/LICENSE
 */

namespace Commune\Protocals;

use Commune\Support\Message\Message;
use Commune\Support\Protocal\Protocal;

/**
 * Host 的基本消息类型, 是对输入输出信息的最基本抽象.
 *
 * @author thirdgerb <thirdgerb@gmail.com>
 */
interface HostMsg extends Message, Protocal
{
    // Debug 级别的消息, 客户端通常无法识别就不用渲染
    const DEBUG = 'debug';
    // 默认的消息级别.
    const INFO = 'info';
    const WARNING = 'warning';
    // 客户端应该给出提示的消息.
    const NOTICE = 'notice';
    // 客户端应该用错误信号来提醒的消息.
    const ERROR = 'error';

    const LEVELS = [
        self::DEBUG,
        self::INFO,
        self::WARNING,
        self::NOTICE,
        self::ERROR,
    ];


    /**
     * @return string
     */
    public function getProtocalId() : string;

    /**
     * @return string
     */
    public function getText() : string;

    /**
     * 获取消息等级.
     * @return string
     */
    public function getLevel() : string;
}