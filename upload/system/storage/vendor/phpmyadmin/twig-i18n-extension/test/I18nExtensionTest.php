<?php

declare(strict_types=1);

/*
 * (c) 2021 phpMyAdmin contributors
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PhpMyAdmin\Tests\Twig\Extensions\Node;

use PhpMyAdmin\Twig\Extensions\I18nExtension;
use Twig\Extension\AbstractExtension;
use Twig\Test\IntegrationTestCase;

class I18nExtensionTest extends IntegrationTestCase
{
    /**
     * @return AbstractExtension[]
     */
    public function getExtensions()
    {
        return [
            new I18nExtension(),
        ];
    }

    /**
     * @return string
     */
    public function getFixturesDir()
    {
        return __DIR__ . '/Fixtures/';
    }

    public function testGetName(): void
    {
        $this->assertNotEmpty((new I18nExtension())->getName());
    }
}
