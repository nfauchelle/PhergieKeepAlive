# PhergieKeepAlive

[Phergie](http://github.com/phergie/phergie-irc-bot-react/) plugin which monitors when the last activity on a connection was and if it exceeds a certain time limit the correction is closed and re-created. Server ping/pongs are included, so in a slow chat room there would stil be enough activity and the only time the timeout trigged is due to a network issue.

[![Build Status](https://secure.travis-ci.org/nfauchelle/PhergieKeepAlive.png?branch=master)](http://travis-ci.org/nfauchelle/PhergieKeepAlive)

## Install

The recommended method of installation is [through composer](http://getcomposer.org).

```JSON
{
    "require": {
        "nfauchelle\PhergieKeepAlive": "dev-master"
    }
}
```

See Phergie documentation for more information on
[installing and enabling plugins](https://github.com/phergie/phergie-irc-bot-react/wiki/Usage#plugins).

## Configuration

```php
new \Phergie\Irc\Plugin\React\KeepAlive\Plugin(array(
    // Seconds of inactivity before attmping reconnect
    'timeout' => 600
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
