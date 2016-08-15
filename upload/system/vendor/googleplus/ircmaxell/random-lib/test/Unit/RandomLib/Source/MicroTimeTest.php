<?php

namespace RandomLib\Source;

use SecurityLib\Strength;



class MicroTimeTest extends \PHPUnit_Framework_TestCase {

    public static function provideGenerate() {
        $data = array();
        for ($i = 0; $i < 100; $i += 5) {
            $not = $i > 0 ? str_repeat(chr(0), $i) : chr(0);
            $data[] = array($i, $not);
        }
        return $data;
    }

    /** 
     * Test the initialization of the static counter (!== 0)
     */
    public function testCounterNotNull() {
        $rand = new MicroTime;
        $reflection_class = new \ReflectionClass("RandomLib\Source\MicroTime");
        $static = $reflection_class->getStaticProperties();
        $this->assertTrue($static['counter'] !== 0);
    }
    
    /**
     */
    public function testGetStrength() {
        $strength = new Strength(Strength::VERYLOW);
        $actual = MicroTime::getStrength();
        $this->assertEquals($actual, $strength);
    }

    /**
     * @dataProvider provideGenerate
     */
    public function testGenerate($length, $not) {
        $rand = new MicroTime;
        $stub = $rand->generate($length);
        $this->assertEquals($length, strlen($stub));
        $this->assertNotEquals($not, $stub);
    }

}
