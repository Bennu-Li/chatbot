<?php

/**
 * This file is part of CommuneChatbot.
 *
 * @link     https://github.com/thirdgerb/chatbot
 * @document https://github.com/thirdgerb/chatbot/blob/master/README.md
 * @contact  <thirdgerb@gmail.com>
 * @license  https://github.com/thirdgerb/chatbot/blob/master/LICENSE
 */

namespace Commune\Components\Demo\Recall;

use Commune\Support\Parameter\ParamBuilder;
use Commune\Host\Recall\ARecall;


/**
 * @author thirdgerb <thirdgerb@gmail.com>
 *
 * @property int $test
 * @property int $test1
 */
class Sandbox extends ARecall
{
    public static function getScopes(): array
    {
        return [];
    }


    public static function paramBuilder(ParamBuilder $builder): ParamBuilder
    {
        return $builder->def('test', 0);
    }


}