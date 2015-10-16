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
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        // https://en.wikipedia.org/wiki/Transformation_of_text#Upside-down_text
        // http://www.fileformat.info/convert/text/upside-down-map.htm
        $this->array_upside_down = [
            'a' => '0250',
            'b' => '0071',
            'c' => '0254',
            'd' => '0070',
            'e' => '01dd',
            'f' => '025f',
            'g' => '0183',
            'h' => '0265',
            'i' => '0131',
            'j' => '027e',
            'k' => '029e',
            'l' => 'A781',
            'm' => '026f',
            'n' => '0075',
            'o' => '006F',
            'p' => '0064',
            'q' => '0062',
            'r' => '0279',
            's' => '0073',
            't' => '0287',
            'u' => '006E',
            'v' => '028C',
            'w' => '028D',
            'x' => '0078',
            'y' => '028E',
            'z' => '007A',
            'A' => '2200',
            'B' => '10412',
            'C' => '0186',
            'D' => '15E1',
            'E' => '018E',
            'F' => '2132',
            'G' => '2141',
            'H' => '0048',
            'I' => '0049',
            'J' => '017F',
            'K' => '22CA',
            'L' => '2142',
            'M' => '0057',
            'N' => '004E',
            'O' => '004F',
            'P' => '0500',
            'Q' => '038C',
            'R' => '1D1A',
            'S' => '0053',
            'T' => '22A5',
            'U' => '2229',
            'V' => '039B',
            'W' => '004D',
            'X' => '0058',
            'Y' => '2144',
            'Z' => '005A',
            '0' => '0030',
            '1' => '0196',
            '2' => '1105',
            '3' => '0190',
            '4' => '3123',
            '5' => '03DB',
            '6' => '0039',
            '7' => '3125',
            '8' => '0038',
            '9' => '0036'
        ];
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
        $this->sendHelpReply($event, $queue, [
            'Usage: tableflip message',
            'message - the message to flip and then send (all words after this are assumed to be part of message)',
            'Instructs the bot to return a flipped table and the inverted words in the message.',
        ]);
    }

    /**
     * @param string $words
     *
     * @return string
     */
    private function getFlippedWords($words)
    {
        $flippedString = "";

        foreach ($words as $word) {
            $letters = str_split($word, 1);
            foreach ($letters as $letter) {
                if (array_key_exists($letter, $this->array_upside_down)) {
                    $flippedString = $this->hexToChar( $this->array_upside_down[$letter] ) . $flippedString;
                } else {
                    $flippedString = $this->specialChar($letter) . $flippedString;
                }
            }
            $flippedString = " " . $flippedString;
        }

        $flippedString = $this->getFlippedTable() . $flippedString;

        return $flippedString;
    }

    /**
     * @return string
     */
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

    /**
     * This is code taken directly from a Stack Overflow answer, so leaving the naming and everything consistent
     * http://stackoverflow.com/questions/17539412/print-unicode-characters-php
     *
     * @param string $cp
     *
     * @return string
     */
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

    /**
     * Switch statement to flip the special and punctuation characters
     *
     * @param string $char
     *
     * @return string
     */
    private function specialChar($char)
    {
        switch($char) {
            case "!":
                return $this->hexToChar('00A1');
            case "_":
                return $this->hexToChar('203E');
            case "&":
                return $this->hexToChar('214B');
            case "?":
                return $this->hexToChar('00BF');
            case ".":
                return $this->hexToChar('U2D9');
            case "\"":
                return $this->hexToChar('201E');
            case "'":
                return $this->hexToChar('002C');
            case "(":
                return $this->hexToChar('0029');
            case ")":
                return $this->hexToChar('0028');
            default:
                return $char;
        }

    }

    /**
     * @param string $char
     *
     * @return string
     */
    private function hexToChar($char)
    {
        return $this->utf8_chr(hexdec($char));
    }
}
