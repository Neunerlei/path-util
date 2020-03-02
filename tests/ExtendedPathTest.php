<?php
/**
 * Copyright 2020 Martin Neundorfer (Neunerlei)
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * Last modified: 2020.03.02 at 17:36
 */

namespace Neunerlei\PathUtil\Tests;


use Neunerlei\PathUtil\Path;
use Psr\Http\Message\UriInterface;

class ExtendedPathTest extends \PHPUnit_Framework_TestCase {
	
	public function testUnifySlashes() {
		$ds = DIRECTORY_SEPARATOR;
		$this->assertEquals($ds . "foo" . $ds . "bar" . $ds . "baz", Path::unifySlashes("\\foo/bar\\baz"));
		$this->assertEquals($ds . "foo" . $ds . "bar", Path::unifySlashes($ds . "foo" . $ds . "bar"));
		$this->assertEquals($ds . "foo" . $ds . "bar", Path::unifySlashes("/foo/bar"));
		$this->assertEquals($ds . "foo" . $ds . "bar", Path::unifySlashes("/foo/../foo/bar"));
		$this->assertEquals($ds . "bar", Path::unifySlashes("/foo/../bar"));
		$this->assertEquals("bar", Path::unifySlashes("bar"));
		$this->assertEquals("-bar", Path::unifySlashes("/foo/../bar", "-"));
		$this->assertEquals("-foo-bar", Path::unifySlashes("/foo/bar", "-"));
	}
	
	public function testUnifyPath() {
		$ds = DIRECTORY_SEPARATOR;
		$this->assertEquals($ds . "foo" . $ds . "bar" . $ds . "baz" . $ds, Path::unifyPath("\\foo/bar\\baz"));
		$this->assertEquals($ds . "foo" . $ds . "bar" . $ds, Path::unifyPath($ds . "foo" . $ds . "bar"));
		$this->assertEquals($ds . "foo" . $ds . "bar" . $ds, Path::unifyPath("/foo/bar"));
	}
	
	public function testClassBaseName() {
		$this->assertEquals("ExtendedPathTest", Path::classBasename(ExtendedPathTest::class));
		$this->assertEquals("ExtendedPathTest", Path::classBasename(__CLASS__));
		$this->assertEquals("Path", Path::classBasename(Path::class));
		$this->assertEquals("Exception", Path::classBasename(\Exception::class));
		$this->assertEquals("", Path::classBasename(""));
	}
	
	public function testClassNamespace() {
		$this->assertEquals("Neunerlei\\PathUtil\\Tests", Path::classNamespace(ExtendedPathTest::class));
		$this->assertEquals("Neunerlei\\PathUtil\\Tests", Path::classNamespace(__CLASS__));
		$this->assertEquals("Neunerlei\\PathUtil", Path::classNamespace(Path::class));
		$this->assertEquals("", Path::classNamespace(\Exception::class));
		$this->assertEquals("", Path::classNamespace(""));
	}
	
	public function testMakeLink() {
		$this->assertInstanceOf(UriInterface::class, Path::makeUri());
		$this->assertEquals("", (string)Path::makeUri());
		$this->assertEquals("http://www.test.com", (string)Path::makeUri("http://www.test.com"));
		$this->assertEquals("http://www.test.com?q=123&t=123", (string)Path::makeUri(parse_url("http://www.test.com?q=123&t=123")));
		
		$_SERVER["REQUEST_SCHEME"] = "http";
		$_SERVER["HTTP_HOST"] = "test.com";
		$_SERVER['REQUEST_URI'] = "";
		$this->assertEquals("http://test.com", (string)Path::makeUri(TRUE));
		
		$i = Path::makeUri();
		$i = $i->withHost("www.test.com");
		$this->assertNotSame($i, Path::makeUri($i));
		$this->assertEquals((string)$i, (string)Path::makeUri($i));
	}
	
	public function testMakeLinkFailsOnInvalidArg() {
		$this->setExpectedException(\InvalidArgumentException::class);
		Path::makeUri(new \stdClass());
	}
}