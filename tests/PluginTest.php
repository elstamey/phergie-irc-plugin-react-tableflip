<?php
/**
 * Phergie plugin for Outputting an overturned table and the upside down translation of a word or phrase submitted
 * (elstamey/phergie-irc-plugin-react-table-flip)
 *
 * @link https://github.com/phergie/phergie-irc-plugin-react-tableflip for the canonical source repository
 * @copyright Copyright (c) 2015 Emily Stamey (http://phergie.org)
 * @license http://phergie.org/license Simplified BSD License
 * @package Phergie\Irc\Plugin\React\TableFlip
 */

namespace Phergie\Irc\Tests\Plugin\React\TableFlip;

use Phake;
use Phergie\Irc\Bot\React\EventQueueInterface as Queue;
use Phergie\Irc\Plugin\React\Command\CommandEvent as Event;
use Phergie\Irc\Plugin\React\TableFlip\Plugin;

/**
 * Tests for the Plugin class.
 *
 * @category Phergie
 * @package Phergie\Irc\Plugin\React\TableFlip
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

    /**
    * Tests handleTableflipCommand().
    */
    public function testHandleTableflipCommand()
    {
        $channels = "#channel1,#channel2";
        $plugin = new Plugin;
        $event = $this->getMockCommandEvent();
        $queue = $this->getMockEventQueue();

        Phake::when($event)->getSource()->thenReturn('#channel1');
        Phake::when($event)->getCommand()->thenReturn('PRIVMSG');

        $plugin->handleTableflipCommand($event, $queue);
        Phake::when($event)->getCustomParams()->thenReturn(array($channels));

        $plugin->handleTableflipCommand($event, $queue);
        Phake::when($event)->getCustomParams()->thenReturn(array($channels,'!tableflip','I','literally','just','ate','a','cricket','right','now'));

        Phake::inOrder(
            Phake::verify($queue, Phake::atLeast(1))->ircPrivmsg('#channel1', $this->isType('string')),
            Phake::verify($queue)->ircPrivmsg('#channel1', '(╯°□°）╯︵ ┻━┻  ʍou ʇɥƃıɹ ʇǝʞɔıɹɔ ɐ ǝʇɐ ʇsnɾ ʎꞁꞁɐɹǝʇıꞁ I')
        );

    }

    /**
     * Tests handleTableflipHelp().
     *
     * @param string $method
     * @dataProvider dataProviderHandleHelp
     */
    public function testHandleTableflipHelp($method)
    {
        $event = $this->getMockCommandEvent();

        Phake::when($event)->getCustomParams()->thenReturn(array());
        Phake::when($event)->getSource()->thenReturn('#channel');
        Phake::when($event)->getCommand()->thenReturn('PRIVMSG');
        $queue = $this->getMockEventQueue();

        $plugin = new Plugin;
        $plugin->$method($event, $queue);

        Phake::verify($queue, Phake::atLeast(1))
            ->ircPrivmsg('#channel', $this->isType('string'));
    }

    /**
     * Data provider for testHandleHelp().
     *
     * @return array
     */
    public function dataProviderHandleHelp()
    {
        $data = array();
        $methods = array(
            'handleTableflipHelp',
        );
        foreach ($methods as $method) {
            $data[] = array($method);
        }
        return $data;
    }

    /**
     * Returns a mock command event
     *
     * @return \Phergie\Irc\Plugin\React\Command\CommandEvent
     */
    private function getMockCommandEvent()
    {
        return Phake::mock('Phergie\Irc\Plugin\React\Command\CommandEvent');
    }

    /**
     * Returns a mock event queue.
     *
     * @return \Phergie\Irc\Bot\React\EventQueueInterface
     */
    protected function getMockEventQueue()
    {
        return Phake::mock('Phergie\Irc\Bot\React\EventQueueInterface');
    }
}
