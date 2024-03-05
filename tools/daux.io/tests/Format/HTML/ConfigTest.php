<?php
namespace Todaymade\Daux\Format\HTML;

use PHPUnit\Framework\TestCase;
use Todaymade\Daux\Config as MainConfig;

class ConfigTest extends TestCase
{
    public function testHTMLConfigCreation()
    {
        $config = new MainConfig(['html' => ['edit_on' => 'test']]);

        $this->assertInstanceOf(Config::class, $config->getHTML());
        $this->assertEquals('test', $config->getHTML()['edit_on']);
    }

    public static function providerEditOn()
    {
        $github_result = ['name' => 'GitHub', 'basepath' => 'https://github.com/dauxio/daux.io/blob/master/docs'];

        return [
            [[], null],
            [['edit_on_github' => 'dauxio/daux.io/blob/master/docs'], $github_result],

            // Allow formatting in many ways
            [['edit_on_github' => 'dauxio/daux.io/blob/master/docs/'], $github_result],
            [['edit_on_github' => '/dauxio/daux.io/blob/master/docs'], $github_result],
            [['edit_on_github' => 'https://github.com/dauxio/daux.io/blob/master/docs/'], $github_result],
            [['edit_on_github' => 'http://github.com/dauxio/daux.io/blob/master/docs/'], $github_result],

            // Fallback if a string is provided to 'edit_on'
            [['edit_on' => 'dauxio/daux.io/blob/master/docs'], $github_result],

            // Support any provider
            [
                ['edit_on' => ['name' => 'Bitbucket', 'basepath' => 'https://bitbucket.org/dauxio/daux.io/src/master/docs/']],
                ['name' => 'Bitbucket', 'basepath' => 'https://bitbucket.org/dauxio/daux.io/src/master/docs'],
            ],
        ];
    }

    /**
     * @dataProvider providerEditOn
     *
     * @param mixed $value
     * @param mixed $expected
     */
    public function testEditOn($value, $expected)
    {
        $config = new Config($value);

        $this->assertEquals($expected, $config->getEditOn());
    }
}
