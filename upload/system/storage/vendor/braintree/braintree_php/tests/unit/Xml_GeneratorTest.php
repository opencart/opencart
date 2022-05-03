<?php
namespace Test\Unit\Xml;

require_once dirname(__DIR__) . '/Setup.php';

use Test\Setup;
use Braintree;

class GeneratorTest extends Setup
{
    public function testSetsTypeAttributeForBooleans()
    {
        $expected = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<root>
 <yes type="boolean">true</yes>
 <no type="boolean">false</no>
</root>

XML;
        $xml = Braintree\Xml::buildXmlFromArray([
            'root' => ['yes' => true, 'no' => false]
        ]);
        $this->assertEquals($expected, $xml);
    }

    public function testCreatesArrays()
    {
        $expected = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<root>
 <stuff type="array">
  <item>foo</item>
  <item>bar</item>
 </stuff>
</root>

XML;
        $xml = Braintree\Xml::buildXmlFromArray([
            'root' => ['stuff' => ['foo', 'bar']]
        ]);
        $this->assertEquals($expected, $xml);
    }

    public function testCreatesWithDashes()
    {
        $expected = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<root>
 <some-stuff>
  <inner-foo type="integer">42</inner-foo>
  <bar-bar-bar type="integer">3</bar-bar-bar>
 </some-stuff>
</root>

XML;
        $xml = Braintree\Xml::buildXmlFromArray([
            'root' => ['someStuff' => ['innerFoo' => 42, 'barBarBar' => 3]]
        ]);
        $this->assertEquals($expected, $xml);
    }

    public function testCreatesArraysWithBooleans()
    {
        $expected = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<root>
 <stuff type="array">
  <item>true</item>
  <item>false</item>
 </stuff>
</root>

XML;
        $xml = Braintree\Xml::buildXmlFromArray([
            'root' => ['stuff' => [true, false]]
        ]);
        $this->assertEquals($expected, $xml);
    }

    public function testHandlesEmptyArrays()
    {
        $expected = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<root>
 <stuff type="array"/>
</root>

XML;
        $xml = Braintree\Xml::buildXmlFromArray([
            'root' => ['stuff' => []]
        ]);
        $this->assertEquals($expected, $xml);
    }

    public function testEscapingSpecialChars()
    {
        $expected = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<root>
 <stuff>&lt;&gt;&amp;'&quot;</stuff>
</root>

XML;
        $xml = Braintree\Xml::buildXmlFromArray([
            'root' => ['stuff' => '<>&\'"']
        ]);
        $this->assertEquals($expected, $xml);
    }

    public function testDoesNotModifyDateTime()
    {
        $date = new \DateTime();
        $date->setTimestamp(strtotime('2016-05-17T21:22:26Z'));
        $date->setTimezone(new \DateTimeZone('Europe/Paris'));

        $originalDate = clone $date;

        $expected = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<root>
 <stuff type="datetime">2016-05-17T21:22:26Z</stuff>
</root>

XML;

        $xml = Braintree\Xml::buildXmlFromArray([
            'root' => ['stuff' => $date]
        ]);

        $this->assertEquals($originalDate, $date);
        $this->assertEquals($expected, $xml);
    }
}
