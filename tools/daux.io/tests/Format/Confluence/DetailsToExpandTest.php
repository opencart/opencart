<?php namespace Todaymade\Daux\Format\Confluence;

use PHPUnit\Framework\TestCase;

class DetailsToExpandTest extends TestCase
{
    public static function provideExpandData()
    {
        return [
            'Simple conversion' => [
                <<<'EOD'
                    <details>
                    <summary>Title !</summary>

                    <ul>
                        <li>Item 1</li>
                        <li>Item 2</li>
                    </ul>

                    </details>
                    EOD,
                <<<'EOD'
                    <ac:structured-macro ac:name="expand">
                    <ac:parameter ac:name="title">Title !</ac:parameter>
                    <ac:rich-text-body>

                    <ul>
                        <li>Item 1</li>
                        <li>Item 2</li>
                    </ul>
                    </ac:rich-text-body>
                    </ac:structured-macro>
                    EOD
            ],
            "Doesn't impact other macros" => [
                <<<'EOD'
                    <ac:structured-macro ac:name="info"><ac:rich-text-body>
                        <p>Content</p>
                    </ac:rich-text-body></ac:structured-macro>

                    <details>
                    <summary>Title !</summary>

                    <ul>
                        <li>Item 1</li>
                        <li>Item 2</li>
                    </ul>

                    </details>
                    EOD,
                <<<'EOD'
                    <ac:structured-macro ac:name="info"><ac:rich-text-body>
                        <p>Content</p>
                    </ac:rich-text-body></ac:structured-macro>
                    <ac:structured-macro ac:name="expand">
                    <ac:parameter ac:name="title">Title !</ac:parameter>
                    <ac:rich-text-body>

                    <ul>
                        <li>Item 1</li>
                        <li>Item 2</li>
                    </ul>
                    </ac:rich-text-body>
                    </ac:structured-macro>
                    EOD
            ],
            'Works with confluence code blocks' => [
                <<<'EOD'
                    <ac:structured-macro ac:name="html">
                    <ac:plain-text-body> <![CDATA[
                    <script>
                    console.log("hi friends");
                    </script>
                    ]]></ac:plain-text-body>
                    </ac:structured-macro>

                    <details>
                    <summary>Title !</summary>

                    <ul>
                        <li>Item 1</li>
                        <li>Item 2</li>
                    </ul>

                    </details>
                    EOD,
                <<<'EOD'
                    <ac:structured-macro ac:name="html">
                    <ac:plain-text-body> <![CDATA[
                    <script>
                    console.log("hi friends");
                    </script>
                    ]]></ac:plain-text-body>
                    </ac:structured-macro>
                    <ac:structured-macro ac:name="expand">
                    <ac:parameter ac:name="title">Title !</ac:parameter>
                    <ac:rich-text-body>

                    <ul>
                        <li>Item 1</li>
                        <li>Item 2</li>
                    </ul>
                    </ac:rich-text-body>
                    </ac:structured-macro>
                    EOD
            ],
            "Doesn't convert within confluence code blocks" => [
                <<<'EOD'
                    <ac:structured-macro ac:name="code">
                        <ac:plain-text-body><![CDATA[<b>This is my code</b>
                        <details>
                        <summary>Title !</summary>

                        <ul>
                            <li>Item 1</li>
                            <li>Item 2</li>
                        </ul>

                        </details>
                        ]]></ac:plain-text-body>
                    </ac:structured-macro>

                    <details>
                    <summary>Title !</summary>

                    <ul>
                        <li>Item 1</li>
                        <li>Item 2</li>
                    </ul>

                    </details>
                    EOD,
                <<<'EOD'
                    <ac:structured-macro ac:name="code">
                        <ac:plain-text-body><![CDATA[<b>This is my code</b>
                        <details>
                        <summary>Title !</summary>
                        <ul>
                            <li>Item 1</li>
                            <li>Item 2</li>
                        </ul>
                        </details>
                        ]]></ac:plain-text-body>
                    </ac:structured-macro>
                    <ac:structured-macro ac:name="expand">
                    <ac:parameter ac:name="title">Title !</ac:parameter>
                    <ac:rich-text-body>

                    <ul>
                        <li>Item 1</li>
                        <li>Item 2</li>
                    </ul>
                    </ac:rich-text-body>
                    </ac:structured-macro>
                    EOD
            ],
            "Don't convert without title" => [
                <<<'EOD'
                    <details>

                    <ul>
                        <li>Item 1</li>
                        <li>Item 2</li>
                    </ul>

                    </details>
                    EOD,
                <<<'EOD'
                    <details>
                    <ul>
                        <li>Item 1</li>
                        <li>Item 2</li>
                    </ul>
                    </details>
                    EOD
            ],
            "Don't convert empty blocks" => [
                <<<'EOD'
                    <details></details>
                    EOD,
                <<<'EOD'
                    <details></details>
                    EOD
            ],
            'Convert nested' => [
                <<<'EOD'
                    <details>
                    <summary>Title !</summary>

                    <ul>
                        <li>Item 1</li>
                        <li>Item 2</li>
                    </ul>

                    <details>
                    <summary>Inner title</summary>

                    <p>Some Text</p>

                    </details>
                    </details>
                    EOD,
                <<<'EOD'
                    <ac:structured-macro ac:name="expand">
                    <ac:parameter ac:name="title">Title !</ac:parameter>
                    <ac:rich-text-body>

                    <ul>
                        <li>Item 1</li>
                        <li>Item 2</li>
                    </ul>
                    <ac:structured-macro ac:name="expand">
                    <ac:parameter ac:name="title">Inner title</ac:parameter>
                    <ac:rich-text-body>

                    <p>Some Text</p>
                    </ac:rich-text-body>
                    </ac:structured-macro>
                    </ac:rich-text-body>
                    </ac:structured-macro>
                    EOD
            ],
        ];
    }

    /**
     * @dataProvider provideExpandData
     *
     * @param mixed $input
     * @param mixed $expected
     */
    public function testDetailsToExpand($input, $expected)
    {
        $expander = new DetailsToExpand();

        $this->assertEquals($expected, str_replace("\n\n", "\n", trim($expander->convert($input))));
    }
}
