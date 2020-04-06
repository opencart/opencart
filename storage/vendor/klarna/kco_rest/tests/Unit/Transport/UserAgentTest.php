<?php
/**
 * Copyright 2014 Klarna AB
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * File containing tests for the UserAgent class.
 */

namespace Klarna\Rest\Tests\Unit\Transport;

use Klarna\Rest\Transport\UserAgent;

/**
 * Unit test cases for the UserAgent class.
 */
class UserAgentTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var UserAgent
     */
    protected $agent;

    /**
     * Set up the test fixtures
     */
    protected function setUp()
    {
        $this->agent = new UserAgent();
    }

    /**
     * Make sure the default user agent components are present.
     *
     * @return void
     */
    public function testCreateDefault()
    {
        $agent = UserAgent::createDefault();
        $text = $agent->__toString();

        $this->assertContains(
            'Language/PHP_' . phpversion(),
            $text,
            'No PHP language component present'
        );

        $this->assertContains(
            'OS/' . php_uname('s') . '_' . php_uname('r'),
            $text,
            'No OS component present'
        );

        $this->assertContains(
            'Library/' . UserAgent::NAME . '_' . UserAgent::VERSION,
            $text,
            'No Library component present',
            false
        );
    }

    /**
     * Make sure the key and component are present in the user agent.
     *
     * @return void
     */
    public function testSetField()
    {
        $this->agent->setField('key', 'component');

        $this->assertEquals('key/component', strval($this->agent));
    }

    /**
     * Make sure the key, component and version are present.
     *
     * @return void
     */
    public function testSetFieldVersion()
    {
        $this->agent->setField('key', 'component', '1.0.0');

        $this->assertEquals('key/component_1.0.0', strval($this->agent));
    }

    /**
     * Make sure the key, component, version and options are present.
     *
     * @return void
     */
    public function testSetFieldOptions()
    {
        $this->agent->setField('key', 'component', '1.0.0', ['attr']);

        $this->assertEquals(
            'key/component_1.0.0 (attr)',
            strval($this->agent)
        );
    }

    /**
     * Make sure the key, component, version and multiple options are present.
     *
     * @return void
     */
    public function testSetFieldTwoOptions()
    {
        $this->agent->setField('key', 'component', '1.0.0', ['attr1', 'attr2']);

        $this->assertEquals(
            'key/component_1.0.0 (attr1; attr2)',
            strval($this->agent)
        );
    }
}
