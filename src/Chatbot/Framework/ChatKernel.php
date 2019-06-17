<?php

/**
 * Class ChatbotServerKernel
 * @package Commune\Chatbot\Framework
 */

namespace Commune\Chatbot\Framework;


use Commune\Chatbot\Blueprint\Application;
use Commune\Chatbot\Blueprint\Conversation\Conversation;
use Commune\Chatbot\Blueprint\Conversation\MessageRequest;
use Commune\Chatbot\Blueprint\Kernel;
use Commune\Chatbot\Contracts\ChatServer;
use Commune\Chatbot\Contracts\ExceptionHandler;
use Commune\Chatbot\Framework\Utils\OnionPipeline;
use Commune\Container\ContainerContract;

/**
 * Class ChatbotKernel
 * @package Commune\Chatbot\Framework
 * @author thirdgerb <thirdgerb@gmail.com>
 */
class ChatKernel implements Kernel
{
    /**
     * @var ChatApp
     */
    protected $app;

    /**
     * @var ChatServer
     */
    protected $server;

    /**
     * @var ExceptionHandler
     */
    protected $expHandler;

    /**
     * ChatbotKernel constructor.
     * @param Application $app
     * @param ChatServer $server
     * @param ExceptionHandler $handler
     */
    public function __construct(
        Application $app,
        ChatServer $server,
        ExceptionHandler $handler
    )
    {
        $this->app = $app;
        $this->server = $server;
        $this->expHandler = $handler;
    }

    public function onUserMessage(MessageRequest $request): void
    {
        try {
            $chatbotConfig = $this->app->getConfig();

            // 初始化这次请求独有的对话容器.
            $conversation = $this->app
                ->getConversationContainer()
                ->onMessage(
                    $request,
                    $chatbotConfig
                );

            // 对对话容器进行boot
            $this->app->bootConversation($conversation);

            // 创建pipeline
            $pipeline = $this->buildPipeline(
                    $conversation,
                    $chatbotConfig->chatbotPipes->onUserMessage
                )->via(Kernel::ON_USER_MESSAGE);

            // 发送会话
            /**
             * @var Conversation $conversation
             */
            $conversation = $pipeline->send(
                $conversation,
                function (Conversation $conversation): Conversation {
                    return $conversation;
                }
            );

            $conversation->finish();

        // 理论上不出现任何异常.
        } catch (\Throwable $e) {

            $this->app->getReactorLogger()->critical($e);
            // 直接exit
            $this->server->fail();
        }
    }

    /**
     * 组装管道
     *
     * @param ContainerContract $container
     * @param array $pipes
     * @return OnionPipeline
     */
    protected function buildPipeline(
        ContainerContract $container,
        array $pipes
    ) : OnionPipeline
    {
        // 关键, 从pipeline 开始, 所有的依赖注入都来自conversation
        $pipeline = new OnionPipeline($container);

        // 中间管道.
        foreach ($pipes as $chatbotPipeName) {
            $pipeline->through($chatbotPipeName);
        }

        // host
        // 返回
        return $pipeline;
    }

}