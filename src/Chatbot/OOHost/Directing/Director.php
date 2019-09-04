<?php


namespace Commune\Chatbot\OOHost\Directing;


use Commune\Chatbot\Blueprint\Conversation\RunningSpy;
use Commune\Chatbot\Framework\Conversation\RunningSpyTrait;
use Commune\Chatbot\Framework\Exceptions\LogicException;
use Commune\Chatbot\OOHost\Directing\Backward\Failure;
use Commune\Chatbot\OOHost\Directing\Backward\Quit;
use Commune\Chatbot\OOHost\Directing\Dialog\MissMatch;
use Commune\Chatbot\OOHost\Exceptions\DataNotFoundException;
use Commune\Chatbot\OOHost\Exceptions\NavigatorException;
use Commune\Chatbot\OOHost\Exceptions\TooManyRedirectException;
use Commune\Chatbot\OOHost\Session\Session;
use Commune\Chatbot\OOHost\Session\SessionImpl;

class Director implements RunningSpy
{
    use RunningSpyTrait;

    /**
     * @var SessionImpl
     */
    protected $session;

    /**
     * @var int
     */
    protected $ticks = 0;

    /**
     * @var int
     */
    protected $maxTicks;

    /**
     * @var bool
     */
    protected $heard = true;

    /**
     * @var string
     */
    protected $sessionId;

    /**
     * Director constructor.
     * @param Session $session
     */
    public function __construct(Session $session)
    {
        $this->session = $session;
        $this->sessionId = $session->sessionId;
        $this->maxTicks = $this->session->hostConfig->maxRedirectTimes;
        static::addRunningTrace($this->sessionId, $this->sessionId);
    }


    protected function tick() : void
    {
        $this->ticks ++;
        if ($this->ticks > $this->maxTicks) {
            throw new TooManyRedirectException(
                static::class
                . ' ticks '. $this->ticks
                . ' times more than max times '
                . $this->maxTicks
                . ', tracking:'
                . $this->session->getHistory()->tracker
            );
        }
    }

    /**
     * @param Navigator $navigator
     * @return bool
     */
    public function hear(Navigator $navigator) : bool
    {
        do {
            $this->tick();

            try {

                // 退出的逻辑
                if ($navigator instanceof Quit) {
                    $this->tick();
                    $navigator->display();
                    return $this->heard;
                }

                if ($navigator instanceof MissMatch) {
                    $this->heard = false;
                }

                $navigator = $navigator->display();

            // 一种特殊的异常, 用于跳脱流程.
            } catch (NavigatorException $e) {
                $navigator = $e->getNavigator();

            // 预定义的逻辑错误
            } catch (TooManyRedirectException $e) {
                $this->session->getLogger()->error($e);
                return $this->destroy();

            // 预定义的逻辑错误
            } catch (DataNotFoundException $e) {
                $this->session->getLogger()->error($e);
                return $this->destroy();

            // 逻辑错误是可以接受的. 不会导致断开连接.
            } catch (LogicException $e) {

                $this->session->getLogger()->warning($e);
                $navigator = new Failure(
                    $this->session->dialog,
                    $this->session->getHistory()
                );
            }

        // 其它的异常不会捕获.

        } while (isset($navigator));

        return $this->heard;
    }

    protected function destroy() : bool
    {
        $this->session
            ->conversation
            ->getSpeech()
            ->error(
                $this->session
                    ->chatbotConfig
                    ->defaultMessages
                    ->systemError
            );
        $this->session->shouldQuit();
        return false;
    }

    public function __destruct()
    {
        static::removeRunningTrace($this->sessionId);
    }


}