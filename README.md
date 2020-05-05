# Server Timing

Add Server-Timing header information from your apps.

## Installation

You can install the package via composer:

```bash
composer require appiusattaprobus/server-timing
```

## Usage

```php
use Appiusattaprobus\ServerTiming\StopWatch;
```

### Whole block
```php
StopWatch::start('Task 1');

sleep(1); // do something

StopWatch::stop('Task 1');  // Not necessary. At set headers all timers stop
StopWatch::setTimingHeader();
```

![server-timing](https://user-images.githubusercontent.com/2347368/80393363-960e7780-88da-11ea-92e9-63d7fb1aaecd.jpg)

### Closure
```php
$closureTimer = StopWatch::measureFunc('Closure', function () {
    usleep(500000);
});
```

### By parts
```php
StopWatch::start('Part');
sleep(1);
StopWatch::pause('Part');
sleep(3);
StopWatch::resume('Part');
sleep(1);
StopWatch::stop('Part');
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
