<?php
/**
 * Phergie plugin for Monitors when the last activity on a connection was and if it exceeds a certain time limit the correction is closed and re-created (https://github.com/nfauchelle/PhergieKeepAlive)
 *
 * @link https://github.com/nfauchelle/PhergieKeepAlive for the canonical source repository
 * @copyright Copyright (c) 2015 Nick (https://github.com/nfauchelle/PhergieKeepAlive)
 * @license http://phergie.org/license New BSD License
 * @package Phergie\Irc\Plugin\React\KeepAlive
 */

namespace Phergie\Irc\Plugin\React\KeepAlive;

use Phergie\Irc\Event\EventInterface as Event;
use Phergie\Irc\Bot\React\EventQueueInterface as Queue;
use Phergie\Irc\Bot\React\AbstractPlugin;
use Phergie\Irc\ConnectionInterface;
use Phergie\Irc\Client\React\WriteStream;
use \Psr\Log\LoggerInterface;

/**
 * Plugin class.
 *
 * @category Phergie
 * @package Phergie\Irc\Plugin\React\KeepAlive
 */
class Plugin extends AbstractPlugin
{

    public $lastActivity = array();
    public $timeout = 600;

    public function getSubscribedEvents()
    {
        return array(
            'irc.received.each' => 'onActivity',
            'irc.tick' => 'onTick',
            'connect.end' => 'handleReconnect'
        );
    }

    /**
     * Ability to change the default timeout via config array
     *
     * @param array $config
     */
    public function __construct(array $config = array())
    {
        if (isset($config['timeout'])) {
            $this->timeout = $config['timeout'];
        }
    }

    /**
     * Attempt to reconnect to the server which we just disconnected from
     *
     * @param ConnectionInterface $connection
     * @param LoggerInterface $logger
     */
    public function handleReconnect(ConnectionInterface $connection, LoggerInterface $logger)
    {
        $mask = $this->getConnectionMask($connection);
        // We unset the mask here so we can get the updated event queue when the activity function is hit
        unset($this->lastActivity[$mask]);

        $logger->debug('Attemping reconnect: ' . $mask);

        $client = $this->getClient();
        $client->addConnection($connection);
    }

    /**
     * Whenever we get any activity track the current time. If this is a new mask then also save a reference
     * to the event queue so we can easily call quit later if needed.
     *
     * @param Event $event
     * @param Queue $queue
     */
    public function onActivity(Event $event, Queue $queue)
    {
        $mask = $this->getConnectionMask($event->getConnection());
        if (!isset($this->lastActivity[$mask])) {
            $this->logger->debug('Added connection ' . $mask);
            $this->lastActivity[$mask] = ['queue' => $queue, 'last_time' => time()];
        }
        $this->lastActivity[$mask]['last_time'] = time();
    }

    /**
     * Returns the connection mask for a given connection.
     *
     * @param \Phergie\Irc\ConnectionInterface $connection
     * @return string
     */
    protected function getConnectionMask(ConnectionInterface $connection)
    {
        return sprintf('%s!%s@%s',
            $connection->getNickname(),
            $connection->getUsername(),
            $connection->getServerHostname()
        );
    }

    /**
     * Check the list of known connection masks and if we have reacted the timeout trigger a quit
     *
     * @param WriteStream $write
     * @param ConnectionInterface $connection
     * @param LoggerInterface $logger
     */
    public function onTick(WriteStream $write, ConnectionInterface $connection, LoggerInterface $logger)
    {
        foreach ($this->lastActivity as $mask => &$details) {
            if ((time() - $details['last_time']) > $this->timeout) {
                $logger->debug('Resetting connection, timeout reached!');
                $details['queue']->ircQuit('Master has killed me!');
                // Update the last_time to prevent this mask from being clobbered
                // But we can't unset the mask, since the quit event is yet to return
                $details['last_time'] = time();
            }
        }
    }
}
