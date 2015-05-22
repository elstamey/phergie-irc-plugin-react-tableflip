<?php
/**
 * Phergie plugin for Outputting the upside down version of a phrase (elstamey/phergie-irc-plugin-react-table-flip)
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

    public function testHandleCommand()
    {

    }
}
