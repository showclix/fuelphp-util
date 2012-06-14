<?php

use Fuel\Util\Arr;

class ArrTest extends PHPUnit_Framework_TestCase
{
	public static function personProvider()
	{
		return array(
			array(
				array(
					"name" => "Jack",
					"age" => "21",
					"weight" => 200,
					"location" => array(
						"city" => "Pittsburgh",
						"state" => "PA",
						"country" => "US"
					),
				),
			),
		);
	}

	public static function collectionProvider()
	{
		$object = new stdClass;
		$object->id = 7;
		$object->name = 'Bert';
		$object->surname = 'Visser';

		return array(
			array(
				array(
					array(
						'id' => 2,
						'name' => 'Bill',
						'surname' => 'Cosby',
					),
					array(
						'id' => 5,
						'name' => 'Chris',
						'surname' => 'Rock',
					),
					$object,
				),
			),
		);
	}

	/**
	 * Test Arr::pluck()
	 *
	 * @test
	 * @dataProvider collectionProvider
	 */
	public function testPluck($collection)
	{
		$output = Arr::pluck($collection, 'id');
		$expected = array(2, 5, 7);
		$this->assertEquals($expected, $output);
	}

	/**
	 * Test Arr::pluck()
	 *
	 * @test
	 * @dataProvider collectionProvider
	 */
	public function testPluckWithIndex($collection)
	{
		$output = Arr::pluck($collection, 'name', 'id');
		$expected = array(2 => 'Bill', 5 => 'Chris', 7 => 'Bert');
		$this->assertEquals($expected, $output);
	}

	/**
	 * Tests Arr::assoc_to_keyval()
	 *
	 * @test
	 */
	public function testAssocToKeyval()
	{
		$assoc = array(
			array(
				'color' => 'red',
				'rank' => 4,
				'name' => 'Apple',
				),
			array(
				'color' => 'yellow',
				'rank' => 3,
				'name' => 'Banana',
				),
			array(
				'color' => 'purple',
				'rank' => 2,
				'name' => 'Grape',
				),
			);

		$expected = array(
			'red' => 'Apple',
			'yellow' => 'Banana',
			'purple' => 'Grape',
			);
		$output = Arr::assocToKeyval($assoc, 'color', 'name');
		$this->assertEquals($expected, $output);
	}

	/**
	 * Tests Arr::keyExists()
	 *
	 * @test
	 * @dataProvider personProvider
	 */
	public function testKeyExistsWithKeyFound($person)
	{
		$expected = true;
		$output = Arr::keyExists($person, "name");
		$this->assertEquals($expected, $output);
	}

	/**
	 * Tests Arr::keyExists()
	 *
	 * @test
	 * @dataProvider personProvider
	 */
	public function testKeyExistsWithKeyNotFound($person)
	{
		$expected = false;
		$output = Arr::keyExists($person, "unknown");
		$this->assertEquals($expected, $output);
	}

	/**
	 * Tests Arr::keyExists()
	 *
	 * @test
	 * @dataProvider personProvider
	 */
	public function testKeyExistsWithDotSeparatedKey($person)
	{
		$expected = true;
		$output = Arr::keyExists($person, "location.city");
		$this->assertEquals($expected, $output);
	}

	/**
	 * Tests Arr::get()
	 *
	 * @test
	 * @dataProvider personProvider
	 */
	public function testGetWithElementFound($person)
	{
		$expected = "Jack";
		$output = Arr::get($person, "name", "Unknown Name");
		$this->assertEquals($expected, $output);
	}

	/**
	 * Tests Arr::get()
	 *
	 * @test
	 * @dataProvider personProvider
	 */
	public function testGetWithElementNotFound($person)
	{
		$expected = "Unknown job";
		$output = Arr::get($person, "job", "Unknown job");
		$this->assertEquals($expected, $output);
	}

	/**
	 * Tests Arr::get()
	 *
	 * @test
	 * @dataProvider personProvider
	 */
	public function testGetWithDotDeparatedKey($person)
	{
		$expected = "Pittsburgh";
		$output = Arr::get($person, "location.city", "Unknown City");
		$this->assertEquals($expected, $output);

	}

	/**
	 * Tests Arr::get()
	 *
	 * @test
	 * @expectedException InvalidArgumentException
	 */
	public function testGetThrowsExceptionWhenArrayIsNotAnArray()
	{
		$output = Arr::get('Jack', 'name', 'Unknown Name');
	}

	/**
	 * Tests Arr::get()
	 *
	 * @test
	 * @dataProvider personProvider
	 */
	public function testGetWhenDotNotatedKeyIsNotArray($person)
	{
		$expected = "Unknown Name";
		$output = Arr::get($person, 'foo.first', 'Unknown Name');
		$this->assertEquals($expected, $output);
	}

	/**
	 * Tests Arr::get()
	 *
	 * @test
	 * @dataProvider personProvider
	 */
	public function testGetWithAllElementsFound($person)
	{
		$expected = array(
			'name' => 'Jack',
			'weight' => 200,
		);
		$output = Arr::get($person, array('name', 'weight'), 'Unknown');
		$this->assertEquals($expected, $output);
	}


	/**
	 * Tests Arr::get()
	 *
	 * @test
	 * @dataProvider personProvider
	 */
	public function test_get_with_all_elements_not_found($person)
	{
		$expected = array(
			'name' => 'Jack',
			'height' => 'Unknown',
		);
		$output = Arr::get($person, array('name', 'height'), 'Unknown');
		$this->assertEquals($expected, $output);
	}

	/**
	 * Tests Arr::get()
	 *
	 * @test
	 * @dataProvider personProvider
	 */
	public function testGetWhenKeysIsNotAnArray($person)
	{
		$expected = 'Jack';
		$output = Arr::get($person, 'name', 'Unknown');
		$this->assertEquals($expected, $output);
	}

	/**
	 * Tests Arr::flatten()
	 *
	 * @test
	 */
	public function test_flatten()
	{
		$indexed = array ( array('a'), array('b'), array('c') );

		$expected = array(
			"0_0" => "a",
			"1_0" => "b",
			"2_0" => "c",
		);

		$output = Arr::flatten($indexed, '_');
		$this->assertEquals($expected, $output);
	}

	/**
	 * Tests Arr::flattenAssoc()
	 *
	 * @test
	 */
	public function testFlattenAssoc()
	{
		$people = array(
			array(
				"name" => "Jack",
				"age" => 21
			),
			array(
				"name" => "Jill",
				"age" => 23
			)
		);

		$expected = array(
			"0:name" => "Jack",
			"0:age" => 21,
			"1:name" => "Jill",
			"1:age" => 23
		);

		$output = Arr::flattenAssoc($people);
		$this->assertEquals($expected, $output);
	}

	/**
	 * Tests Arr::insert()
	 *
	 * @test
	 */
	public function testInsert()
	{
		$people = array("Jack", "Jill");

		$expected = array("Humpty", "Jack", "Jill");
		$output = Arr::insert($people, "Humpty", 0);

		$this->assertEquals(true, $output);
		$this->assertEquals($expected, $people);
	}

	/**
	 * Tests Arr::insert()
	 *
	 * @test
	 */
	public function testInsertWithIndexOutOfRange()
	{
		$people = array("Jack", "Jill");

		$output = Arr::insert($people, "Humpty", 4);

		$this->assertFalse($output);
	}

	/**
	 * Tests Arr::insertAfterKey()
	 *
	 * @test
	 */
	public function testInsertAfterKeyThatExists()
	{
		$people = array("Jack", "Jill");

		$expected = array("Jack", "Jill", "Humpty");
		$output = Arr::insertAfterKey($people, "Humpty", 1);

		$this->assertTrue($output);
		$this->assertEquals($expected, $people);
	}

	/**
	 * Tests Arr::insert_after_key()
	 *
	 * @test
	 */
	public function testInsertAfterKeyThatDoesNotExist()
	{
		$people = array("Jack", "Jill");
		$output = Arr::insertAfterKey($people, "Humpty", 6);
		$this->assertFalse($output);
	}

	/**
	 * Tests Arr::insertAfterValue()
	 *
	 * @test
	 */
	public function testInsertAfterValueThatExists()
	{
		$people = array("Jack", "Jill");
		$expected = array("Jack", "Humpty", "Jill");
		$output = Arr::insertAfterValue($people, "Humpty", "Jack");
		$this->assertTrue($output);
		$this->assertEquals($expected, $people);
	}

	/**
	 * Tests Arr::insertAfterValue()
	 *
	 * @test
	 */
	public function testinsertAfterValueThatDoesNotExists()
	{
		$people = array("Jack", "Jill");
		$output = Arr::insertAfterValue($people, "Humpty", "Joe");
		$this->assertFalse($output);
	}

	/**
	 * Tests Arr::filterPrefixed()
	 *
	 * @test
	 */
	public function testFilterPrefixed()
	{
		$arr = array('foo' => 'baz', 'prefix_bar' => 'yay');

		$output = Arr::filterPrefixed($arr, 'prefix_');
		$this->assertEquals(array('bar' => 'yay'), $output);
	}

	/**
	 * Tests Arr::sort()
	 *
	 * @test
	 * @expectedException InvalidArgumentException
	 */
	public function testSortOfNonArray()
	{
		$sorted = Arr::sort('not an array', 'foo.key');
	}

	public function sortProvider()
	{
		return array(
			array(
				// Unsorted Array
				array(
					array(
						'info' => array(
							'pet' => array(
								'type' => 'dog'
							)
						),
					),
					array(
						'info' => array(
							'pet' => array(
								'type' => 'fish'
							)
						),
					),
					array(
						'info' => array(
							'pet' => array(
								'type' => 'cat'
							)
						),
					),
				),

				// Sorted Array
				array(
					array(
						'info' => array(
							'pet' => array(
								'type' => 'cat'
							)
						),
					),
					array(
						'info' => array(
							'pet' => array(
								'type' => 'dog'
							)
						),
					),
					array(
						'info' => array(
							'pet' => array(
								'type' => 'fish'
							)
						),
					),
				)
			)
		);
	}

	/**
	 * Tests Arr::sort()
	 *
	 * @test
	 * @dataProvider sortProvider
	 */
	public function testSortAsc($data, $expected)
	{
		$this->assertEquals(Arr::sort($data, 'info.pet.type', 'asc'), $expected);
	}

	/**
	 * Tests Arr::sort()
	 *
	 * @test
	 * @dataProvider sortProvider
	 */
	public function testSortDesc($data, $expected)
	{
		$expected = array_reverse($expected);
		$this->assertEquals(Arr::sort($data, 'info.pet.type', 'desc'), $expected);
	}

	/**
	 * Tests Arr::sort()
	 *
	 * @test
	 * @dataProvider sortProvider
	 * @expectedException InvalidArgumentException
	 */
	public function testSortInvalidDirection($data, $expected)
	{
		$this->assertEquals(Arr::sort($data, 'info.pet.type', 'downer'), $expected);
	}

	public function testSortEmpty()
	{
		$expected = array();
		$output = Arr::sort(array(), 'test', 'test');
		$this->assertEquals($expected, $output);
	}

	/**
	 * Tests Arr::filterKeys()
	 *
	 * @test
	 */
	public function testFilterKeys()
	{
		$data = array(
			'epic' => 'win',
			'weak' => 'sauce',
			'foo' => 'bar'
		);
		$expected = array(
			'epic' => 'win',
			'foo' => 'bar'
		);
		$expected_remove = array(
			'weak' => 'sauce',
		);
		$keys = array('epic', 'foo');
		$this->assertEquals(Arr::filterKeys($data, $keys), $expected);
		$this->assertEquals(Arr::filterKeys($data, $keys, true), $expected_remove);
	}

	/**
	 * Tests Arr::prepend()
	 *
	 * @test
	 */
	public function testPrepend()
	{
		$arr = array(
			'two' => 2,
			'three' => 3,
		);
		$expected = array(
			'one' => 1,
			'two' => 2,
			'three' => 3,
		);
		Arr::prepend($arr, 'one', 1);
		$this->assertEquals($expected, $arr);
	}

	/**
	 * Tests Arr::prepend()
	 *
	 * @test
	 */
	public function testPrependArray()
	{
		$arr = array(
			'two' => 2,
			'three' => 3,
		);
		$expected = array(
			'one' => 1,
			'two' => 2,
			'three' => 3,
		);
		Arr::prepend($arr, array('one' => 1));
		$this->assertEquals($expected, $arr);
	}

	/**
	 * Tests Arr::isMulti()
	 *
	 * @test
	 */
	public function testMultidimensionalArray()
	{
		// Single array
		$arr_single = array('one' => 1, 'two' => 2);
		$this->assertFalse(Arr::isMulti($arr_single));

		// Multi-dimensional array
		$arr_multi = array('one' => array('test' => 1), 'two' => array('test' => 2), 'three' => array('test' => 3));
		$this->assertTrue(Arr::isMulti($arr_multi));

		// Multi-dimensional array (not all elements are arrays)
		$arr_multi_strange = array('one' => array('test' => 1), 'two' => array('test' => 2), 'three' => 3);
		$this->assertTrue(Arr::isMulti($arr_multi_strange, false));
		$this->assertFalse(Arr::isMulti($arr_multi_strange, true));
	}
}