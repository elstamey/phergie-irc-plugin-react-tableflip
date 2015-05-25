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
class Plugin extends AbstractPlugin
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
        //https://en.wikipedia.org/wiki/Transformation_of_text#Upside-down_text
        $this->array_upside_down = array(
            'a' => '\u0250',
            'b' => '\u0071',
            'c' => '\u0254',
            'd' => '\u0070',
            'e' => '\u01dd',
            'f' => '\u025f',
            'g' => '\u0183',
            'h' => '\u0265',
            'i' => '\u0131',
            'j' => '\u027e',
            'k' => '\u029e',
            'l' => '\uA781',
            'm' => '\u026f',
            'n' => '\u0075',
            'o' => 'o',
            'p' => 'd',
            'q' => 'b',
            'r' => '\u0279',
            's' => '\u0073',
            't' => '\u0287',
            'u' => 'n',
            'v' => '\u028C',
            'w' => '\u028D',
            'x' => '\u0078',
            'y' => '\u028E',
            'z' => '\u007A',
            'A' => '\u2200',
            'B' => '\u10412',
            'C' => '\u0186',
            'D' => '\u15E1',
            'E' => '\u018E',
            'F' => '\u2132',
            'G' => '\u2141',
            'H' => '\u0048',
            'I' => '\u0049',
            'J' => '\u017F',
            'K' => '\u22CA',
            'L' => '\u2142',
            'M' => '\u0057',
            'N' => '\u004E',
            'O' => 'O',
            'P' => '\u0500',
            'Q' => '\u038C',
            'R' => '\u1D1A',
            'S' => '\u0053',
            'T' => '\u22A5',
            'U' => '\u2229',
            'V' => '\u039B',
            'W' => '\u004D',
            'X' => '\u0058',
            'Y' => '\u2144',
            'Z' => '\u005A',
            '0' => '0',
            '1' => '\u0196',
            '2' => '\u1105',
            '3' => '\u0190',
            '4' => '\u3123',
            '5' => '\u03DB',
            '6' => '9',
            '7' => '\u3125',
            '8' => '8',
            '9' => '6'
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
        $channel = $event->getSource();

        $words = $event->getCustomParams();
        $new_string = "";

        foreach ($words as $word) {
            $letters = str_split($word, 1);
            foreach ($letters as $letter) {
                $new_string = json_decode('"'.$this->array_upside_down[$letter].'"') . $new_string;
            }
            $new_string = " " . $new_string;
        }

        $new_string = "(╯°□°）╯︵ ┻━┻ " . $new_string;

        $queue->ircPrivmsg($channel, $new_string);
    }


}
