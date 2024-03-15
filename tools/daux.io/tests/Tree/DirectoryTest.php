<?php
namespace Todaymade\Daux\Tree;

use PHPUnit\Framework\TestCase;
use Todaymade\Daux\ConfigBuilder;

class DirectoryTest extends TestCase
{
    public static function providerSort()
    {
        return [
            [['005_Fifth', '01_First'], ['01_First', '005_Fifth']],
            [['005_Fifth', 'Another', '01_First'], ['01_First', '005_Fifth', 'Another']],
            [['005_Fifth', 'Another', '-Sticky', '01_First'], ['01_First', '005_Fifth', 'Another', '-Sticky']],
            [['01_before', '-Down'], ['01_before', '-Down']],
            [['01_before', '-Down-after', '-Down'], ['01_before', '-Down', '-Down-after']],
            [['01_numeric', '01_before'], ['01_before', '01_numeric']],
            [['A_File', '01_A_File'], ['01_A_File', 'A_File']],
            [['A_File', '01_Continuing', '-01_Coming', '-02_Soon'], ['01_Continuing', 'A_File', '-01_Coming', '-02_Soon']],
            [['+A_File', '01_Continuing', '+01_Coming', '-02_Soon'], ['+01_Coming', '+A_File', '01_Continuing', '-02_Soon']],
            [['01_Getting_Started', 'API_Calls', '200_Something_Else-Cool', '_5_Ways_to_Be_Happy'], ['01_Getting_Started', '200_Something_Else-Cool', '_5_Ways_to_Be_Happy', 'API_Calls']],
            [['01_Getting_Started', 'API_Calls', 'index', '200_Something_Else-Cool', '_5_Ways_to_Be_Happy'], ['index', '01_Getting_Started', '200_Something_Else-Cool', '_5_Ways_to_Be_Happy', 'API_Calls']],
            [['Before_but_after', 'A_File', 'Continuing'], ['A_File', 'Before_but_after', 'Continuing']],
            [['01_GitHub_Flavored_Markdown', 'Code_Test', '05_Code_Highlighting'], ['01_GitHub_Flavored_Markdown', '05_Code_Highlighting', 'Code_Test']],
        ];
    }

    /**
     * @dataProvider providerSort
     *
     * @param mixed $list
     * @param mixed $expected
     */
    public function testSort($list, $expected)
    {
        shuffle($list);

        $config = ConfigBuilder::withMode()
            ->build();

        $directory = new Directory(new Root($config), 'dir');

        foreach ($list as $value) {
            $entry = new Content($directory, $value);
            $entry->setName($value);
        }

        $directory->sort();

        $final = [];
        foreach ($directory->getEntries() as $obj) {
            $final[] = $obj->getName();
        }

        $this->assertEquals($expected, $final);
    }
}
