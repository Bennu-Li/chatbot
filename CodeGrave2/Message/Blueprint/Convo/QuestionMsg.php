<?php

/**
 * This file is part of CommuneChatbot.
 *
 * @link     https://github.com/thirdgerb/chatbot
 * @document https://github.com/thirdgerb/chatbot/blob/master/README.md
 * @contact  <thirdgerb@gmail.com>
 * @license  https://github.com/thirdgerb/chatbot/blob/master/LICENSE
 */

namespace Commune\Message\Blueprint\Convo;

use Commune\Message\Blueprint\ConvoMsg;
use Commune\Message\Blueprint\Tags\Conversational;
use Commune\Message\Blueprint\Tags\Verbal;

/**
 * 可以进行选择的消息.
 * 通常是发送给用户的
 *
 * @author thirdgerb <thirdgerb@gmail.com>
 */
interface QuestionMsg extends ConvoMsg, Verbal, Conversational
{

}