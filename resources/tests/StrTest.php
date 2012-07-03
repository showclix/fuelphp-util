<?php

use Fuel\Util\Str;

class StrTest extends PHPUnit_Framework_TestCase
{


	public function truncateProvider()
	{
		return array(
			array(15, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.'),
		);
	}

	public function startEndProvider()
	{
		return array(array('this is a string'));
	}

	/**
	 * Test for Str::startsWith()
	 *
	 * @test
	 * @dataProvider startEndProvider
	 */
	public function testStartsWithMatch($str)
	{
		$this->assertEquals(true, Str::startsWith($str, 'this'));
	}

	/**
	 * Test for Str::startsWith()
	 *
	 * @test
	 * @dataProvider startEndProvider
	 */
	public function testStartsWithFailure($str)
	{
		$this->assertEquals(false, Str::startsWith($str, 'not this'));
	}

	/**
	 * Test for Str::startsWith()
	 *
	 * @test
	 * @dataProvider startEndProvider
	 */
	public function testStartsWithIgnoreCaseMatch($str)
	{
		$this->assertEquals(true, Str::startsWith($str, 'ThIs', true));
	}

	/**
	 * Test for Str::startsWith()
	 *
	 * @test
	 * @dataProvider startEndProvider
	 */
	public function testStartsWithIgnoreCaseFailure($str)
	{
		$this->assertEquals(false, Str::startsWith($str, 'Not ThIs', true));
	}

	/**
	 * Test for Str::truncate()
	 *
	 * @test
	 * @dataProvider truncateProvider
	 */
	public function testTruncatePlain($limit, $string)
	{
		$output = Str::truncate($string, $limit);
		$expected = 'Lorem ipsum dol...';
		$this->assertEquals($expected, $output);
	}

	/**
	 * Test for Str::truncate()
	 *
	 * @test
	 * @dataProvider truncateProvider
	 */
	public function testTruncateCustomContinuation($limit, $string)
	{
		$output = Str::truncate($string, $limit, '..');
		$expected = 'Lorem ipsum dol..';
		$this->assertEquals($expected, $output);
	}

	/**
	 * Test for Str::truncate()
	 *
	 * @test
	 * @dataProvider truncateProvider
	 */
	public function testTruncateNotHtml($limit, $string)
	{
		$string = '<h1>'.$string.'</h1>';

		$output = Str::truncate($string, $limit, '...', false);
		$expected = '<h1>Lorem ipsum...';
		$this->assertEquals($expected, $output);

		$output = Str::truncate($string, $limit, '...', true);
		$expected = '<h1>Lorem ipsum dol...</h1>';
		$this->assertEquals($expected, $output);
	}

	/**
	 * Test for Str::truncate()
	 *
	 * @test
	 * @dataProvider truncateProvider
	 */
	public function testTruncateIsHtml($limit, $string)
	{
		$string = '<h1>'.$string.'</h1>';

		$output = Str::truncate($string, $limit, '...', true);
		$expected = '<h1>Lorem ipsum dol...</h1>';
		$this->assertEquals($expected, $output);
	}

	/**
	 * Test for Str::truncate()
	 *
	 * @test
	 * @dataProvider truncateProvider
	 */
	public function testTruncateMultipleTags($limit, $string)
	{
		$limit = 400;
		$string = '<p><strong>'.$string.'</strong></p>';

		$output = Str::truncate($string, $limit, '...', true);
		$this->assertEquals($string, $output);
	}

	/**
	 * Test for Str::increment()
	 *
	 * @test
	 */
	public function testIncrement()
	{
		$values = array('valueA', 'valueB', 'valueC');

		for ($i = 0; $i < count($values); $i ++)
		{
			$output = Str::increment($values[$i], $i);
			$expected = $values[$i].'_'.$i;

			$this->assertEquals($expected, $output);
		}
	}

	/**
	 * Test for Str::lower()
	 *
	 * @test
	 */
	public function testLower()
	{
		$output = Str::lower('HELLO WORLD');
		$expected = "hello world";

		$this->assertEquals($expected, $output);
	}

	/**
	 * Test for Str::upper()
	 *
	 * @test
	 */
	public function testUpper()
	{
		$output = Str::upper('hello world');
		$expected = "HELLO WORLD";

		$this->assertEquals($expected, $output);
	}

	/**
	 * Test for Str::lcfirst()
	 *
	 * @test
	 */
	public function testLcfirst()
	{
		$output = Str::lcfirst('Hello World');
		$expected = "hello World";

		$this->assertEquals($expected, $output);
	}

	/**
	 * Test for Str::ucfirst()
	 *
	 * @test
	 */
	public function testUcfirst()
	{
		$output = Str::ucfirst('hello world');
		$expected = "Hello world";

		$this->assertEquals($expected, $output);
	}

	/**
	 * Test for Str::ucwords()
	 *
	 * @test
	 */
	public function testUcwords()
	{
		$output = Str::ucwords('hello world');
		$expected = "Hello World";

		$this->assertEquals($expected, $output);
	}

	/**
	 * Test for Str::random()
	 *
	 * @test
	 */
	public function testRandom()
	{
		// testing length
		$output = Str::random('alnum', 34);
		$this->assertEquals(34, strlen($output));

		// testing alnum
		$output = Str::random('alnum', 15);
		$this->assertTrue(ctype_alnum($output));

		// testing numeric
		$output = Str::random('numeric', 20);
		$this->assertTrue(ctype_digit($output));

		// testing alpha
		$output = Str::random('alpha', 35);
		$this->assertTrue(ctype_alpha($output));

		// testing nozero
		$output = Str::random('nozero', 22);
		$this->assertFalse(strpos($output, '0'));
	}
}