<?php

declare(strict_types = 1);

/* The contents of this file is free and unencumbered software released into the
 * public domain.
 * For more information, please refer to <http://unlicense.org/>
 */
namespace Wdes\phpI18nL10n\Tests;

use PHPUnit\Framework\TestCase;
use Wdes\phpI18nL10n\Twig\Extension\I18n as ExtensionI18n;
use Twig\Environment as TwigEnv;
use Twig\Loader\FilesystemLoader as TwigLoaderFilesystem;
use Wdes\phpI18nL10n\plugins\MoReader;
use Wdes\phpI18nL10n\Launcher;

/**
 * @author William Desportes <williamdes@wdes.fr>
 * @license Unlicense
 */
class I18nTestNoData extends TestCase
{

    /**
     * The TwigEnv object
     *
     * @var TwigEnv
     */
    private $twig = null;

    /**
     * The MoReader object
     *
     * @var MoReader
     */
    private $moReader = null;

    /**
     * Set up the instance
     *
     * @return void
     */
    public function setUp(): void
    {
        $this->moReader = new MoReader();
        Launcher::setPlugin($this->moReader);

        $loader     = new TwigLoaderFilesystem();
        $this->twig = new TwigEnv(
            $loader
        );

        $this->twig->addExtension(new ExtensionI18n());
    }

    /**
     * Test simple translation using get and set
     * @return void
     */
    public function testSimpleTranslationGetSetTranslations(): void
    {
        $template = $this->twig->createTemplate(
            '{% trans "Translate this" %}',
            'testSimpleTranslationGetSetTranslations'
        );
        $html     = $template->render([]);
        $this->assertNotEmpty($html);
        $this->assertEquals('Translate this', $html);
        $this->moReader->setTranslations([]);
        $this->assertSame($this->moReader->getTranslations(), []);
        $html = $template->render([]);
        $this->assertNotEmpty($html);
        $this->assertEquals('Translate this', $html);
        $this->moReader->setTranslations(
            [
            'Translate this' => 'blob blob blob',
            ]
        );
        $this->assertSame(
            $this->moReader->getTranslations(),
            [
            'Translate this' => 'blob blob blob',
            ]
        );
        $html = $template->render([]);
        $this->assertNotEmpty($html);
        $this->assertEquals('blob blob blob', $html);
    }

    /**
     * Test simple translation
     * @return void
     */
    public function testSimpleTranslation(): void
    {
        $template = $this->twig->createTemplate(
            '{% trans "Translate this" %}'
        );
        $html     = $template->render([]);
        $this->assertEquals('Translate this', $html);
        $this->assertNotEmpty($html);
    }

    /**
     * Test simple translation with a comment
     * @return void
     */
    public function testSimpleTranslationWithComment(): void
    {
        $template = $this->twig->createTemplate(
            '{% trans %}Translate this{% notes %}And note{% endtrans %}'
        );
        $html     = $template->render([]);
        $this->assertEquals('Translate this', $html);
        $this->assertNotEmpty($html);
    }

    /**
     * Test simple translation with context
     * @return void
     */
    public function testSimpleTranslationWithContext(): void
    {
        $template = $this->twig->createTemplate(
            '{% trans %}Translate this{% context %}NayanCat{% endtrans %}'
        );
        $html     = $template->render([]);
        $this->assertEquals('Translate this', $html);
        $this->assertNotEmpty($html);
    }

    /**
     * Test simple translation with context and a variable
     * @return void
     */
    public function testSimpleTranslationWithContextAndVariable(): void
    {
        $template = $this->twig->createTemplate(
            '{% trans %}Translate this {{name}} {% context %}The user name{% endtrans %}'
        );
        $html     = $template->render(
            [
            'name' => 'williamdes',
            ]
        );
        $this->assertEquals('Translate this williamdes', $html);
        $this->assertNotEmpty($html);
    }

    /**
     * Test simple translation with context and some variables
     * @return void
     */
    public function testSimpleTranslationWithContextAndVariables(): void
    {
        $template = $this->twig->createTemplate(
            '{% trans %}Translate this {{key}}: {{value}} {% context %}The user name{% endtrans %}'
        );
        $html     = $template->render(
            [
            'key' => 'user',
            'value' => 'williamdes',
            ]
        );
        $this->assertEquals('Translate this user: williamdes', $html);
        $this->assertNotEmpty($html);
    }

    /**
     * Test plural translation
     * @return void
     */
    public function testPluralTranslation(): void
    {
        $template = $this->twig->createTemplate(
            '{% trans %}One person{% plural nbr_persons %}{{ nbr }} persons{% endtrans %}'
        );
        $html     = $template->render(['nbr' => 5]);
        $this->assertEquals('One person', $html);
        $this->assertNotEmpty($html);
    }

    /**
     * Test plural translation with comment
     * @return void
     */
    public function testPluralTranslationWithComment(): void
    {
        $template = $this->twig->createTemplate(
            '{% trans %}one user likes this.{% plural nbr_persons %}{{ nbr }} users likes this.{% notes %}Number of users{% endtrans %}'
        );
        $html     = $template->render(['nbr' => 5]);
        $this->assertEquals('one user likes this.', $html);
        $this->assertNotEmpty($html);
    }

    /**
     * Test simple plural translation
     * @return void
     */
    public function testSimplePluralTranslation(): void
    {
        $template = $this->twig->createTemplate(
            '{% trans %}One person{% plural a %}persons{% endtrans %}'
        );
        $html     = $template->render(['nbr' => 5]);
        $this->assertEquals('One person', $html);
        $this->assertNotEmpty($html);
    }

    /**
      * Test simple plural translation using count


      * @return void
      */
    public function testSimplePluralTranslationCount(): void
    {
        $template = $this->twig->createTemplate(
            '{% trans %}One person{% plural a.count %}persons{% endtrans %}'
        );
        $html     = $template->render(['a' => ['1', '2']]);
        $this->assertEquals('One person', $html);
        $this->assertNotEmpty($html);
    }

    /**
     * Test simple plural translation using count and vars
     * @return void
     */
    public function testSimplePluralTranslationCountAndVars(): void
    {
        $template = $this->twig->createTemplate(
            '{% trans %}One person{% plural a.count %}persons and {{ count }} dogs{% endtrans %}'
        );
        $html     = $template->render(['a' => ['1', '2'], 'nbrdogs' => 3]);
        $this->assertEquals('One person', $html);
        $this->assertNotEmpty($html);
    }

    /**
     * Test simple plural translation using count and vars
     * @return void
     */
    public function testExtensionI18nGetName(): void
    {
        $extension = new ExtensionI18n();
        $this->assertSame('i18n', $extension->getName());
    }

}
