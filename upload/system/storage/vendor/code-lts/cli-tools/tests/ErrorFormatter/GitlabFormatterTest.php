<?php

declare(strict_types = 1);

/*
 * (c) Copyright (c) 2016-2021 Ondřej Mirtes <ondrej@mirtes.cz>
 *
 * This source file is subject to the MIT license.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

namespace CodeLts\CliTools\Tests\ErrorFormatter;

use CodeLts\CliTools\File\SimpleRelativePathHelper;
use CodeLts\CliTools\Tests\ErrorFormatterTestCase;
use CodeLts\CliTools\ErrorFormatter\GitlabErrorFormatter;

class GitlabFormatterTest extends ErrorFormatterTestCase
{

    /**
     * @return iterable<array>
     */
    public function dataFormatterOutputProvider(): iterable
    {
        yield [
            'No errors',
            0,
            0,
            0,
            '[]',
        ];

        yield [
            'One file error',
            1,
            1,
            0,
            '[
    {
        "description": "Foo",
        "fingerprint": "e82b7e1f1d4255352b19ecefa9116a12f129c7edb4351cf2319285eccdb1565e",
        "severity": "major",
        "location": {
            "path": "with space/and unicode 😃/project/folder with unicode 😃/file name with \"spaces\" and unicode 😃.php",
            "lines": {
                "begin": 4
            }
        }
    }
]',
        ];

        yield [
            'One generic error',
            1,
            0,
            1,
            '[
    {
        "description": "first generic error",
        "fingerprint": "53ed216d77c9a9b21d9535322457ca7d7b037d6596d76484b3481f161adfd96f",
        "severity": "major",
        "location": {
            "path": "",
            "lines": {
                "begin": 0
            }
        }
    }
]',
        ];

        yield [
            'Multiple file errors',
            1,
            4,
            0,
            '[
    {
        "description": "Bar\nBar2",
        "fingerprint": "034b4afbfb347494c14e396ed8327692f58be4cd27e8aff5f19f4194934db7c9",
        "severity": "major",
        "location": {
            "path": "with space/and unicode 😃/project/folder with unicode 😃/file name with \"spaces\" and unicode 😃.php",
            "lines": {
                "begin": 2
            }
        }
    },
    {
        "description": "Foo",
        "fingerprint": "e82b7e1f1d4255352b19ecefa9116a12f129c7edb4351cf2319285eccdb1565e",
        "severity": "major",
        "location": {
            "path": "with space/and unicode 😃/project/folder with unicode 😃/file name with \"spaces\" and unicode 😃.php",
            "lines": {
                "begin": 4
            }
        }
    },
    {
        "description": "Foo",
        "fingerprint": "93c79740ed8c6fbaac2087e54d6f6f67fc0918e3ff77840530f32e19857ef63c",
        "severity": "major",
        "location": {
            "path": "with space/and unicode \ud83d\ude03/project/foo.php",
            "lines": {
                "begin": 1
            }
        }
    },
    {
        "description": "Bar\nBar2",
        "fingerprint": "829f6c782152fdac840b39208c5b519d18e51bff2c601b6197812fffb8bcd9ed",
        "severity": "major",
        "location": {
            "path": "with space/and unicode \ud83d\ude03/project/foo.php",
            "lines": {
                "begin": 5
            }
        }
    }
]',
        ];
        yield [
            'Multiple file errors, including error with line=null',
            1,
            5,
            0,
            '[
    {
        "description": "Bar\nBar2",
        "fingerprint": "034b4afbfb347494c14e396ed8327692f58be4cd27e8aff5f19f4194934db7c9",
        "severity": "major",
        "location": {
            "path": "with space/and unicode 😃/project/folder with unicode 😃/file name with \"spaces\" and unicode 😃.php",
            "lines": {
                "begin": 2
            }
        }
    },
    {
        "description": "Foo",
        "fingerprint": "e82b7e1f1d4255352b19ecefa9116a12f129c7edb4351cf2319285eccdb1565e",
        "severity": "major",
        "location": {
            "path": "with space/and unicode 😃/project/folder with unicode 😃/file name with \"spaces\" and unicode 😃.php",
            "lines": {
                "begin": 4
            }
        }
    },
    {
        "description": "Bar\nBar2",
        "fingerprint": "52d22d9e64bd6c6257b7a0d170ed8c99482043aeedd68c52bac081a80da9800a",
        "severity": "major",
        "location": {
            "path": "with space/and unicode \ud83d\ude03/project/foo.php",
            "lines": {
                "begin": 0
            }
        }
    },
    {
        "description": "Foo",
        "fingerprint": "93c79740ed8c6fbaac2087e54d6f6f67fc0918e3ff77840530f32e19857ef63c",
        "severity": "major",
        "location": {
            "path": "with space/and unicode \ud83d\ude03/project/foo.php",
            "lines": {
                "begin": 1
            }
        }
    },
    {
        "description": "Bar\nBar2",
        "fingerprint": "829f6c782152fdac840b39208c5b519d18e51bff2c601b6197812fffb8bcd9ed",
        "severity": "major",
        "location": {
            "path": "with space/and unicode \ud83d\ude03/project/foo.php",
            "lines": {
                "begin": 5
            }
        }
    }
]',
        ];

        yield [
            'Multiple generic errors',
            1,
            0,
            2,
            '[
    {
        "description": "first generic error",
        "fingerprint": "53ed216d77c9a9b21d9535322457ca7d7b037d6596d76484b3481f161adfd96f",
        "severity": "major",
        "location": {
            "path": "",
            "lines": {
                "begin": 0
            }
        }
    },
    {
        "description": "second generic error",
        "fingerprint": "f49870714e8ce889212aefb50f718f88ae63d00dd01c775b7bac86c4466e96f0",
        "severity": "major",
        "location": {
            "path": "",
            "lines": {
                "begin": 0
            }
        }
    }
]',
        ];

        yield [
            'Multiple file, multiple generic errors',
            1,
            4,
            2,
            '[
    {
        "description": "Bar\nBar2",
        "fingerprint": "034b4afbfb347494c14e396ed8327692f58be4cd27e8aff5f19f4194934db7c9",
        "severity": "major",
        "location": {
            "path": "with space/and unicode \ud83d\ude03/project/folder with unicode \ud83d\ude03/file name with \"spaces\" and unicode \ud83d\ude03.php",
            "lines": {
                "begin": 2
            }
        }
    },
    {
        "description": "Foo",
        "fingerprint": "e82b7e1f1d4255352b19ecefa9116a12f129c7edb4351cf2319285eccdb1565e",
        "severity": "major",
        "location": {
            "path": "with space/and unicode \ud83d\ude03/project/folder with unicode \ud83d\ude03/file name with \"spaces\" and unicode \ud83d\ude03.php",
            "lines": {
                "begin": 4
            }
        }
    },
    {
        "description": "Foo",
        "fingerprint": "93c79740ed8c6fbaac2087e54d6f6f67fc0918e3ff77840530f32e19857ef63c",
        "severity": "major",
        "location": {
            "path": "with space/and unicode \ud83d\ude03/project/foo.php",
            "lines": {
                "begin": 1
            }
        }
    },
    {
        "description": "Bar\nBar2",
        "fingerprint": "829f6c782152fdac840b39208c5b519d18e51bff2c601b6197812fffb8bcd9ed",
        "severity": "major",
        "location": {
            "path": "with space/and unicode \ud83d\ude03/project/foo.php",
            "lines": {
                "begin": 5
            }
        }
    },
    {
        "description": "first generic error",
        "fingerprint": "53ed216d77c9a9b21d9535322457ca7d7b037d6596d76484b3481f161adfd96f",
        "severity": "major",
        "location": {
            "path": "",
            "lines": {
                "begin": 0
            }
        }
    },
    {
        "description": "second generic error",
        "fingerprint": "f49870714e8ce889212aefb50f718f88ae63d00dd01c775b7bac86c4466e96f0",
        "severity": "major",
        "location": {
            "path": "",
            "lines": {
                "begin": 0
            }
        }
    }
]',
        ];
    }

    /**
     * @dataProvider dataFormatterOutputProvider
     *
     * @param string $message
     * @param int    $exitCode
     * @param int    $numFileErrors
     * @param int    $numGenericErrors
     * @param string $expected
     *
     */
    public function testFormatErrors(
        string $message,
        int $exitCode,
        int $numFileErrors,
        int $numGenericErrors,
        string $expected
    ): void {
        $formatter = new GitlabErrorFormatter(new SimpleRelativePathHelper('/data/folder'));

        $this->assertSame(
            $exitCode,
            $formatter->formatErrors(
                $this->getAnalysisResult($numFileErrors, $numGenericErrors),
                $this->getOutput()
            ),
            sprintf('%s: response code do not match', $message)
        );

        $this->assertJsonStringEqualsJsonString($expected, $this->getOutputContent(), sprintf('%s: output do not match', $message));
    }

}
