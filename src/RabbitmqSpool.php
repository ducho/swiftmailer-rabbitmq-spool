<?php


namespace Enl\Swiftmailer;

use AmqpWorkers\Producer;
use Swift_Mime_Message;
use Swift_Transport;

class RabbitmqSpool implements \Swift_Spool
{

    /**
     * @var Producer
     */
    private $producer;

    public function __construct(Producer $producer)
    {
        $this->producer = $producer->withFormatter('serialize');
    }

    /**
     * Starts this Spool mechanism.
     */
    public function start()
    {
        return $this->producer->selfCheck();
    }

    /**
     * Stops this Spool mechanism.
     */
    public function stop()
    {
        // Nothing to do here
    }

    /**
     * Tests if this Spool mechanism has started.
     *
     * @return bool
     */
    public function isStarted()
    {
        return true;
    }

    /**
     * Queues a message.
     *
     * @param Swift_Mime_Message $message The message to store
     *
     * @return bool Whether the operation has succeeded
     */
    public function queueMessage(Swift_Mime_Message $message)
    {
        try {
            $this->producer->produce($message);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Sends messages using the given transport instance.
     *
     * @param Swift_Transport $transport A transport instance
     * @param string[] $failedRecipients An array of failures by-reference
     *
     * @return int The number of sent emails
     */
    public function flushQueue(Swift_Transport $transport, &$failedRecipients = null)
    {
        throw new \LogicException('RabbitmqSpool::flushQueue() is not implemented.');
    }
}
