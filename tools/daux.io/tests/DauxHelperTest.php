<?php namespace Todaymade\Daux;

use PHPUnit\Framework\TestCase;

class DauxHelperTest extends TestCase
{
    public static function providerGetFilenames()
    {
        return [
            [['Page.html', 'Page'], 'Page.html'],
            [['Page.html', 'Page'], 'Page.md'],
            [['Page.html', 'Page'], 'Page'],
            [['Code_Highlighting.html', 'Code_Highlighting'], '05_Code_Highlighting.md'],
        ];
    }

    /**
     * @dataProvider providerGetFilenames
     *
     * @param mixed $expected
     * @param mixed $node
     */
    public function testGetFilenames($expected, $node)
    {
        $config = ConfigBuilder::withMode()
            ->withValidContentExtensions(['md'])
            ->build();

        $this->assertEquals($expected, DauxHelper::getFilenames($config, $node));
    }
}
