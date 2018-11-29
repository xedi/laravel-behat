# behat-laravel
Behat Extension compatible with both Laravel and Lumen

---

## Getting Started

### Installation

Pull in the extension using composer

```bash
composer require xedi/behat-laravel --dev
```

You will also probably need `behat/mink-extension`:

```bash
composer require behat/behat behat/mink-extension --dev
```

:information_source: **If you are using Lumen you will need to register the ServiceProvider yourself for access to the Artisan commands.**

```php
// bootstrap/app.php
if (class_exists("Xedi\\Behat\\ServiceProvider")) {
    $app->register("Xedi\\Behat\\ServiceProvider");
}
```

### Setup

Next you need to configure behat.

```bash
php artisan make:behat-yaml
```

It is recommended that you use a `.env.behat` environment file and set the `LOG_DRIVER` to `single`.

Then, you need to initialize a behat within your repository.

```bash
vendor/bin/behat --init
```

If everything is working, it will create a "features" directory within your repository.

### Running the tests

To run the test run behat as normal.

```bash
vendor/bin/behat
```

Alternatively, you can run individual files:

```bash
vendor/bin/behat -- features/Example.feature
```

You can also run specific Scenarios by specifying the line number it begins on:

```bash
vendor/bin/behat -- features/Example.feature:54
```

For more information, check out the help documentation using the `--help` option
