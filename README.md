# PhergieKeepAlive

[Phergie](http://github.com/phergie/phergie-irc-bot-react/) plugin which monitors a connection for in-activity, and if the timeout is reached then it will attempt to reconnect.

[![Build Status](https://secure.travis-ci.org/nfauchelle/PhergieKeepAlive.png?branch=master)](http://travis-ci.org/nfauchelle/PhergieKeepAlive)

## Install

The recommended method of installation is [through composer](http://getcomposer.org).

```JSON
{
    "require": {
        "nfauchelle/phergie-keep-alive": "~1.0.0"
    }
}
```
or use the command below, it will automatically detect the latest version and bind it with `~`.

```
composer require nfauchelle/phergie-keep-alive
```


See Phergie documentation for more information on
[installing and enabling plugins](https://github.com/phergie/phergie-irc-bot-react/wiki/Usage#plugins).

## Configuration

```php
new \Phergie\Irc\Plugin\React\KeepAlive\Plugin(array(
    // Seconds of inactivity before attmping reconnect
    'timeout' => 600,
    // Quit message on reconnect
    'quitMessage' => 'Ping timeout, reconnecting...'
))
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
