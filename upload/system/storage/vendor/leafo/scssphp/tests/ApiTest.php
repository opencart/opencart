<?php

require_once __DIR__ . "/../scss.inc.php";

class ApiTest extends PHPUnit_Framework_TestCase
{
	public function setUp()
	{
		$this->scss = new scssc();
	}

	public function testUserFunction()
	{
		$this->scss->registerFunction("add-two", function ($args) {
			list($a, $b) = $args;
			return $a[1] + $b[1];
		});

		$this->assertEquals(
			"result: 30;",
			$this->compile("result: add-two(10, 20);")
		);
	}
	
	public function testImportMissing()
	{
		$this->assertEquals(
			'@import "missing";',
			$this->compile('@import "missing";')
		);
	}
	
	public function testImportCustomCallback()
	{
		$this->scss->addImportPath(function ($path) {
			return __DIR__ . '/inputs/' . str_replace('.css', '.scss', $path);
		});
		
		$this->assertEquals(
			trim(file_get_contents(__DIR__ . '/outputs/variables.css')),
			$this->compile('@import "variables.css";')
		);
	}

	/**
	 * @dataProvider provideSetVariables
	 */
	public function testSetVariables($expected, $scss, $variables)
	{
		$this->scss->setVariables($variables);

		$this->assertEquals($expected, $this->compile($scss));
	}

	public function provideSetVariables()
	{
		return array(
			array(
				".magic {\n  color: red;\n  width: 760px; }",
				'.magic { color: $color; width: $base - 200; }',
				array(
					'color' => 'red',
					'base'  => '960px',
				),
			),
			array(
				".logo {\n  color: #808080; }",
				'.logo { color: desaturate($primary, 100%); }',
				array(
					'primary' => '#ff0000',
				),
			),
		);
	}

	public function compile($str)
	{
		return trim($this->scss->compile($str));
	}
}
