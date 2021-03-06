<?php
declare(strict_types=1);

/*
 * This file is part of the webmozart/path-util package.
 *
 * (c) Bernhard Schussek <bschussek@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Neunerlei\PathUtil\Tests;

use Neunerlei\PathUtil\Path;

/**
 * @since  1.0
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 * @author Thomas Schulz <mail@king2500.net>
 */
class PathTest extends \PHPUnit_Framework_TestCase {
	protected $storedEnv = [];
	
	public function setUp() {
		$this->storedEnv['HOME'] = getenv('HOME');
		$this->storedEnv['HOMEDRIVE'] = getenv('HOMEDRIVE');
		$this->storedEnv['HOMEPATH'] = getenv('HOMEPATH');
		
		putenv('HOME=/home/webmozart');
		putenv('HOMEDRIVE=');
		putenv('HOMEPATH=');
	}
	
	public function tearDown() {
		putenv('HOME=' . $this->storedEnv['HOME']);
		putenv('HOMEDRIVE=' . $this->storedEnv['HOMEDRIVE']);
		putenv('HOMEPATH=' . $this->storedEnv['HOMEPATH']);
	}
	
	public function provideCanonicalizationTests() {
		return [
			// relative paths (forward slash)
			['css/./style.css', 'css/style.css'],
			['css/../style.css', 'style.css'],
			['css/./../style.css', 'style.css'],
			['css/.././style.css', 'style.css'],
			['css/../../style.css', '../style.css'],
			['./css/style.css', 'css/style.css'],
			['../css/style.css', '../css/style.css'],
			['./../css/style.css', '../css/style.css'],
			['.././css/style.css', '../css/style.css'],
			['../../css/style.css', '../../css/style.css'],
			['', ''],
			['.', ''],
			['..', '..'],
			['./..', '..'],
			['../.', '..'],
			['../..', '../..'],
			
			// relative paths (backslash)
			['css\\.\\style.css', 'css/style.css'],
			['css\\..\\style.css', 'style.css'],
			['css\\.\\..\\style.css', 'style.css'],
			['css\\..\\.\\style.css', 'style.css'],
			['css\\..\\..\\style.css', '../style.css'],
			['.\\css\\style.css', 'css/style.css'],
			['..\\css\\style.css', '../css/style.css'],
			['.\\..\\css\\style.css', '../css/style.css'],
			['..\\.\\css\\style.css', '../css/style.css'],
			['..\\..\\css\\style.css', '../../css/style.css'],
			
			// absolute paths (forward slash, UNIX)
			['/css/style.css', '/css/style.css'],
			['/css/./style.css', '/css/style.css'],
			['/css/../style.css', '/style.css'],
			['/css/./../style.css', '/style.css'],
			['/css/.././style.css', '/style.css'],
			['/./css/style.css', '/css/style.css'],
			['/../css/style.css', '/css/style.css'],
			['/./../css/style.css', '/css/style.css'],
			['/.././css/style.css', '/css/style.css'],
			['/../../css/style.css', '/css/style.css'],
			
			// absolute paths (backslash, UNIX)
			['\\css\\style.css', '/css/style.css'],
			['\\css\\.\\style.css', '/css/style.css'],
			['\\css\\..\\style.css', '/style.css'],
			['\\css\\.\\..\\style.css', '/style.css'],
			['\\css\\..\\.\\style.css', '/style.css'],
			['\\.\\css\\style.css', '/css/style.css'],
			['\\..\\css\\style.css', '/css/style.css'],
			['\\.\\..\\css\\style.css', '/css/style.css'],
			['\\..\\.\\css\\style.css', '/css/style.css'],
			['\\..\\..\\css\\style.css', '/css/style.css'],
			
			// absolute paths (forward slash, Windows)
			['C:/css/style.css', 'C:/css/style.css'],
			['C:/css/./style.css', 'C:/css/style.css'],
			['C:/css/../style.css', 'C:/style.css'],
			['C:/css/./../style.css', 'C:/style.css'],
			['C:/css/.././style.css', 'C:/style.css'],
			['C:/./css/style.css', 'C:/css/style.css'],
			['C:/../css/style.css', 'C:/css/style.css'],
			['C:/./../css/style.css', 'C:/css/style.css'],
			['C:/.././css/style.css', 'C:/css/style.css'],
			['C:/../../css/style.css', 'C:/css/style.css'],
			
			// absolute paths (backslash, Windows)
			['C:\\css\\style.css', 'C:/css/style.css'],
			['C:\\css\\.\\style.css', 'C:/css/style.css'],
			['C:\\css\\..\\style.css', 'C:/style.css'],
			['C:\\css\\.\\..\\style.css', 'C:/style.css'],
			['C:\\css\\..\\.\\style.css', 'C:/style.css'],
			['C:\\.\\css\\style.css', 'C:/css/style.css'],
			['C:\\..\\css\\style.css', 'C:/css/style.css'],
			['C:\\.\\..\\css\\style.css', 'C:/css/style.css'],
			['C:\\..\\.\\css\\style.css', 'C:/css/style.css'],
			['C:\\..\\..\\css\\style.css', 'C:/css/style.css'],
			
			// Windows special case
			['C:', 'C:/'],
			
			// Don't change malformed path
			['C:css/style.css', 'C:css/style.css'],
			
			// absolute paths (stream, UNIX)
			['phar:///css/style.css', 'phar:///css/style.css'],
			['phar:///css/./style.css', 'phar:///css/style.css'],
			['phar:///css/../style.css', 'phar:///style.css'],
			['phar:///css/./../style.css', 'phar:///style.css'],
			['phar:///css/.././style.css', 'phar:///style.css'],
			['phar:///./css/style.css', 'phar:///css/style.css'],
			['phar:///../css/style.css', 'phar:///css/style.css'],
			['phar:///./../css/style.css', 'phar:///css/style.css'],
			['phar:///.././css/style.css', 'phar:///css/style.css'],
			['phar:///../../css/style.css', 'phar:///css/style.css'],
			
			// absolute paths (stream, Windows)
			['phar://C:/css/style.css', 'phar://C:/css/style.css'],
			['phar://C:/css/./style.css', 'phar://C:/css/style.css'],
			['phar://C:/css/../style.css', 'phar://C:/style.css'],
			['phar://C:/css/./../style.css', 'phar://C:/style.css'],
			['phar://C:/css/.././style.css', 'phar://C:/style.css'],
			['phar://C:/./css/style.css', 'phar://C:/css/style.css'],
			['phar://C:/../css/style.css', 'phar://C:/css/style.css'],
			['phar://C:/./../css/style.css', 'phar://C:/css/style.css'],
			['phar://C:/.././css/style.css', 'phar://C:/css/style.css'],
			['phar://C:/../../css/style.css', 'phar://C:/css/style.css'],
			
			// paths with "~" UNIX
			['~/css/style.css', '/home/webmozart/css/style.css'],
			['~/css/./style.css', '/home/webmozart/css/style.css'],
			['~/css/../style.css', '/home/webmozart/style.css'],
			['~/css/./../style.css', '/home/webmozart/style.css'],
			['~/css/.././style.css', '/home/webmozart/style.css'],
			['~/./css/style.css', '/home/webmozart/css/style.css'],
			['~/../css/style.css', '/home/css/style.css'],
			['~/./../css/style.css', '/home/css/style.css'],
			['~/.././css/style.css', '/home/css/style.css'],
			['~/../../css/style.css', '/css/style.css'],
		];
	}
	
	/**
	 * @dataProvider provideCanonicalizationTests
	 */
	public function testCanonicalize($path, $canonicalized) {
		$this->assertSame($canonicalized, Path::canonicalize($path));
	}
	
	/**
	 * @expectedException \TypeError
	 * @expectedExceptionMessage Argument 1 passed to Neunerlei\PathUtil\Path::canonicalize() must be of the type
	 *                           string, array given
	 */
	public function testCanonicalizeFailsIfInvalidPath() {
		Path::canonicalize([]);
	}
	
	public function provideGetDirectoryTests() {
		return [
			['/webmozart/puli/style.css', '/webmozart/puli'],
			['/webmozart/puli', '/webmozart'],
			['/webmozart', '/'],
			['/', '/'],
			['', ''],
			
			['\\webmozart\\puli\\style.css', '/webmozart/puli'],
			['\\webmozart\\puli', '/webmozart'],
			['\\webmozart', '/'],
			['\\', '/'],
			
			['C:/webmozart/puli/style.css', 'C:/webmozart/puli'],
			['C:/webmozart/puli', 'C:/webmozart'],
			['C:/webmozart', 'C:/'],
			['C:/', 'C:/'],
			['C:', 'C:/'],
			
			['C:\\webmozart\\puli\\style.css', 'C:/webmozart/puli'],
			['C:\\webmozart\\puli', 'C:/webmozart'],
			['C:\\webmozart', 'C:/'],
			['C:\\', 'C:/'],
			
			['phar:///webmozart/puli/style.css', 'phar:///webmozart/puli'],
			['phar:///webmozart/puli', 'phar:///webmozart'],
			['phar:///webmozart', 'phar:///'],
			['phar:///', 'phar:///'],
			
			['phar://C:/webmozart/puli/style.css', 'phar://C:/webmozart/puli'],
			['phar://C:/webmozart/puli', 'phar://C:/webmozart'],
			['phar://C:/webmozart', 'phar://C:/'],
			['phar://C:/', 'phar://C:/'],
			
			['webmozart/puli/style.css', 'webmozart/puli'],
			['webmozart/puli', 'webmozart'],
			['webmozart', ''],
			
			['webmozart\\puli\\style.css', 'webmozart/puli'],
			['webmozart\\puli', 'webmozart'],
			['webmozart', ''],
			
			['/webmozart/./puli/style.css', '/webmozart/puli'],
			['/webmozart/../puli/style.css', '/puli'],
			['/webmozart/./../puli/style.css', '/puli'],
			['/webmozart/.././puli/style.css', '/puli'],
			['/webmozart/../../puli/style.css', '/puli'],
			['/.', '/'],
			['/..', '/'],
			
			['C:webmozart', ''],
		];
	}
	
	/**
	 * @dataProvider provideGetDirectoryTests
	 */
	public function testGetDirectory($path, $directory) {
		$this->assertSame($directory, Path::getDirectory($path));
	}
	
	/**
	 * @expectedException \TypeError
	 * @expectedExceptionMessage Argument 1 passed to Neunerlei\PathUtil\Path::getDirectory() must be of the type
	 *                           string, array given
	 */
	public function testGetDirectoryFailsIfInvalidPath() {
		Path::getDirectory([]);
	}
	
	public function provideGetFilenameTests() {
		return [
			['/webmozart/puli/style.css', 'style.css'],
			['/webmozart/puli/STYLE.CSS', 'STYLE.CSS'],
			['/webmozart/puli/style.css/', 'style.css'],
			['/webmozart/puli/', 'puli'],
			['/webmozart/puli', 'puli'],
			['/', ''],
			['', ''],
		];
	}
	
	/**
	 * @dataProvider provideGetFilenameTests
	 */
	public function testGetFilename($path, $filename) {
		$this->assertSame($filename, Path::getFilename($path));
	}
	
	/**
	 * @expectedException \TypeError
	 * @expectedExceptionMessage Argument 1 passed to Neunerlei\PathUtil\Path::getFilename() must be of the type
	 *                           string, array given
	 */
	public function testGetFilenameFailsIfInvalidPath() {
		Path::getFilename([]);
	}
	
	public function provideGetFilenameWithoutExtensionTests() {
		return [
			['/webmozart/puli/style.css.twig', NULL, 'style.css'],
			['/webmozart/puli/style.css.', NULL, 'style.css'],
			['/webmozart/puli/style.css', NULL, 'style'],
			['/webmozart/puli/.style.css', NULL, '.style'],
			['/webmozart/puli/', NULL, 'puli'],
			['/webmozart/puli', NULL, 'puli'],
			['/', NULL, ''],
			['', NULL, ''],
			
			['/webmozart/puli/style.css', 'css', 'style'],
			['/webmozart/puli/style.css', '.css', 'style'],
			['/webmozart/puli/style.css', 'twig', 'style.css'],
			['/webmozart/puli/style.css', '.twig', 'style.css'],
			['/webmozart/puli/style.css', '', 'style.css'],
			['/webmozart/puli/style.css.', '', 'style.css'],
			['/webmozart/puli/style.css.', '.', 'style.css'],
			['/webmozart/puli/style.css.', '.css', 'style.css'],
			['/webmozart/puli/.style.css', 'css', '.style'],
			['/webmozart/puli/.style.css', '.css', '.style'],
		];
	}
	
	/**
	 * @dataProvider provideGetFilenameWithoutExtensionTests
	 */
	public function testGetFilenameWithoutExtension($path, $extension, $filename) {
		$this->assertSame($filename, Path::getFilenameWithoutExtension($path, $extension));
	}
	
	/**
	 * @expectedException \TypeError
	 * @expectedExceptionMessage Argument 1 passed to Neunerlei\PathUtil\Path::getFilenameWithoutExtension() must be of
	 *                           the type string, array given
	 */
	public function testGetFilenameWithoutExtensionFailsIfInvalidPath() {
		Path::getFilenameWithoutExtension([], '.css');
	}
	
	/**
	 * @expectedException \TypeError
	 * @expectedExceptionMessage Argument 2 passed to Neunerlei\PathUtil\Path::getFilenameWithoutExtension() must be
	 *                           of the type string or null, array given
	 */
	public function testGetFilenameWithoutExtensionFailsIfInvalidExtension() {
		Path::getFilenameWithoutExtension('/style.css', []);
	}
	
	public function provideGetExtensionTests() {
		$tests = [
			['/webmozart/puli/style.css.twig', FALSE, 'twig'],
			['/webmozart/puli/style.css', FALSE, 'css'],
			['/webmozart/puli/style.css.', FALSE, ''],
			['/webmozart/puli/', FALSE, ''],
			['/webmozart/puli', FALSE, ''],
			['/', FALSE, ''],
			['', FALSE, ''],
			
			['/webmozart/puli/style.CSS', FALSE, 'CSS'],
			['/webmozart/puli/style.CSS', TRUE, 'css'],
			['/webmozart/puli/style.ÄÖÜ', FALSE, 'ÄÖÜ'],
		];
		
		if (extension_loaded('mbstring')) {
			// This can only be tested, when mbstring is installed
			$tests[] = ['/webmozart/puli/style.ÄÖÜ', TRUE, 'äöü'];
		}
		
		return $tests;
	}
	
	/**
	 * @dataProvider provideGetExtensionTests
	 */
	public function testGetExtension($path, $forceLowerCase, $extension) {
		$this->assertSame($extension, Path::getExtension($path, $forceLowerCase));
	}
	
	/**
	 * @expectedException \TypeError
	 * @expectedExceptionMessage Argument 1 passed to Neunerlei\PathUtil\Path::getExtension() must be of the type
	 *                           string, array given
	 */
	public function testGetExtensionFailsIfInvalidPath() {
		Path::getExtension([]);
	}
	
	public function provideHasExtensionTests() {
		$tests = [
			[TRUE, '/webmozart/puli/style.css.twig', NULL, FALSE],
			[TRUE, '/webmozart/puli/style.css', NULL, FALSE],
			[FALSE, '/webmozart/puli/style.css.', NULL, FALSE],
			[FALSE, '/webmozart/puli/', NULL, FALSE],
			[FALSE, '/webmozart/puli', NULL, FALSE],
			[FALSE, '/', NULL, FALSE],
			[FALSE, '', NULL, FALSE],
			
			[TRUE, '/webmozart/puli/style.css.twig', 'twig', FALSE],
			[FALSE, '/webmozart/puli/style.css.twig', 'css', FALSE],
			[TRUE, '/webmozart/puli/style.css', 'css', FALSE],
			[TRUE, '/webmozart/puli/style.css', '.css', FALSE],
			[TRUE, '/webmozart/puli/style.css.', '', FALSE],
			[FALSE, '/webmozart/puli/', 'ext', FALSE],
			[FALSE, '/webmozart/puli', 'ext', FALSE],
			[FALSE, '/', 'ext', FALSE],
			[FALSE, '', 'ext', FALSE],
			
			[FALSE, '/webmozart/puli/style.css', 'CSS', FALSE],
			[TRUE, '/webmozart/puli/style.css', 'CSS', TRUE],
			[FALSE, '/webmozart/puli/style.CSS', 'css', FALSE],
			[TRUE, '/webmozart/puli/style.CSS', 'css', TRUE],
			[TRUE, '/webmozart/puli/style.ÄÖÜ', 'ÄÖÜ', FALSE],
			
			[TRUE, '/webmozart/puli/style.css', ['ext', 'css'], FALSE],
			[TRUE, '/webmozart/puli/style.css', ['.ext', '.css'], FALSE],
			[TRUE, '/webmozart/puli/style.css.', ['ext', ''], FALSE],
			[FALSE, '/webmozart/puli/style.css', ['foo', 'bar', ''], FALSE],
			[FALSE, '/webmozart/puli/style.css', ['.foo', '.bar', ''], FALSE],
		];
		
		if (extension_loaded('mbstring')) {
			// This can only be tested, when mbstring is installed
			$tests[] = [TRUE, '/webmozart/puli/style.ÄÖÜ', 'äöü', TRUE];
			$tests[] = [TRUE, '/webmozart/puli/style.ÄÖÜ', ['äöü'], TRUE];
		}
		
		return $tests;
	}
	
	/**
	 * @dataProvider provideHasExtensionTests
	 */
	public function testHasExtension($hasExtension, $path, $extension, $ignoreCase) {
		$this->assertSame($hasExtension, Path::hasExtension($path, $extension, $ignoreCase));
	}
	
	/**
	 * @expectedException \TypeError
	 * @expectedExceptionMessage Argument 1 passed to Neunerlei\PathUtil\Path::hasExtension() must be of the type
	 *                           string, array given
	 */
	public function testHasExtensionFailsIfInvalidPath() {
		Path::hasExtension([]);
	}
	
	/**
	 * @expectedException \TypeError
	 * @expectedExceptionMessage The extensions must be strings. Got: object
	 */
	public function testHasExtensionFailsIfInvalidExtension() {
		Path::hasExtension('/style.css', (object)[]);
	}
	
	public function provideChangeExtensionTests() {
		return [
			['/webmozart/puli/style.css.twig', 'html', '/webmozart/puli/style.css.html'],
			['/webmozart/puli/style.css', 'sass', '/webmozart/puli/style.sass'],
			['/webmozart/puli/style.css', '.sass', '/webmozart/puli/style.sass'],
			['/webmozart/puli/style.css', '', '/webmozart/puli/style.'],
			['/webmozart/puli/style.css.', 'twig', '/webmozart/puli/style.css.twig'],
			['/webmozart/puli/style.css.', '', '/webmozart/puli/style.css.'],
			['/webmozart/puli/style.css', 'äöü', '/webmozart/puli/style.äöü'],
			['/webmozart/puli/style.äöü', 'css', '/webmozart/puli/style.css'],
			['/webmozart/puli/', 'css', '/webmozart/puli/'],
			['/webmozart/puli', 'css', '/webmozart/puli.css'],
			['/', 'css', '/'],
			['', 'css', ''],
		];
	}
	
	/**
	 * @dataProvider provideChangeExtensionTests
	 */
	public function testChangeExtension($path, $extension, $pathExpected) {
		static $call = 0;
		$this->assertSame($pathExpected, Path::changeExtension($path, $extension));
		++$call;
	}
	
	/**
	 * @expectedException \TypeError
	 * @expectedExceptionMessage Argument 1 passed to Neunerlei\PathUtil\Path::changeExtension() must be of the type
	 *                           string, array given
	 */
	public function testChangeExtensionFailsIfInvalidPath() {
		Path::changeExtension([], '.sass');
	}
	
	/**
	 * @expectedException \TypeError
	 * @expectedExceptionMessage Argument 2 passed to Neunerlei\PathUtil\Path::changeExtension() must be of the type
	 *                           string, array given
	 */
	public function testChangeExtensionFailsIfInvalidExtension() {
		Path::changeExtension('/style.css', []);
	}
	
	public function provideIsAbsolutePathTests() {
		return [
			['/css/style.css', TRUE],
			['/', TRUE],
			['css/style.css', FALSE],
			['', FALSE],
			
			['\\css\\style.css', TRUE],
			['\\', TRUE],
			['css\\style.css', FALSE],
			
			['C:/css/style.css', TRUE],
			['D:/', TRUE],
			
			['E:\\css\\style.css', TRUE],
			['F:\\', TRUE],
			
			['phar:///css/style.css', TRUE],
			['phar:///', TRUE],
			
			// Windows special case
			['C:', TRUE],
			
			// Not considered absolute
			['C:css/style.css', FALSE],
		];
	}
	
	/**
	 * @dataProvider provideIsAbsolutePathTests
	 */
	public function testIsAbsolute($path, $isAbsolute) {
		$this->assertSame($isAbsolute, Path::isAbsolute($path));
	}
	
	/**
	 * @expectedException \TypeError
	 * @expectedExceptionMessage Argument 1 passed to Neunerlei\PathUtil\Path::isAbsolute() must be of the type string,
	 *                           array given
	 */
	public function testIsAbsoluteFailsIfInvalidPath() {
		Path::isAbsolute([]);
	}
	
	/**
	 * @dataProvider provideIsAbsolutePathTests
	 */
	public function testIsRelative($path, $isAbsolute) {
		$this->assertSame(!$isAbsolute, Path::isRelative($path));
	}
	
	/**
	 * @expectedException \TypeError
	 * @expectedExceptionMessage Argument 1 passed to Neunerlei\PathUtil\Path::isRelative() must be of the type string,
	 *                           array given
	 */
	public function testIsRelativeFailsIfInvalidPath() {
		Path::isRelative([]);
	}
	
	public function provideGetRootTests() {
		return [
			['/css/style.css', '/'],
			['/', '/'],
			['css/style.css', ''],
			['', ''],
			
			['\\css\\style.css', '/'],
			['\\', '/'],
			['css\\style.css', ''],
			
			['C:/css/style.css', 'C:/'],
			['C:/', 'C:/'],
			['C:', 'C:/'],
			
			['D:\\css\\style.css', 'D:/'],
			['D:\\', 'D:/'],
			
			['phar:///css/style.css', 'phar:///'],
			['phar:///', 'phar:///'],
			
			['phar://C:/css/style.css', 'phar://C:/'],
			['phar://C:/', 'phar://C:/'],
			['phar://C:', 'phar://C:/'],
		];
	}
	
	/**
	 * @dataProvider provideGetRootTests
	 */
	public function testGetRoot($path, $root) {
		$this->assertSame($root, Path::getRoot($path));
	}
	
	/**
	 * @expectedException \TypeError
	 * @expectedExceptionMessage Argument 1 passed to Neunerlei\PathUtil\Path::getRoot() must be of the type string,
	 *                           array given
	 */
	public function testGetRootFailsIfInvalidPath() {
		Path::getRoot([]);
	}
	
	public function providePathTests() {
		return [
			// relative to absolute path
			['css/style.css', '/webmozart/puli', '/webmozart/puli/css/style.css'],
			['../css/style.css', '/webmozart/puli', '/webmozart/css/style.css'],
			['../../css/style.css', '/webmozart/puli', '/css/style.css'],
			
			// relative to root
			['css/style.css', '/', '/css/style.css'],
			['css/style.css', 'C:', 'C:/css/style.css'],
			['css/style.css', 'C:/', 'C:/css/style.css'],
			
			// same sub directories in different base directories
			['../../puli/css/style.css', '/webmozart/css', '/puli/css/style.css'],
			
			['', '/webmozart/puli', '/webmozart/puli'],
			['..', '/webmozart/puli', '/webmozart'],
		];
	}
	
	public function provideMakeAbsoluteTests() {
		return array_merge($this->providePathTests(), [
			// collapse dots
			['css/./style.css', '/webmozart/puli', '/webmozart/puli/css/style.css'],
			['css/../style.css', '/webmozart/puli', '/webmozart/puli/style.css'],
			['css/./../style.css', '/webmozart/puli', '/webmozart/puli/style.css'],
			['css/.././style.css', '/webmozart/puli', '/webmozart/puli/style.css'],
			['./css/style.css', '/webmozart/puli', '/webmozart/puli/css/style.css'],
			
			['css\\.\\style.css', '\\webmozart\\puli', '/webmozart/puli/css/style.css'],
			['css\\..\\style.css', '\\webmozart\\puli', '/webmozart/puli/style.css'],
			['css\\.\\..\\style.css', '\\webmozart\\puli', '/webmozart/puli/style.css'],
			['css\\..\\.\\style.css', '\\webmozart\\puli', '/webmozart/puli/style.css'],
			['.\\css\\style.css', '\\webmozart\\puli', '/webmozart/puli/css/style.css'],
			
			// collapse dots on root
			['./css/style.css', '/', '/css/style.css'],
			['../css/style.css', '/', '/css/style.css'],
			['../css/./style.css', '/', '/css/style.css'],
			['../css/../style.css', '/', '/style.css'],
			['../css/./../style.css', '/', '/style.css'],
			['../css/.././style.css', '/', '/style.css'],
			
			['.\\css\\style.css', '\\', '/css/style.css'],
			['..\\css\\style.css', '\\', '/css/style.css'],
			['..\\css\\.\\style.css', '\\', '/css/style.css'],
			['..\\css\\..\\style.css', '\\', '/style.css'],
			['..\\css\\.\\..\\style.css', '\\', '/style.css'],
			['..\\css\\..\\.\\style.css', '\\', '/style.css'],
			
			['./css/style.css', 'C:/', 'C:/css/style.css'],
			['../css/style.css', 'C:/', 'C:/css/style.css'],
			['../css/./style.css', 'C:/', 'C:/css/style.css'],
			['../css/../style.css', 'C:/', 'C:/style.css'],
			['../css/./../style.css', 'C:/', 'C:/style.css'],
			['../css/.././style.css', 'C:/', 'C:/style.css'],
			
			['.\\css\\style.css', 'C:\\', 'C:/css/style.css'],
			['..\\css\\style.css', 'C:\\', 'C:/css/style.css'],
			['..\\css\\.\\style.css', 'C:\\', 'C:/css/style.css'],
			['..\\css\\..\\style.css', 'C:\\', 'C:/style.css'],
			['..\\css\\.\\..\\style.css', 'C:\\', 'C:/style.css'],
			['..\\css\\..\\.\\style.css', 'C:\\', 'C:/style.css'],
			
			['./css/style.css', 'phar:///', 'phar:///css/style.css'],
			['../css/style.css', 'phar:///', 'phar:///css/style.css'],
			['../css/./style.css', 'phar:///', 'phar:///css/style.css'],
			['../css/../style.css', 'phar:///', 'phar:///style.css'],
			['../css/./../style.css', 'phar:///', 'phar:///style.css'],
			['../css/.././style.css', 'phar:///', 'phar:///style.css'],
			
			['./css/style.css', 'phar://C:/', 'phar://C:/css/style.css'],
			['../css/style.css', 'phar://C:/', 'phar://C:/css/style.css'],
			['../css/./style.css', 'phar://C:/', 'phar://C:/css/style.css'],
			['../css/../style.css', 'phar://C:/', 'phar://C:/style.css'],
			['../css/./../style.css', 'phar://C:/', 'phar://C:/style.css'],
			['../css/.././style.css', 'phar://C:/', 'phar://C:/style.css'],
			
			// absolute paths
			['/css/style.css', '/webmozart/puli', '/css/style.css'],
			['\\css\\style.css', '/webmozart/puli', '/css/style.css'],
			['C:/css/style.css', 'C:/webmozart/puli', 'C:/css/style.css'],
			['D:\\css\\style.css', 'D:/webmozart/puli', 'D:/css/style.css'],
		]);
	}
	
	/**
	 * @dataProvider provideMakeAbsoluteTests
	 */
	public function testMakeAbsolute($relativePath, $basePath, $absolutePath) {
		$this->assertSame($absolutePath, Path::makeAbsolute($relativePath, $basePath));
	}
	
	/**
	 * @expectedException \TypeError
	 * @expectedExceptionMessage Argument 1 passed to Neunerlei\PathUtil\Path::makeAbsolute() must be of the type
	 *                           string, array given
	 */
	public function testMakeAbsoluteFailsIfInvalidPath() {
		Path::makeAbsolute([], '/webmozart/puli');
	}
	
	/**
	 * @expectedException \TypeError
	 * @expectedExceptionMessage Argument 2 passed to Neunerlei\PathUtil\Path::makeAbsolute() must be of the type
	 *                           string, array given
	 */
	public function testMakeAbsoluteFailsIfInvalidBasePath() {
		Path::makeAbsolute('css/style.css', []);
	}
	
	/**
	 * @expectedException \InvalidArgumentException
	 * @expectedExceptionMessage The base path "webmozart/puli" is not an absolute path.
	 */
	public function testMakeAbsoluteFailsIfBasePathNotAbsolute() {
		Path::makeAbsolute('css/style.css', 'webmozart/puli');
	}
	
	/**
	 * @expectedException \InvalidArgumentException
	 * @expectedExceptionMessage The base path must be a non-empty string!
	 */
	public function testMakeAbsoluteFailsIfBasePathEmpty() {
		Path::makeAbsolute('css/style.css', '');
	}
	
	/**
	 * @expectedException \TypeError
	 * @expectedExceptionMessage Argument 2 passed to Neunerlei\PathUtil\Path::makeAbsolute() must be of the type
	 *                           string, null given
	 */
	public function testMakeAbsoluteFailsIfBasePathNull() {
		Path::makeAbsolute('css/style.css', NULL);
	}
	
	public function provideAbsolutePathsWithDifferentRoots() {
		return [
			['C:/css/style.css', '/webmozart/puli'],
			['C:/css/style.css', '\\webmozart\\puli'],
			['C:\\css\\style.css', '/webmozart/puli'],
			['C:\\css\\style.css', '\\webmozart\\puli'],
			
			['/css/style.css', 'C:/webmozart/puli'],
			['/css/style.css', 'C:\\webmozart\\puli'],
			['\\css\\style.css', 'C:/webmozart/puli'],
			['\\css\\style.css', 'C:\\webmozart\\puli'],
			
			['D:/css/style.css', 'C:/webmozart/puli'],
			['D:/css/style.css', 'C:\\webmozart\\puli'],
			['D:\\css\\style.css', 'C:/webmozart/puli'],
			['D:\\css\\style.css', 'C:\\webmozart\\puli'],
			
			['phar:///css/style.css', '/webmozart/puli'],
			['/css/style.css', 'phar:///webmozart/puli'],
			
			['phar://C:/css/style.css', 'C:/webmozart/puli'],
			['phar://C:/css/style.css', 'C:\\webmozart\\puli'],
			['phar://C:\\css\\style.css', 'C:/webmozart/puli'],
			['phar://C:\\css\\style.css', 'C:\\webmozart\\puli'],
		];
	}
	
	/**
	 * @dataProvider provideAbsolutePathsWithDifferentRoots
	 */
	public function testMakeAbsoluteDoesNotFailIfDifferentRoot($basePath, $absolutePath) {
		// If a path in partition D: is passed, but $basePath is in partition
		// C:, the path should be returned unchanged
		$this->assertSame(Path::canonicalize($absolutePath), Path::makeAbsolute($absolutePath, $basePath));
	}
	
	public function provideMakeRelativeTests() {
		$paths = array_map(function (array $arguments) {
			return [$arguments[2], $arguments[1], $arguments[0]];
		}, $this->providePathTests());
		
		return array_merge($paths, [
			['/webmozart/puli/./css/style.css', '/webmozart/puli', 'css/style.css'],
			['/webmozart/puli/../css/style.css', '/webmozart/puli', '../css/style.css'],
			['/webmozart/puli/.././css/style.css', '/webmozart/puli', '../css/style.css'],
			['/webmozart/puli/./../css/style.css', '/webmozart/puli', '../css/style.css'],
			['/webmozart/puli/../../css/style.css', '/webmozart/puli', '../../css/style.css'],
			['/webmozart/puli/css/style.css', '/webmozart/./puli', 'css/style.css'],
			['/webmozart/puli/css/style.css', '/webmozart/../puli', '../webmozart/puli/css/style.css'],
			['/webmozart/puli/css/style.css', '/webmozart/./../puli', '../webmozart/puli/css/style.css'],
			['/webmozart/puli/css/style.css', '/webmozart/.././puli', '../webmozart/puli/css/style.css'],
			['/webmozart/puli/css/style.css', '/webmozart/../../puli', '../webmozart/puli/css/style.css'],
			
			// first argument shorter than second
			['/css', '/webmozart/puli', '../../css'],
			
			// second argument shorter than first
			['/webmozart/puli', '/css', '../webmozart/puli'],
			
			['\\webmozart\\puli\\css\\style.css', '\\webmozart\\puli', 'css/style.css'],
			['\\webmozart\\css\\style.css', '\\webmozart\\puli', '../css/style.css'],
			['\\css\\style.css', '\\webmozart\\puli', '../../css/style.css'],
			
			['C:/webmozart/puli/css/style.css', 'C:/webmozart/puli', 'css/style.css'],
			['C:/webmozart/css/style.css', 'C:/webmozart/puli', '../css/style.css'],
			['C:/css/style.css', 'C:/webmozart/puli', '../../css/style.css'],
			
			['C:\\webmozart\\puli\\css\\style.css', 'C:\\webmozart\\puli', 'css/style.css'],
			['C:\\webmozart\\css\\style.css', 'C:\\webmozart\\puli', '../css/style.css'],
			['C:\\css\\style.css', 'C:\\webmozart\\puli', '../../css/style.css'],
			
			['phar:///webmozart/puli/css/style.css', 'phar:///webmozart/puli', 'css/style.css'],
			['phar:///webmozart/css/style.css', 'phar:///webmozart/puli', '../css/style.css'],
			['phar:///css/style.css', 'phar:///webmozart/puli', '../../css/style.css'],
			
			['phar://C:/webmozart/puli/css/style.css', 'phar://C:/webmozart/puli', 'css/style.css'],
			['phar://C:/webmozart/css/style.css', 'phar://C:/webmozart/puli', '../css/style.css'],
			['phar://C:/css/style.css', 'phar://C:/webmozart/puli', '../../css/style.css'],
			
			// already relative + already in root basepath
			['../style.css', '/', 'style.css'],
			['./style.css', '/', 'style.css'],
			['../../style.css', '/', 'style.css'],
			['..\\style.css', 'C:\\', 'style.css'],
			['.\\style.css', 'C:\\', 'style.css'],
			['..\\..\\style.css', 'C:\\', 'style.css'],
			['../style.css', 'C:/', 'style.css'],
			['./style.css', 'C:/', 'style.css'],
			['../../style.css', 'C:/', 'style.css'],
			['..\\style.css', '\\', 'style.css'],
			['.\\style.css', '\\', 'style.css'],
			['..\\..\\style.css', '\\', 'style.css'],
			['../style.css', 'phar:///', 'style.css'],
			['./style.css', 'phar:///', 'style.css'],
			['../../style.css', 'phar:///', 'style.css'],
			['..\\style.css', 'phar://C:\\', 'style.css'],
			['.\\style.css', 'phar://C:\\', 'style.css'],
			['..\\..\\style.css', 'phar://C:\\', 'style.css'],
			
			['css/../style.css', '/', 'style.css'],
			['css/./style.css', '/', 'css/style.css'],
			['css\\..\\style.css', 'C:\\', 'style.css'],
			['css\\.\\style.css', 'C:\\', 'css/style.css'],
			['css/../style.css', 'C:/', 'style.css'],
			['css/./style.css', 'C:/', 'css/style.css'],
			['css\\..\\style.css', '\\', 'style.css'],
			['css\\.\\style.css', '\\', 'css/style.css'],
			['css/../style.css', 'phar:///', 'style.css'],
			['css/./style.css', 'phar:///', 'css/style.css'],
			['css\\..\\style.css', 'phar://C:\\', 'style.css'],
			['css\\.\\style.css', 'phar://C:\\', 'css/style.css'],
			
			// already relative
			['css/style.css', '/webmozart/puli', 'css/style.css'],
			['css\\style.css', '\\webmozart\\puli', 'css/style.css'],
			
			// both relative
			['css/style.css', 'webmozart/puli', '../../css/style.css'],
			['css\\style.css', 'webmozart\\puli', '../../css/style.css'],
			
			// relative to empty
			['css/style.css', '', 'css/style.css'],
			['css\\style.css', '', 'css/style.css'],
			
			// different slashes in path and base path
			['/webmozart/puli/css/style.css', '\\webmozart\\puli', 'css/style.css'],
			['\\webmozart\\puli\\css\\style.css', '/webmozart/puli', 'css/style.css'],
		]);
	}
	
	/**
	 * @dataProvider provideMakeRelativeTests
	 */
	public function testMakeRelative($absolutePath, $basePath, $relativePath) {
		$this->assertSame($relativePath, Path::makeRelative($absolutePath, $basePath));
	}
	
	/**
	 * @expectedException \TypeError
	 * @expectedExceptionMessage Argument 1 passed to Neunerlei\PathUtil\Path::makeRelative() must be of the type
	 *                           string, array given
	 */
	public function testMakeRelativeFailsIfInvalidPath() {
		Path::makeRelative([], '/webmozart/puli');
	}
	
	/**
	 * @expectedException \TypeError
	 * @expectedExceptionMessage Argument 2 passed to Neunerlei\PathUtil\Path::makeRelative() must be of the type
	 *                           string, array given
	 */
	public function testMakeRelativeFailsIfInvalidBasePath() {
		Path::makeRelative('/webmozart/puli/css/style.css', []);
	}
	
	/**
	 * @expectedException \InvalidArgumentException
	 * @expectedExceptionMessage The absolute path "/webmozart/puli/css/style.css" cannot be made relative to the
	 *                           relative path "webmozart/puli". You should provide an absolute base path instead.
	 */
	public function testMakeRelativeFailsIfAbsolutePathAndBasePathNotAbsolute() {
		Path::makeRelative('/webmozart/puli/css/style.css', 'webmozart/puli');
	}
	
	/**
	 * @expectedException \InvalidArgumentException
	 * @expectedExceptionMessage The absolute path "/webmozart/puli/css/style.css" cannot be made relative to the
	 *                           relative path "". You should provide an absolute base path instead.
	 */
	public function testMakeRelativeFailsIfAbsolutePathAndBasePathEmpty() {
		Path::makeRelative('/webmozart/puli/css/style.css', '');
	}
	
	/**
	 * @expectedException \TypeError
	 * @expectedExceptionMessage Argument 2 passed to Neunerlei\PathUtil\Path::makeRelative()
	 */
	public function testMakeRelativeFailsIfBasePathNull() {
		Path::makeRelative('/webmozart/puli/css/style.css', NULL);
	}
	
	/**
	 * @dataProvider provideAbsolutePathsWithDifferentRoots
	 * @expectedException \InvalidArgumentException
	 */
	public function testMakeRelativeFailsIfDifferentRoot($absolutePath, $basePath) {
		Path::makeRelative($absolutePath, $basePath);
	}
	
	public function provideIsLocalTests() {
		return [
			['/bg.png', TRUE],
			['bg.png', TRUE],
			['http://example.com/bg.png', FALSE],
			['http://example.com', FALSE],
			['', FALSE],
		];
	}
	
	/**
	 * @dataProvider provideIsLocalTests
	 */
	public function testIsLocal($path, $isLocal) {
		$this->assertSame($isLocal, Path::isLocal($path));
	}
	
	/**
	 * @expectedException \TypeError
	 * @expectedExceptionMessage Argument 1 passed to Neunerlei\PathUtil\Path::isLocal() must be of the type string,
	 *                           array given
	 */
	public function testIsLocalFailsIfInvalidPath() {
		Path::isLocal([]);
	}
	
	public function provideGetLongestCommonBasePathTests() {
		return [
			// same paths
			[['/base/path', '/base/path'], '/base/path'],
			[['C:/base/path', 'C:/base/path'], 'C:/base/path'],
			[['C:\\base\\path', 'C:\\base\\path'], 'C:/base/path'],
			[['C:/base/path', 'C:\\base\\path'], 'C:/base/path'],
			[['phar:///base/path', 'phar:///base/path'], 'phar:///base/path'],
			[['phar://C:/base/path', 'phar://C:/base/path'], 'phar://C:/base/path'],
			
			// trailing slash
			[['/base/path/', '/base/path'], '/base/path'],
			[['C:/base/path/', 'C:/base/path'], 'C:/base/path'],
			[['C:\\base\\path\\', 'C:\\base\\path'], 'C:/base/path'],
			[['C:/base/path/', 'C:\\base\\path'], 'C:/base/path'],
			[['phar:///base/path/', 'phar:///base/path'], 'phar:///base/path'],
			[['phar://C:/base/path/', 'phar://C:/base/path'], 'phar://C:/base/path'],
			
			[['/base/path', '/base/path/'], '/base/path'],
			[['C:/base/path', 'C:/base/path/'], 'C:/base/path'],
			[['C:\\base\\path', 'C:\\base\\path\\'], 'C:/base/path'],
			[['C:/base/path', 'C:\\base\\path\\'], 'C:/base/path'],
			[['phar:///base/path', 'phar:///base/path/'], 'phar:///base/path'],
			[['phar://C:/base/path', 'phar://C:/base/path/'], 'phar://C:/base/path'],
			
			// first in second
			[['/base/path/sub', '/base/path'], '/base/path'],
			[['C:/base/path/sub', 'C:/base/path'], 'C:/base/path'],
			[['C:\\base\\path\\sub', 'C:\\base\\path'], 'C:/base/path'],
			[['C:/base/path/sub', 'C:\\base\\path'], 'C:/base/path'],
			[['phar:///base/path/sub', 'phar:///base/path'], 'phar:///base/path'],
			[['phar://C:/base/path/sub', 'phar://C:/base/path'], 'phar://C:/base/path'],
			
			// second in first
			[['/base/path', '/base/path/sub'], '/base/path'],
			[['C:/base/path', 'C:/base/path/sub'], 'C:/base/path'],
			[['C:\\base\\path', 'C:\\base\\path\\sub'], 'C:/base/path'],
			[['C:/base/path', 'C:\\base\\path\\sub'], 'C:/base/path'],
			[['phar:///base/path', 'phar:///base/path/sub'], 'phar:///base/path'],
			[['phar://C:/base/path', 'phar://C:/base/path/sub'], 'phar://C:/base/path'],
			
			// first is prefix
			[['/base/path/di', '/base/path/dir'], '/base/path'],
			[['C:/base/path/di', 'C:/base/path/dir'], 'C:/base/path'],
			[['C:\\base\\path\\di', 'C:\\base\\path\\dir'], 'C:/base/path'],
			[['C:/base/path/di', 'C:\\base\\path\\dir'], 'C:/base/path'],
			[['phar:///base/path/di', 'phar:///base/path/dir'], 'phar:///base/path'],
			[['phar://C:/base/path/di', 'phar://C:/base/path/dir'], 'phar://C:/base/path'],
			
			// second is prefix
			[['/base/path/dir', '/base/path/di'], '/base/path'],
			[['C:/base/path/dir', 'C:/base/path/di'], 'C:/base/path'],
			[['C:\\base\\path\\dir', 'C:\\base\\path\\di'], 'C:/base/path'],
			[['C:/base/path/dir', 'C:\\base\\path\\di'], 'C:/base/path'],
			[['phar:///base/path/dir', 'phar:///base/path/di'], 'phar:///base/path'],
			[['phar://C:/base/path/dir', 'phar://C:/base/path/di'], 'phar://C:/base/path'],
			
			// root is common base path
			[['/first', '/second'], '/'],
			[['C:/first', 'C:/second'], 'C:/'],
			[['C:\\first', 'C:\\second'], 'C:/'],
			[['C:/first', 'C:\\second'], 'C:/'],
			[['phar:///first', 'phar:///second'], 'phar:///'],
			[['phar://C:/first', 'phar://C:/second'], 'phar://C:/'],
			
			// windows vs unix
			[['/base/path', 'C:/base/path'], NULL],
			[['C:/base/path', '/base/path'], NULL],
			[['/base/path', 'C:\\base\\path'], NULL],
			[['phar:///base/path', 'phar://C:/base/path'], NULL],
			
			// different partitions
			[['C:/base/path', 'D:/base/path'], NULL],
			[['C:/base/path', 'D:\\base\\path'], NULL],
			[['C:\\base\\path', 'D:\\base\\path'], NULL],
			[['phar://C:/base/path', 'phar://D:/base/path'], NULL],
			
			// three paths
			[['/base/path/foo', '/base/path', '/base/path/bar'], '/base/path'],
			[['C:/base/path/foo', 'C:/base/path', 'C:/base/path/bar'], 'C:/base/path'],
			[['C:\\base\\path\\foo', 'C:\\base\\path', 'C:\\base\\path\\bar'], 'C:/base/path'],
			[['C:/base/path//foo', 'C:/base/path', 'C:\\base\\path\\bar'], 'C:/base/path'],
			[['phar:///base/path/foo', 'phar:///base/path', 'phar:///base/path/bar'], 'phar:///base/path'],
			[['phar://C:/base/path/foo', 'phar://C:/base/path', 'phar://C:/base/path/bar'], 'phar://C:/base/path'],
			
			// three paths with root
			[['/base/path/foo', '/', '/base/path/bar'], '/'],
			[['C:/base/path/foo', 'C:/', 'C:/base/path/bar'], 'C:/'],
			[['C:\\base\\path\\foo', 'C:\\', 'C:\\base\\path\\bar'], 'C:/'],
			[['C:/base/path//foo', 'C:/', 'C:\\base\\path\\bar'], 'C:/'],
			[['phar:///base/path/foo', 'phar:///', 'phar:///base/path/bar'], 'phar:///'],
			[['phar://C:/base/path/foo', 'phar://C:/', 'phar://C:/base/path/bar'], 'phar://C:/'],
			
			// three paths, different roots
			[['/base/path/foo', 'C:/base/path', '/base/path/bar'], NULL],
			[['/base/path/foo', 'C:\\base\\path', '/base/path/bar'], NULL],
			[['C:/base/path/foo', 'D:/base/path', 'C:/base/path/bar'], NULL],
			[['C:\\base\\path\\foo', 'D:\\base\\path', 'C:\\base\\path\\bar'], NULL],
			[['C:/base/path//foo', 'D:/base/path', 'C:\\base\\path\\bar'], NULL],
			[['phar:///base/path/foo', 'phar://C:/base/path', 'phar:///base/path/bar'], NULL],
			[['phar://C:/base/path/foo', 'phar://D:/base/path', 'phar://C:/base/path/bar'], NULL],
			
			// only one path
			[['/base/path'], '/base/path'],
			[['C:/base/path'], 'C:/base/path'],
			[['C:\\base\\path'], 'C:/base/path'],
			[['phar:///base/path'], 'phar:///base/path'],
			[['phar://C:/base/path'], 'phar://C:/base/path'],
		];
	}
	
	/**
	 * @dataProvider provideGetLongestCommonBasePathTests
	 */
	public function testGetLongestCommonBasePath(array $paths, $basePath) {
		$this->assertSame($basePath, Path::getLongestCommonBasePath($paths));
	}
	
	/**
	 * @expectedException \TypeError
	 * @expectedExceptionMessage The paths must be strings. Got: array
	 */
	public function testGetLongestCommonBasePathFailsIfInvalidPath() {
		Path::getLongestCommonBasePath([[]]);
	}
	
	public function provideIsBasePathTests() {
		return [
			// same paths
			['/base/path', '/base/path', TRUE],
			['C:/base/path', 'C:/base/path', TRUE],
			['C:\\base\\path', 'C:\\base\\path', TRUE],
			['C:/base/path', 'C:\\base\\path', TRUE],
			['phar:///base/path', 'phar:///base/path', TRUE],
			['phar://C:/base/path', 'phar://C:/base/path', TRUE],
			
			// trailing slash
			['/base/path/', '/base/path', TRUE],
			['C:/base/path/', 'C:/base/path', TRUE],
			['C:\\base\\path\\', 'C:\\base\\path', TRUE],
			['C:/base/path/', 'C:\\base\\path', TRUE],
			['phar:///base/path/', 'phar:///base/path', TRUE],
			['phar://C:/base/path/', 'phar://C:/base/path', TRUE],
			
			['/base/path', '/base/path/', TRUE],
			['C:/base/path', 'C:/base/path/', TRUE],
			['C:\\base\\path', 'C:\\base\\path\\', TRUE],
			['C:/base/path', 'C:\\base\\path\\', TRUE],
			['phar:///base/path', 'phar:///base/path/', TRUE],
			['phar://C:/base/path', 'phar://C:/base/path/', TRUE],
			
			// first in second
			['/base/path/sub', '/base/path', FALSE],
			['C:/base/path/sub', 'C:/base/path', FALSE],
			['C:\\base\\path\\sub', 'C:\\base\\path', FALSE],
			['C:/base/path/sub', 'C:\\base\\path', FALSE],
			['phar:///base/path/sub', 'phar:///base/path', FALSE],
			['phar://C:/base/path/sub', 'phar://C:/base/path', FALSE],
			
			// second in first
			['/base/path', '/base/path/sub', TRUE],
			['C:/base/path', 'C:/base/path/sub', TRUE],
			['C:\\base\\path', 'C:\\base\\path\\sub', TRUE],
			['C:/base/path', 'C:\\base\\path\\sub', TRUE],
			['phar:///base/path', 'phar:///base/path/sub', TRUE],
			['phar://C:/base/path', 'phar://C:/base/path/sub', TRUE],
			
			// first is prefix
			['/base/path/di', '/base/path/dir', FALSE],
			['C:/base/path/di', 'C:/base/path/dir', FALSE],
			['C:\\base\\path\\di', 'C:\\base\\path\\dir', FALSE],
			['C:/base/path/di', 'C:\\base\\path\\dir', FALSE],
			['phar:///base/path/di', 'phar:///base/path/dir', FALSE],
			['phar://C:/base/path/di', 'phar://C:/base/path/dir', FALSE],
			
			// second is prefix
			['/base/path/dir', '/base/path/di', FALSE],
			['C:/base/path/dir', 'C:/base/path/di', FALSE],
			['C:\\base\\path\\dir', 'C:\\base\\path\\di', FALSE],
			['C:/base/path/dir', 'C:\\base\\path\\di', FALSE],
			['phar:///base/path/dir', 'phar:///base/path/di', FALSE],
			['phar://C:/base/path/dir', 'phar://C:/base/path/di', FALSE],
			
			// root
			['/', '/second', TRUE],
			['C:/', 'C:/second', TRUE],
			['C:', 'C:/second', TRUE],
			['C:\\', 'C:\\second', TRUE],
			['C:/', 'C:\\second', TRUE],
			['phar:///', 'phar:///second', TRUE],
			['phar://C:/', 'phar://C:/second', TRUE],
			
			// windows vs unix
			['/base/path', 'C:/base/path', FALSE],
			['C:/base/path', '/base/path', FALSE],
			['/base/path', 'C:\\base\\path', FALSE],
			['/base/path', 'phar:///base/path', FALSE],
			['phar:///base/path', 'phar://C:/base/path', FALSE],
			
			// different partitions
			['C:/base/path', 'D:/base/path', FALSE],
			['C:/base/path', 'D:\\base\\path', FALSE],
			['C:\\base\\path', 'D:\\base\\path', FALSE],
			['C:/base/path', 'phar://C:/base/path', FALSE],
			['phar://C:/base/path', 'phar://D:/base/path', FALSE],
		];
	}
	
	/**
	 * @dataProvider provideIsBasePathTests
	 */
	public function testIsBasePath($path, $ofPath, $result) {
		$this->assertSame($result, Path::isBasePath($path, $ofPath));
	}
	
	/**
	 * @expectedException \TypeError
	 * @expectedExceptionMessage Argument 1 passed to Neunerlei\PathUtil\Path::isBasePath()
	 */
	public function testIsBasePathFailsIfInvalidBasePath() {
		Path::isBasePath([], '/base/path');
	}
	
	/**
	 * @expectedException \TypeError
	 * @expectedExceptionMessage Argument 2 passed to Neunerlei\PathUtil\Path::isBasePath() must be of the type string
	 */
	public function testIsBasePathFailsIfInvalidPath() {
		Path::isBasePath('/base/path', []);
	}
	
	public function provideJoinTests() {
		return [
			['', '', ''],
			['/path/to/test', '', '/path/to/test'],
			['/path/to//test', '', '/path/to/test'],
			['', '/path/to/test', '/path/to/test'],
			['', '/path/to//test', '/path/to/test'],
			
			['/path/to/test', 'subdir', '/path/to/test/subdir'],
			['/path/to/test/', 'subdir', '/path/to/test/subdir'],
			['/path/to/test', '/subdir', '/path/to/test/subdir'],
			['/path/to/test/', '/subdir', '/path/to/test/subdir'],
			['/path/to/test', './subdir', '/path/to/test/subdir'],
			['/path/to/test/', './subdir', '/path/to/test/subdir'],
			['/path/to/test/', '../parentdir', '/path/to/parentdir'],
			['/path/to/test', '../parentdir', '/path/to/parentdir'],
			['path/to/test/', '/subdir', 'path/to/test/subdir'],
			['path/to/test', '/subdir', 'path/to/test/subdir'],
			['../path/to/test', '/subdir', '../path/to/test/subdir'],
			['path', '../../subdir', '../subdir'],
			['/path', '../../subdir', '/subdir'],
			['../path', '../../subdir', '../../subdir'],
			
			[['/path/to/test', 'subdir'], '', '/path/to/test/subdir'],
			[['/path/to/test', '/subdir'], '', '/path/to/test/subdir'],
			[['/path/to/test/', 'subdir'], '', '/path/to/test/subdir'],
			[['/path/to/test/', '/subdir'], '', '/path/to/test/subdir'],
			
			[['/path'], '', '/path'],
			[['/path', 'to', '/test'], '', '/path/to/test'],
			[['/path', '', '/test'], '', '/path/test'],
			[['path', 'to', 'test'], '', 'path/to/test'],
			[[], '', ''],
			
			['base/path', 'to/test', 'base/path/to/test'],
			
			['C:\\path\\to\\test', 'subdir', 'C:/path/to/test/subdir'],
			['C:\\path\\to\\test\\', 'subdir', 'C:/path/to/test/subdir'],
			['C:\\path\\to\\test', '/subdir', 'C:/path/to/test/subdir'],
			['C:\\path\\to\\test\\', '/subdir', 'C:/path/to/test/subdir'],
			
			['/', 'subdir', '/subdir'],
			['/', '/subdir', '/subdir'],
			['C:/', 'subdir', 'C:/subdir'],
			['C:/', '/subdir', 'C:/subdir'],
			['C:\\', 'subdir', 'C:/subdir'],
			['C:\\', '/subdir', 'C:/subdir'],
			['C:', 'subdir', 'C:/subdir'],
			['C:', '/subdir', 'C:/subdir'],
			
			['phar://', '/path/to/test', 'phar:///path/to/test'],
			['phar:///', '/path/to/test', 'phar:///path/to/test'],
			['phar:///path/to/test', 'subdir', 'phar:///path/to/test/subdir'],
			['phar:///path/to/test', 'subdir/', 'phar:///path/to/test/subdir'],
			['phar:///path/to/test', '/subdir', 'phar:///path/to/test/subdir'],
			['phar:///path/to/test/', 'subdir', 'phar:///path/to/test/subdir'],
			['phar:///path/to/test/', '/subdir', 'phar:///path/to/test/subdir'],
			
			['phar://', 'C:/path/to/test', 'phar://C:/path/to/test'],
			['phar://', 'C:\\path\\to\\test', 'phar://C:/path/to/test'],
			['phar://C:/path/to/test', 'subdir', 'phar://C:/path/to/test/subdir'],
			['phar://C:/path/to/test', 'subdir/', 'phar://C:/path/to/test/subdir'],
			['phar://C:/path/to/test', '/subdir', 'phar://C:/path/to/test/subdir'],
			['phar://C:/path/to/test/', 'subdir', 'phar://C:/path/to/test/subdir'],
			['phar://C:/path/to/test/', '/subdir', 'phar://C:/path/to/test/subdir'],
			['phar://C:', 'path/to/test', 'phar://C:/path/to/test'],
			['phar://C:', '/path/to/test', 'phar://C:/path/to/test'],
			['phar://C:/', 'path/to/test', 'phar://C:/path/to/test'],
			['phar://C:/', '/path/to/test', 'phar://C:/path/to/test'],
		];
	}
	
	/**
	 * @dataProvider provideJoinTests
	 */
	public function testJoin($path1, $path2, $result) {
		$this->assertSame($result, Path::join($path1, $path2));
	}
	
	public function testJoinVarArgs() {
		$this->assertSame('/path', Path::join('/path'));
		$this->assertSame('/path/to', Path::join('/path', 'to'));
		$this->assertSame('/path/to/test', Path::join('/path', 'to', '/test'));
		$this->assertSame('/path/to/test/subdir', Path::join('/path', 'to', '/test', 'subdir/'));
	}
	
	/**
	 * @expectedException \TypeError
	 * @expectedExceptionMessage The paths must be strings. Got: array
	 */
	public function testJoinFailsIfInvalidPath() {
		Path::join('/path', []);
	}
	
	/**
	 * @expectedException \RuntimeException
	 * @expectedExceptionMessage Your environment or operation system isn't supported
	 */
	public function testGetHomeDirectoryFailsIfNotSupportedOperationSystem() {
		putenv('HOME=');
		
		Path::getHomeDirectory();
	}
	
	public function testGetHomeDirectoryForUnix() {
		$this->assertEquals('/home/webmozart', Path::getHomeDirectory());
	}
	
	public function testGetHomeDirectoryForWindows() {
		putenv('HOME=');
		putenv('HOMEDRIVE=C:');
		putenv('HOMEPATH=/users/webmozart');
		
		$this->assertEquals('C:/users/webmozart', Path::getHomeDirectory());
	}
	
	public function testNormalize() {
		$this->assertSame('C:/Foo/Bar/test', Path::normalize('C:\\Foo\\Bar/test'));
	}
	
	/**
	 * @expectedException \TypeError
	 */
	public function testNormalizeFailsIfNoString() {
		Path::normalize(TRUE);
	}
}
