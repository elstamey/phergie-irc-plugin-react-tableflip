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
        // https://en.wikipedia.org/wiki/Transformation_of_text#Upside-down_text
        // http://www.fileformat.info/convert/text/upside-down-map.htm
        $this->array_upside_down = array(
            'a' => hexdec('0250'),
            'b' => hexdec('0071'),
            'c' => hexdec('0254'),
            'd' => hexdec('0070'),
            'e' => hexdec('01dd'),
            'f' => hexdec('025f'),
            'g' => hexdec('0183'),
            'h' => hexdec('0265'),
            'i' => hexdec('0131'),
            'j' => hexdec('027e'),
            'k' => hexdec('029e'),
            'l' => hexdec('A781'),
            'm' => hexdec('026f'),
            'n' => hexdec('0075'),
            'o' => hexdec('006F'),
            'p' => hexdec('0064'),
            'q' => hexdec('0062'),
            'r' => hexdec('0279'),
            's' => hexdec('0073'),
            't' => hexdec('0287'),
            'u' => hexdec('006E'),
            'v' => hexdec('028C'),
            'w' => hexdec('028D'),
            'x' => hexdec('0078'),
            'y' => hexdec('028E'),
            'z' => hexdec('007A'),
            'A' => hexdec('2200'),
            'B' => hexdec('10412'),
            'C' => hexdec('0186'),
            'D' => hexdec('15E1'),
            'E' => hexdec('018E'),
            'F' => hexdec('2132'),
            'G' => hexdec('2141'),
            'H' => hexdec('0048'),
            'I' => hexdec('0049'),
            'J' => hexdec('017F'),
            'K' => hexdec('22CA'),
            'L' => hexdec('2142'),
            'M' => hexdec('0057'),
            'N' => hexdec('004E'),
            'O' => hexdec('004F'),
            'P' => hexdec('0500'),
            'Q' => hexdec('038C'),
            'R' => hexdec('1D1A'),
            'S' => hexdec('0053'),
            'T' => hexdec('22A5'),
            'U' => hexdec('2229'),
            'V' => hexdec('039B'),
            'W' => hexdec('004D'),
            'X' => hexdec('0058'),
            'Y' => hexdec('2144'),
            'Z' => hexdec('005A'),
            '0' => hexdec('0030'),
            '1' => hexdec('0196'),
            '2' => hexdec('1105'),
            '3' => hexdec('0190'),
            '4' => hexdec('3123'),
            '5' => hexdec('03DB'),
            '6' => hexdec('0039'),
            '7' => hexdec('3125'),
            '8' => hexdec('0038'),
            '9' => hexdec('0036')
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
            'command.tableflip' => 'handleTableflipCommand',
            'command.tableflip.help' => 'handleTableflipHelp',
        ];
    }

    /**
     *
     * @param \Phergie\Irc\Plugin\React\Command\CommandEvent $event
     * @param \Phergie\Irc\Bot\React\EventQueueInterface $queue
     *
     * @return string
     */
    public function handleTableflipCommand(Event $event, Queue $queue)
    {
        $channel = $event->getSource();
        $params = $event->getCustomParams();

        if (count( $params ) < 1) {
            $this->handleTableflipHelp( $event, $queue );
            $msgString = $this->getFlippedTable();
        } else {
            $msgString = $this->getFlippedWords( $event->getCustomParams() );
        }

        $queue->ircPrivmsg($channel, $msgString);
    }

    /**
     * Tableflip Command Help
     *
     * @param \Phergie\Irc\Plugin\React\Command\CommandEvent $event
     * @param \Phergie\Irc\Bot\React\EventQueueInterface $queue
     */
    public function handleTableflipHelp(Event $event, Queue $queue)
    {
        $this->sendHelpReply($event, $queue, array(
            'Usage: tableflip message',
            'message - the message to flip and then send (all words after this are assumed to be part of message)',
            'Instructs the bot to return a flipped table and the inverted words in the message.',
        ));
    }

    private function getFlippedWords($words)
    {
        $flippedString = "";

        foreach ($words as $word) {
            $letters = str_split($word, 1);
            foreach ($letters as $letter) {
                if (array_key_exists($letter, $this->array_upside_down)) {
                    $flippedString = $this->utf8_chr($this->array_upside_down[$letter]) . $flippedString;
                } else {
                    $flippedString = $this->special_char($letter) . $flippedString;
                }
            }
            $flippedString = " " . $flippedString;
        }

        $flippedString = $this->getFlippedTable() . $flippedString;

        return $flippedString;
    }

    private function getFlippedTable()
    {
        return "(╯°□°）╯︵ ┻━┻ ";
    }

    /**
     * Responds to a help command.
     *
     * @param \Phergie\Irc\Plugin\React\Command\CommandEvent $event
     * @param \Phergie\Irc\Bot\React\EventQueueInterface $queue
     * @param array $messages
     */
    protected function sendHelpReply(Event $event, Queue $queue, array $messages)
    {
        $method = 'irc' . $event->getCommand();
        $target = $event->getSource();
        foreach ($messages as $message) {
            $queue->$method($target, $message);
        }
    }

    private function utf8_chr($cp) {

        if (!is_int($cp)) {
            exit("$cp is not integer\n");
        }

        // UTF-8 prohibits characters between U+D800 and U+DFFF
        // https://tools.ietf.org/html/rfc3629#section-3
        //
        // Q: Are there any 16-bit values that are invalid?
        // http://unicode.org/faq/utf_bom.html#utf16-7

        if ($cp < 0 || (0xD7FF < $cp && $cp < 0xE000) || 0x10FFFF < $cp) {
            exit("$cp is out of range\n");
        }

        if ($cp < 0x10000) {
            return json_decode('"\u'.bin2hex(pack('n', $cp)).'"');
        }

        // Q: Isn’t there a simpler way to do this?
        // http://unicode.org/faq/utf_bom.html#utf16-4
        $lead = 0xD800 - (0x10000 >> 10) + ($cp >> 10);
        $trail = 0xDC00 + ($cp & 0x3FF);

        return json_decode('"\u'.bin2hex(pack('n', $lead)).'\u'.bin2hex(pack('n', $trail)).'"');
    }

    private function special_char($char)
    {
        switch($char) {
            case "!":
                return $this->hex_to_char('00A1');
            default:
                return $char;
        }

    }

    private function hex_to_char($char)
    {
        return $this->utf8_chr(hexdec($char));
    }
}
