<?php

namespace RandomLib;

use SecurityLib\Strength;

class FactoryTest extends \PHPUnit_Framework_TestCase {

    public function testConstruct() {
        $factory = new Factory;
        $this->assertTrue($factory instanceof Factory);
    }

    public function testGetGeneratorFallback() {
        $factory = new Factory;
        $generator = $factory->getGenerator(new Strength(Strength::VERYLOW));
        $mixer = call_user_func(array(
            get_class($generator->getMixer()),
            'getStrength'
        ));
        $this->assertTrue($mixer->compare(new Strength(Strength::LOW)) <= 0);
    }

    /**
     * @covers RandomLib\Factory::getMediumStrengthGenerator
     * @covers RandomLib\Factory::getGenerator
     * @covers RandomLib\Factory::findMixer
     */
    public function testGetMediumStrengthGenerator() {
        $factory = new Factory;
        $generator = $factory->getMediumStrengthGenerator();
        $this->assertTrue($generator instanceof Generator);
        $mixer = call_user_func(array(
            get_class($generator->getMixer()),
            'getStrength'
        ));
        $this->assertTrue($mixer->compare(new Strength(Strength::MEDIUM)) <= 0);
        foreach ($generator->getSources() as $source) {
            $strength = call_user_func(array(get_class($source), 'getStrength'));
            $this->assertTrue($strength->compare(new Strength(Strength::MEDIUM)) >= 0);
        }
    }


}
