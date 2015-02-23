<?php
/**
 * Phergie plugin for Monitors when the last activity on a connection was and if it exceeds a certain time limit the correction is closed and re-created (https://github.com/nfauchelle/PhergieKeepAlive)
 *
 * @link https://github.com/nfauchelle/PhergieKeepAlive for the canonical source repository
 * @copyright Copyright (c) 2015 Nick (https://github.com/nfauchelle/PhergieKeepAlive)
 * @license http://phergie.org/license New BSD License
 * @package nfauchelle\Phergie\KeepAlive\Plugin
 */

namespace Phergie\Irc\Tests\Plugin\React\KeepAlive;

use Phake;
use Phergie\Irc\Bot\React\EventQueueInterface as Queue;
use Phergie\Irc\Event\EventInterface as Event;
use Phergie\Irc\Plugin\React\KeepAlive\Plugin;

/**
 * Tests for the Plugin class.
 *
 * @category Phergie
 * @package Phergie\Irc\Plugin\React\KeepAlive
 */
class PluginTest extends \PHPUnit_Framework_TestCase
{


    /**
     * Tests that getSubscribedEvents() returns an array.
     */
    public function testGetSubscribedEvents()
    {
        $plugin = new Plugin;
        $this->assertInternalType('array', $plugin->getSubscribedEvents());
    }
}
