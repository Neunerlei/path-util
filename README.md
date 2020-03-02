# File Path Utility (FORKED)

This package provides robust, cross-platform utility functions for normalizing,
comparing and modifying file paths and URLs.

**ATTENTION: This is a fork of [path-util](https://github.com/webmozart/path-util) by [webmozart](https://github.com/webmozart)!**

```PHP >= 7.3.0```

## What's different?

- Removed "Url" utility and replaced it with [URI by PHP League](https://uri.thephpleague.com/)
- Added new methods to the Path class
- Added tests for the new methods
- Updated PHP dependency to 7.3.0
- Updated existing code to use PHP Type Hints
- Updated tests to work with the modified code (Mostly to support the Type Hints)
- Removed dependency on "webmozart/assert"
- The Path class is no longer a "final" class
- All private methods of the Path class are now "protected"
- Removed build related files

## Installation

The utility can be installed with [Composer]:

```
$ composer require neunerlei/path-util
```

## Basic Usage
Use the `Path` class to handle file paths:

```php
use Neunerlei\PathUtil\Path;

// These methods are added by this fork
// ==========================================================
echo Path::unifySlashes("\\foo/bar\\baz");
// => /foo/bar/baz (on linux) or \foo\bar\baz (on windows)

echo Path::unifyPath("\\foo/bar\\baz");
// => /foo/bar/baz/ (on linux) or \foo\bar\baz\ (on windows)

echo Path::classBasename(\Neunerlei\PathUtil\Path::class);
// => Path

echo Path::classNamespace(\Neunerlei\PathUtil\Path::class);
// => Neunerlei\PathUtil

$link = Path::makeUri();
// => Returns a new Uri object -> See "URI" Section for details.

// Those methods were already in the base implementation
// ==========================================================
echo Path::canonicalize('/var/www/vhost/webmozart/../config.ini');
// => /var/www/vhost/config.ini

echo Path::canonicalize('C:\Programs\Webmozart\..\config.ini');
// => C:/Programs/config.ini

echo Path::canonicalize('~/config.ini');
// => /home/webmozart/config.ini

echo Path::makeAbsolute('config/config.yml', '/var/www/project');
// => /var/www/project/config/config.yml

echo Path::makeRelative('/var/www/project/config/config.yml', '/var/www/project/uploads');
// => ../config/config.yml

$paths = array(
    '/var/www/vhosts/project/httpdocs/config/config.yml',
    '/var/www/vhosts/project/httpdocs/images/banana.gif',
    '/var/www/vhosts/project/httpdocs/uploads/../images/nicer-banana.gif',
);

Path::getLongestCommonBasePath($paths);
// => /var/www/vhosts/project/httpdocs

Path::getFilename('/views/index.html.twig');
// => index.html.twig

Path::getFilenameWithoutExtension('/views/index.html.twig');
// => index.html

Path::getFilenameWithoutExtension('/views/index.html.twig', 'html.twig');
Path::getFilenameWithoutExtension('/views/index.html.twig', '.html.twig');
// => index

Path::getExtension('/views/index.html.twig');
// => twig

Path::hasExtension('/views/index.html.twig');
// => true

Path::hasExtension('/views/index.html.twig', 'twig');
// => true

Path::hasExtension('/images/profile.jpg', array('jpg', 'png', 'gif'));
// => true

Path::changeExtension('/images/profile.jpeg', 'jpg');
// => /images/profile.jpg

Path::join('phar://C:/Documents', 'projects/my-project.phar', 'composer.json');
// => phar://C:/Documents/projects/my-project.phar/composer.json

Path::getHomeDirectory();
// => /home/webmozart
```

## URI handling
When using the ```Path::makeLink()``` method you can supply a single parameter that will be used as a base for the generated link object. 
Possible parameter options are:

- string: A url represented by a string
- instance: An object that implements \League\Uri\Contracts\UriInterface or Psr\Http\Message\UriInterface
- TRUE: Let the script figure out the url based on the current $_SERVER super-global
- array: The result of parse_url() or a similarly constructed array

If no parameter is given an empty Uri object is returned. The method uses the [League\Uri](https://uri.thephpleague.com/uri/6.0/) package under the hood.
The result of the method is an instance of a [PSR-7](https://www.php-fig.org/psr/psr-7/) compliant URI object.

## Base Package documentation
Learn more about the forked **base package** in the [Documentation]. 

## Authors

* [Bernhard Schussek] a.k.a. [@webmozart]
* [The Community Contributors]
* [Martin Neundorfer](https://www.neunerlei.eu) 

## Contribute

Contributions are always welcome!

* Report any bugs or issues you find on the [issue tracker].
* You can grab the source code at the [Git repository].

## License

All contents of this package are licensed under the [MIT license].

## Special Thanks
Special thanks goes to the folks at [LABOR.digital](https://labor.digital/) (which is the word german for laboratory and not the english "work" :D) for making it possible to publish my code online.

## Postcardware
You're free to use this package, but if it makes it to your production environment I highly appreciate you sending me a postcard from your hometown, mentioning which of our package(s) you are using.

You can find my address [here](https://www.neunerlei.eu/). 

Thank you :D 


[Bernhard Schussek]: http://webmozarts.com
[The Community Contributors]: https://github.com/neunerlei/path-util/graphs/contributors
[Composer]: https://getcomposer.org
[API Docs]: https://webmozart.github.io/path-util/api/latest/class-Webmozart.PathUtil.Path.html
[Documentation]: https://github.com/webmozart/path-util/docs/usage.md
[issue tracker]: https://github.com/neunerlei/path-util/issues
[Git repository]: https://github.com/neunerlei/path-util
[@webmozart]: https://twitter.com/webmozart
[MIT license]: LICENSE
