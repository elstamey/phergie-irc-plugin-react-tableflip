<?php
/**
 * Phergie plugin for Outputting the upside down version of a phrase (elstamey/phergie-irc-plugin-react-table-flip)
 *
 * @link https://github.com/phergie/phergie-irc-plugin-react-tableflip for the canonical source repository
 * @copyright Copyright (c) 2015 Emily Stamey (http://phergie.org)
 * @license http://phergie.org/license Simplified BSD License
 * @package Phergie\Irc\Plugin\React\TableFlip
 */

namespace Phergie\Irc\Plugin\React\TableFlip;

use Phergie\Irc\Bot\React\AbstractPlugin;
use Phergie\Irc\Bot\React\EventQueueInterface as Queue;
use Phergie\Irc\Plugin\React\Command\CommandEvent as Event;

/**
 * Plugin class.
 *
 * @category Phergie
 * @package Phergie\Irc\Plugin\React\TableFlip
 */
class TableFlipPlugin extends AbstractPlugin
{
    private $array_upside_down;

    /**
     * Accepts plugin configuration.
     *
     * Supported keys:
     *
     *
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $this->array_upside_down = new array(
            'a' => '\u0250',
            'b' => '\u0071',
            'c' => '\u0254',
            'd' => '\u0070',
            'e' => '\u01dd',
            'f' => '\u025f',
            'g' => '\u0183',
            'h' => '',
            'i' => '',
            'j' => '',
            'k' => '',
            'l' => '',
            'm' => '',
            'n' => '',
            'o' => '',
            'p' => '',
            'q' => '',
            'r' => ''
        );
    }

    /**
     *
     *
     * @return array
     */
    public function getSubscribedEvents()
    {
        return [
            'command.tableflip' => 'handleCommand',
        ];
    }

    /**
     *
     * @param \Phergie\Irc\Plugin\React\Command\CommandEvent $event
     * @param \Phergie\Irc\Bot\React\EventQueueInterface $queue
     *
     * @return string
     */
    public function handleCommand(Event $event, Queue $queue)
    {
        $words = $event->getCustomParams();
        $new_string = "";

        foreach ($words as $word) {
            $letters = chunk_split($word, 1);
            foreach ($letters as $letter) {
                $new_string = $this->array_upside_down[$letter] . $new_string;
            }
            $new_string = " " . $new_string;
        }

        return "(╯°□°）╯︵ " . $new_string;
    }


}
