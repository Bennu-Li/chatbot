<?php

/**
 * This file is part of CommuneChatbot.
 *
 * @link     https://github.com/thirdgerb/chatbot
 * @document https://github.com/thirdgerb/chatbot/blob/master/README.md
 * @contact  <thirdgerb@gmail.com>
 * @license  https://github.com/thirdgerb/chatbot/blob/master/LICENSE
 */

namespace Commune\Chatbot;

use Commune\Chatbot\Blueprint\Chatbot;
use Commune\Framework\Blueprint\App;
use Commune\Framework\Blueprint\ReqContainer;
use Commune\Framework\Contracts\Cache;
use Commune\Framework\Contracts\ConsoleLogger;
use Commune\Framework\Contracts\ExceptionReporter;
use Commune\Framework\Contracts\LogInfo;
use Commune\Framework\Contracts\Messenger;
use Commune\Ghost\Blueprint\Ghost;
use Commune\Ghost\Blueprint\Kernels\ApiKernel;
use Commune\Ghost\Blueprint\Kernels\MessageKernel;
use Commune\Ghost\Blueprint\Convo\Conversation;
use Commune\Ghost\Contracts\GhostRequest;
use Commune\Ghost\Contracts\GhostResponse;
use Commune\Ghost\GhostConfig;
use Commune\Shell\Blueprint\Kernels\RequestKernel;
use Commune\Shell\Blueprint\Render\Renderer;
use Commune\Shell\Blueprint\Session\ShellSession;
use Commune\Shell\Blueprint\Shell;
use Commune\Shell\Contracts\ShellRequest;
use Commune\Shell\Contracts\ShellResponse;
use Commune\Shell\Contracts\ShellServer;
use Commune\Shell\ShellConfig;
use Commune\Support\Babel\BabelResolver;
use Psr\Log\LoggerInterface;


/**
 * @author thirdgerb <thirdgerb@gmail.com>
 */
class InjectableDependencies
{
    const CHATBOT_BASIC_BINDINGS = [
        Chatbot::class,
        ChatbotConfig::class,
        LogInfo::class,
        ConsoleLogger::class,
    ];

    const APP_BASIC_BINDINGS = [
        // 单例
        Cache::class,
        BabelResolver::class,
        App::class,

        // 非单例
        Messenger::class,
        ExceptionReporter::class,
        Cache::class,

        // 未必
        LoggerInterface::class,
    ];

    const SHELL_PROCESS_LEVEL = [
        Shell::class,
        ChatbotConfig::class,
        ShellConfig::class,
        Renderer::class,
        ShellServer::class,

        // 非单例
        RequestKernel::class,
    ];

    const GHOST_PROCESS_LEVEL = [
        Ghost::class,
        ChatbotConfig::class,
        GhostConfig::class,

        // 非单例
        MessageKernel::class,
        ApiKernel::class,
    ];

    const REQUEST_LEVEL = [
        // 单例
        ReqContainer::class,
        Messenger::class,
        ExceptionReporter::class,
        Cache::class,
        BabelResolver::class,
        LoggerInterface::class,
    ];


    const SHELL_REQUEST_LEVEL = [
        ShellSession::class,
        ShellRequest::class,
        ShellResponse::class,
    ];

    const GHOST_REQUEST_LEVEL = [
        Conversation::class,
        GhostResponse::class,
        GhostRequest::class,
    ];

}