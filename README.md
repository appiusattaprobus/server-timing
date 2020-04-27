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

```php
StopWatch::start('Task 1');

sleep(1); // do something

StopWatch::stop('Task 1'); 
StopWatch::setTimingHeader();
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
