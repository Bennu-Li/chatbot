<?php

/**
 * This file is part of CommuneChatbot.
 *
 * @link     https://github.com/thirdgerb/chatbot
 * @document https://github.com/thirdgerb/chatbot/blob/master/README.md
 * @contact  <thirdgerb@gmail.com>
 * @license  https://github.com/thirdgerb/chatbot/blob/master/LICENSE
 */

namespace Commune\Message\Intercom;

use Commune\Message\Abstracted\IComprehension;
use Commune\Message\Host\Convo\IVerbalMsg;
use Commune\Protocals\Comprehension;
use Commune\Protocals\HostMsg;
use Commune\Protocals\Intercom\GhostInput;
use Commune\Protocals\Intercom\ShellInput;
use Commune\Support\Message\AbsMessage;
use Commune\Support\Struct\Struct;
use Commune\Support\Uuid\HasIdGenerator;
use Commune\Support\Uuid\IdGeneratorHelper;


/**
 * @author thirdgerb <thirdgerb@gmail.com>
 *
 * @property-read string $shellName
 * @property-read string $shellId
 * @property-read string $senderId
 *
 * @property-read string $messageId
 * @property-read string $batchId
 * @property-read string|null $sessionId
 *
 * @property-read HostMsg $message
 * @property-read Comprehension $comprehension
 *
 *
 * @property-read float $deliverAt
 * @property-read float $createdAt
 *
 * @property-read array $env
 * @property-read string $sceneId
 */
class IShellInput extends AbsMessage implements ShellInput, HasIdGenerator
{
    use IdGeneratorHelper;

    protected $transferNoEmptyRelations = false;

    public function __construct(
        HostMsg $message,
        string $shellName,
        string $senderId,
        string $messageId = null,
        string $shellId = null,
        array $moreInfo = [
            //'batchId' => 'id',
            //'sceneId' => '',
            //'sessionId' => '',
            //'env' => [],
            //'deliverAt' => 0,
            //'createdAt' => 0
        ],
        $comprehension = null
    )
    {
        $moreInfo['message'] = $message;
        $moreInfo['shellName'] = $shellName;
        $moreInfo['senderId'] = $senderId;

        $moreInfo['messageId'] = empty($messageId) ? $this->createUuId() : $messageId;
        $moreInfo['shellId'] = empty($shellId)
            ? sha1("shellName:$shellName:sender:$senderId")
            : $shellId;

        $moreInfo['comprehension'] = $comprehension ?? [];
        parent::__construct($moreInfo);
    }

    public static function stub(): array
    {
        return [
            'shellName' => '',
            'senderId' => '',
            'shellId' => '',

            'messageId' => '',
            'batchId' => '',

            'sceneId' => '',
            'env' => [],
            'sessionId' => null,

            'message' => new IVerbalMsg(),
            'comprehension' => new IComprehension(),

            'deliverAt' => $now = round(floatval(microtime(true)), 3),
            'createdAt' => $now,
        ];
    }

    public static function create(array $data = []): Struct
    {
        return new static(
            $data['message'] ?? null,
            $data['shellName'] ?? '',
            $data['senderId'] ?? '',
            $data['messageId'] ?? '',
            $data['shellId'] ?? '',
            $data,
            $data['comprehension'] ?? null
        );
    }

    public static function relations(): array
    {
        return [
            'message' => HostMsg::class,
            'comprehension' => IComprehension::class
        ];
    }

    public function getMessageId(): string
    {
        return $this->messageId;
    }

    public function getBatchId(): string
    {
        $batchId = $this->batchId;
        return empty($batchId) ? $this->messageId : $this->batchId;
    }

    public function getMessage(): HostMsg
    {
        return $this->message;
    }

    public function getCreatedAt(): float
    {
        return $this->createdAt;
    }

    public function getDeliverAt(): float
    {
        return $this->deliverAt;
    }

    public function isEmpty(): bool
    {
        return false;
    }

    public function getSceneId(): string
    {
        return $this->sceneId;
    }

    public function getEnv(): array
    {
        return $this->env;
    }

    public function getComprehension(): Comprehension
    {
        return $this->comprehension;
    }

    public function getShellName(): string
    {
        return $this->shellName;
    }

    public function getShellId(): string
    {
        return $this->shellId;
    }

    public function senderId(): string
    {
        return $this->senderId;
    }

    public function getSessionId(): ? string
    {
        return $this->sessionId;
    }


    public function toGhostInput(
        string $cloneId = null,
        string $sessionId = null,
        string $guestId = null
    ): GhostInput
    {
        // todo
    }


}