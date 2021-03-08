# Magic Test for Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/magic-test/magic-test-laravel.svg?style=flat-square)](https://packagist.org/packages/magic-test/magic-test-laravel)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/magic-test/magic-test-laravel/run-tests?label=tests)](https://github.com/magic-test/magic-test-laravel/actions?query=workflow%3ATests+branch%3Amaster)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/magic-test/magic-test-laravel/Check%20&%20fix%20styling?label=code%20style)](https://github.com/magic-test/magic-test-laravel/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amaster)
[![Total Downloads](https://img.shields.io/packagist/dt/magic-test/magic-test-laravel.svg?style=flat-square)](https://packagist.org/packages/magic-test/magic-test-laravel)

Magic Test allows you to write browser tests by simply clicking around on the application being tested, all without the slowness of constantly restarting the testing environment.  
It inverts the test-writing experience and avoids all the back and forth between tests, your terminal and your template files. See it in action here.

Magic Test was originally created by [Andrew Culver](http://twitter.com/andrewculver) and [Adam Pallozi](https://twitter.com/adampallozzi) for Ruby on Rails.   
Laravel Magic Test was created by [Mateus Guimarães](https://twitter.com/mateusjatenee).  

> Magic Test is still in early development, and that includes the documentation. Any questions you have that aren't already address in the documentation should be opened as issues so they can be appropriately addressed in the documentation.

## Installation

You can install the package via composer:

```bash
composer require magic-test/magic-test-laravel
```

Then, add the following line to your `$middleware` array under `app/Http/Kernel.php`:   

```php
\MagicTest\MagicTest\Middleware\MagicTestMiddleware::class
```  

## Usage   
On your Laravel Dusk tests, simply add `magic()` at the end of your method chain. For example:  

```php
    public function testBasicExample()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                    ->magic();
        });
    }
```    

To run Magic Test, you must simply run the command `php artisan magic`. Behind the scenes, it is the same as running `php artisan dusk`, but it will maintain the browser window open.  

This will leave you with two or three windows:  
- The browser
- An interactive Shell
- Your text editor if you had it open    

For the Magic Experience™️, we suggest you organize the three windows to fit your screen. That way, you can see tests being generated in real-time.

## Recording Actions  
Once the browser is open, Magic Test will already be capturing all of your actions. You can click around, fill inputs, checkboxes, selects and radios just like you would do manually testing an application.   

## Generating Assertions  
Additionally, you can generate text assertions by selecting a given text and then pressing <kbd>Control</kbd><kbd>Shift</kbd> + <kbd>A</kbd>. You'll see a dialog box confirming the assertion has been recorded.  

## Saving the new actions to the test file   
To save the actions that were recorded, simply go to the Shell and type `ok`. You are free to close it and come back to your Magic Sessiona any time, or just keep recording more actions.  
If you're satisfied with your test, you can type `finish` on the Shell and it'll remove the `magic()` call from your test, leaving you with a clean, working test.  

Magic Test is still in it's early days, so you might find that the output is not exactly what you wanted. In that case, [feel free to submit an issue](https://github.com/magic-test/magic-test-laravel/issues/new) and we'll try to improve it ASAP.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Mateus Guimarães](https://twitter.com/mateusjatenee)
- [Andrew Culver](http://twitter.com/andrewculver)
- [Adam Pallozzi](https://twitter.com/adampallozzi)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
