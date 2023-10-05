<?php

declare(strict_types = 1);

/* The contents of this file is free and unencumbered software released into the
 * public domain.
 * For more information, please refer to <http://unlicense.org/>
 */
namespace Wdes\phpI18nL10n\Tests;

use PHPUnit\Framework\TestCase;
use Wdes\phpI18nL10n\plugins\MoReader;
use Wdes\phpI18nL10n\Launcher;

/**
 * Test class for Utils
 * @author William Desportes <williamdes@wdes.fr>
 * @license Unlicense
 */
class LauncherTest extends TestCase
{

    /**
     * test for Launcher::gettext
     *
     * @return void
     */
    public function testGetText(): void
    {
        $S       = DIRECTORY_SEPARATOR;
        $dataDir = __DIR__ . $S . 'data' . $S;

        $moReader = new MoReader();
        $moReader->readFile($dataDir . 'abc.mo');
        Launcher::setPlugin($moReader);
        $this->assertSame('Traduis ça', Launcher::gettext('Translate this'));
    }

    /**
     * test for Launcher::getPlugin
     *
     * @return void
     */
    public function testGetPlugin(): void
    {
        $S       = DIRECTORY_SEPARATOR;
        $dataDir = __DIR__ . $S . 'data' . $S;

        $moReader = new MoReader();
        $moReader->readFile($dataDir . 'abc.mo');
        Launcher::setPlugin($moReader);
        $this->assertSame($moReader, Launcher::getPlugin());
    }

}
