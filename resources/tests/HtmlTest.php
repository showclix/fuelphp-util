<?php

use Fuel\Util\Html;

class HtmlTest extends PHPUnit_Framework_TestCase
{
	/**
	 * Tests Html::mailTo()
	 *
	 * @test
	 */
	public function testMailTo()
	{
		$output = Html::mailTo('test@test.com');
		$expected = '<a href="mailto:test@test.com">test@test.com</a>';
		$this->assertEquals($expected, $output);

		$output = Html::mailTo('test@test.com', 'Email');
		$expected = '<a href="mailto:test@test.com">Email</a>';
		$this->assertEquals($expected, $output);

		$output = Html::mailTo('test@test.com', NULL, 'Test');
		$expected = '<a href="mailto:test@test.com?subject=Test">test@test.com</a>';
		$this->assertEquals($expected, $output);

		$output = Html::mailTo('test@test.com', 'Test', 'Test');
		$expected = '<a href="mailto:test@test.com?subject=Test">Test</a>';
		$this->assertEquals($expected, $output);
	}

	/**
	 * Tests Html::ul() & Html::ol()
	 *
	 * @test
	 */
	public function testLists()
	{
		$list = array('one', 'two');

		$output = Html::ul($list);
		$expected = '<ul>'.PHP_EOL
					.'	<li>one</li>'.PHP_EOL
					.'	<li>two</li>'.PHP_EOL
					.'</ul>'.PHP_EOL;
		$this->assertEquals($expected, $output);

		$output = Html::ol($list);
		$expected = '<ol>'.PHP_EOL
					.'	<li>one</li>'.PHP_EOL
					.'	<li>two</li>'.PHP_EOL
					.'</ol>'.PHP_EOL;
		$this->assertEquals($expected, $output);
	}
}