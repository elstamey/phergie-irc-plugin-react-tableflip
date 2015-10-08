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
use ReflectionMethod;
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
        $event = $this->getMockCommandEvent();
        $queue = $this->getMockEventQueue();
        $plugin = new Plugin;

        Phake::when($event)->getSource()->thenReturn('#channel1');
        Phake::when($event)->getCommand()->thenReturn('PRIVMSG');

        $plugin->handleTableflipCommand($event, $queue);
        Phake::when($event)->getCustomParams()->thenReturn(array('#channel1'));

        Phake::verify($queue, Phake::atLeast(1))->ircPrivmsg('#channel1', $this->isType('string'));
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
     * Tests private function getFlippedTable()
     */
    public function testGetFlippedTable()
    {
        // We have to set the private method accessible
        $method = new ReflectionMethod(
            'Phergie\Irc\Plugin\React\TableFlip\Plugin', 'getFlippedTable'
        );
        $method->setAccessible(TRUE);

        // Test our Method
        $this->assertEquals(
            '(╯°□°）╯︵ ┻━┻ ', $method->invoke(new Plugin)
        );
    }

    /**
     * Tests private function getFlippedTable()
     */
    public function testGetFlippedWords()
    {
        // We have to set the private method accessible
        $method = new ReflectionMethod(
            'Phergie\Irc\Plugin\React\TableFlip\Plugin', 'getFlippedWords'
        );
        $method->setAccessible(TRUE);

        // Test our Method with a sentence
        $this->assertEquals(
            '(╯°□°）╯︵ ┻━┻  ʍou ʇɥƃıɹ ʇǝʞɔıɹɔ ɐ ǝʇɐ ʇsnɾ ʎꞁꞁɐɹǝʇıꞁ I',
            $method->invoke(new Plugin(), array('I','literally','just','ate','a','cricket','right','now'))
        );

        // Test our Method with a single word
        $this->assertEquals(
            '(╯°□°）╯︵ ┻━┻  ʍou',
            $method->invoke(new Plugin(), array('now'))
        );
    }

    public function testExclamation()
    {
        // We have to set the private method accessible
        $method = new ReflectionMethod(
            'Phergie\Irc\Plugin\React\TableFlip\Plugin', 'getFlippedWords'
        );
        $method->setAccessible(TRUE);

        // Test our Method with a sentence
        $this->assertEquals(
            '(╯°□°）╯︵ ┻━┻  ¡ʍou ʇɥƃıɹ ʇǝʞɔıɹɔ ɐ ǝʇɐ ʇsnɾ ʎꞁꞁɐɹǝʇıꞁ I',
            $method->invoke(new Plugin(), array('I','literally','just','ate','a','cricket','right','now!'))
        );
    }

    public function testUnderscore()
    {
        // We have to set the private method accessible
        $method = new ReflectionMethod(
            'Phergie\Irc\Plugin\React\TableFlip\Plugin', 'getFlippedWords'
        );
        $method->setAccessible(TRUE);

        // Test our Method with a sentence
        $this->assertEquals(
            '(╯°□°）╯︵ ┻━┻  dıꞁɟǝꞁqɐʇ‾uıƃnꞁd',
            $method->invoke(new Plugin(), array('plugin_tableflip'))
        );
    }

    public function testAmpersand()
    {
        // We have to set the private method accessible
        $method = new ReflectionMethod(
            'Phergie\Irc\Plugin\React\TableFlip\Plugin', 'getFlippedWords'
        );
        $method->setAccessible(TRUE);

        // Test our Method with a sentence
        $this->assertEquals(
            '(╯°□°）╯︵ ┻━┻  ɯ⅋ɯ',
            $method->invoke(new Plugin(), array('m&m'))
        );
    }

    public function testQuestionMark()
    {
        // We have to set the private method accessible
        $method = new ReflectionMethod(
            'Phergie\Irc\Plugin\React\TableFlip\Plugin', 'getFlippedWords'
        );
        $method->setAccessible(TRUE);

        // Test our Method with a sentence
        $this->assertEquals(
            '(╯°□°）╯︵ ┻━┻  ¿oꞁꞁǝɥ',
            $method->invoke(new Plugin(), array('hello?'))
        );
    }

    public function testUnexpectedCharacters()
    {
        // We have to set the private method accessible
        $method = new ReflectionMethod(
            'Phergie\Irc\Plugin\React\TableFlip\Plugin', 'getFlippedWords'
        );
        $method->setAccessible(TRUE);

        // Test our Method with a sentence
        $this->assertEquals(
            '(╯°□°）╯︵ ┻━┻  @ # $ ^ + - = *',
            $method->invoke(new Plugin(), array('*','=','-','+','^','$','#','@'))
        );
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
