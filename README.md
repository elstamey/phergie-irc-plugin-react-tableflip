# phergie/phergie-irc-plugin-react-tableflip

[Phergie](http://github.com/phergie/phergie-irc-bot-react/) plugin for Outputting an overturned table and the upside down translation of a word or phrase submitted.

[![Build Status](https://secure.travis-ci.org/phergie/phergie-irc-plugin-react-tableflip.png?branch=master)](http://travis-ci.org/phergie/phergie-irc-plugin-react-tableflip)

## Install

The recommended method of installation is [through composer](http://getcomposer.org).

```JSON
{
    "require": {
        "phergie/phergie-irc-plugin-react-tableflip": "dev-master"
    }
}
```

See Phergie documentation for more information on
[installing and enabling plugins](https://github.com/phergie/phergie-irc-bot-react/wiki/Usage#plugins).

## Configuration


```php
return [
    'plugins' => [
        // configuration
        new \Phergie\Irc\Plugin\React\TableFlip\Plugin([



        ])
    ]
];
```

## Tests

To run the unit test suite:

```
curl -s https://getcomposer.org/installer | php
php composer.phar install
./vendor/bin/phpunit
```

## License

Released under the BSD License. See `LICENSE`.
