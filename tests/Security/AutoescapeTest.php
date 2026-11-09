<?php
/**
 * Security tests for the contextual auto-escaping output encoding system.
 *
 * Verifies that:
 *  - every template expression is HTML escaped by default (body and attribute contexts)
 *  - JavaScript contexts (script blocks, on* event handlers) are escaped with |escape('js')
 *  - trusted content (language strings, sub-view output) is passed through as \Twig\Markup
 *  - Language::format() escapes plain string arguments and passes trusted arguments through
 *  - the |raw opt-out is available for audited rich text (e.g. CKEditor fields)
 *  - request input is stored raw (escaping happens on output only)
 *
 * Run: php tests/Security/AutoescapeTest.php
 */

// Bootstrap ---------------------------------------------------------------- --

if (!defined('DIR_OPENCART')) {
        $root = dirname(__DIR__, 2) . '/';

        define('DIR_OPENCART', $root . 'upload/');
        define('DIR_SYSTEM', DIR_OPENCART . 'system/');
        define('DIR_EXTENSION', DIR_OPENCART . 'extension/');
        define('DIR_CACHE', sys_get_temp_dir() . '/opencart-test-cache/');

        if (!is_dir(DIR_CACHE . 'template/')) {
                @mkdir(DIR_CACHE . 'template/', 0777, true);
        }

        require_once(DIR_SYSTEM . 'storage/vendor/autoload.php');
        require_once(DIR_SYSTEM . 'engine/autoloader.php');

        $autoloader = new \Opencart\System\Engine\Autoloader();
        $autoloader->register('Opencart\System', DIR_SYSTEM);
}

/**
 * Class AutoescapeTest
 */
class AutoescapeTest {
        /**
         * @var \Opencart\System\Engine\Registry
         */
        private \Opencart\System\Engine\Registry $registry;
        /**
         * @var \Opencart\System\Engine\Loader
         */
        private \Opencart\System\Engine\Loader $loader;
        /**
         * @var \Opencart\System\Library\Language
         */
        private \Opencart\System\Library\Language $language;

        /**
         * Constructor
         */
        public function __construct() {
                $this->registry = new \Opencart\System\Engine\Registry();

                $this->registry->set('event', new \Opencart\System\Engine\Event($this->registry));

                $template = new \Opencart\System\Library\Template('Twig');
                $template->addPath(DIR_OPENCART . 'admin/view/template/');
                $template->addPath('extension/opencart', DIR_EXTENSION . 'opencart/admin/view/template/');

                $this->registry->set('template', $template);

                $this->language = new \Opencart\System\Library\Language('en-gb');
                $this->language->addPath(DIR_OPENCART . 'admin/language/');

                $this->registry->set('language', $this->language);

                $this->loader = new \Opencart\System\Engine\Loader($this->registry);
        }

        /**
         * Render a template through the real Loader so the autoescape and the
         * trusted Markup handling are both exercised.
         *
         * @param string                $code
         * @param array<string, mixed>  $data
         *
         * @return string
         */
        private function render(string $code, array $data = []): string {
                return (string)$this->loader->view('test/template', $data, $code);
        }

        /**
         * HTML body context: a script tag payload must be entity escaped.
         */
        public function testBodyContextEscapesScriptTag(): void {
                $out = $this->render('Hello {{ name }}', ['name' => '<script>alert(1)</script>']);

                assert_not_contains('<script>', $out, 'body: raw <script> must not reach the output');
                assert_contains('&lt;script&gt;alert(1)&lt;/script&gt;', $out, 'body: payload must be entity escaped');
        }

        /**
         * Attribute context: quotes in a value must not allow attribute breakout.
         */
        public function testAttributeContextEscapesQuotes(): void {
                $out = $this->render('<span title="{{ name }}">x</span>', ['name' => 'a" onmouseover="alert(1)']);

                assert_not_contains('" onmouseover="', $out, 'attribute: quote breakout must not survive');
                assert_contains('&quot; onmouseover=&quot;', $out, 'attribute: quotes must be entity escaped');
        }

        /**
         * Sub-view output (Loader::view) is trusted: it must be a Markup instance
         * and must not be escaped again when embedded in a parent template.
         */
        public function testSubViewOutputIsTrusted(): void {
                $footer = $this->loader->view('common/footer', [
                        'text_footer'  => 'Copyright',
                        'text_version' => '1.0.0',
                        'scripts'      => [],
                ]);

                assert_instance_of(\Twig\Markup::class, $footer, 'sub-view output must be trusted Markup');

                $out = $this->render('<div>{{ footer }}</div>', ['footer' => $footer]);

                assert_contains('<footer id="footer">', $out, 'sub-view: parent must not escape sub-view HTML');
                assert_not_contains('&lt;footer', $out, 'sub-view: output must not be double escaped');
        }

        /**
         * Language strings are trusted translation scaffolding.
         */
        public function testLanguageStringsAreTrusted(): void {
                $this->language->load('catalog/product');

                $variant = $this->language->get('text_variant');

                assert_instance_of(\Twig\Markup::class, $variant, 'language strings must be trusted Markup');

                $out = $this->render('{{ text }}', ['text' => $variant]);

                assert_contains('<a href="%s" target="_blank" class="alert-link">', $out, 'language: HTML scaffold must render unescaped');
        }

        /**
         * A sprintf() composite is a plain string: the whole composite (scaffold
         * included) is escaped by the template autoescape. This is the secure
         * default; use Language::format() to keep a trusted scaffold.
         */
        public function testSprintfCompositeIsEscaped(): void {
                $this->language->load('catalog/product');

                $composite = sprintf($this->language->get('text_variant'), 'https://example.com/variant', 'https://example.com/variant');

                $out = $this->render('{{ text }}', ['text' => $composite]);

                assert_not_contains('<a href="https://example.com/variant"', $out, 'sprintf composite: raw scaffold must not reach the output');
                assert_contains('&lt;a href=&quot;https://example.com/variant&quot;', $out, 'sprintf composite: must be fully escaped');
        }

        /**
         * Language::format() keeps the trusted scaffold and escapes plain string
         * arguments (Django placeholder escaping model).
         */
        public function testLanguageFormatEscapesStringArgs(): void {
                $this->language->load('catalog/product');

                $out = $this->render('{{ text }}', [
                        'text' => $this->language->format('text_variant', 'https://example.com/" onmouseover="alert(1)', 'https://example.com/ok'),
                ]);

                assert_contains('<a href="', $out, 'format: trusted scaffold must render unescaped');
                assert_not_contains('"><script>', $out, 'format: argument payload must not break out');
                assert_contains('&quot; onmouseover=&quot;', $out, 'format: argument must be escaped');
        }

        /**
         * Language::format() passes trusted (Markup) arguments through untouched.
         */
        public function testLanguageFormatPassesTrustedArgs(): void {
                $this->language->load('catalog/product');

                $trusted = new \Twig\Markup('<b>variant product fields</b>', 'utf-8');
                $trusted2 = new \Twig\Markup('<b>master product</b>', 'utf-8');

                $out = $this->render('{{ text }}', ['text' => $this->language->format('text_variant', $trusted, $trusted2)]);

                assert_contains('<a href="<b>variant product fields</b>"', $out, 'format: trusted argument must pass through');
        }

        /**
         * JavaScript context: |escape('js') must neutralise a JS string breakout.
         */
        public function testJsContextEscaping(): void {
                $out = $this->render('<script>var name = \'{{ name|escape(\'js\') }}\';</script>', ['name' => '\'; alert(1); var x=\'']);

                assert_not_contains('\'; alert(1);', $out, 'js context: string breakout must not survive');
                assert_contains('\u0027', $out, 'js context: payload must be js escaped');
        }

        /**
         * The explicit |raw opt-out is available for audited rich text.
         */
        public function testRawOptOut(): void {
                $out = $this->render('{{ html|raw }}', ['html' => '<b>rich text</b>']);

                assert_contains('<b>rich text</b>', $out, 'raw: audited rich text must render unescaped');
        }

        /**
         * Request input is stored raw; escaping is done on output only.
         */
        public function testRequestDataIsRaw(): void {
                $_GET['payload'] = '<script>alert(1)</script>&amp;';

                $request = new \Opencart\System\Library\Request();

                assert_same('<script>alert(1)</script>&amp;', $request->get['payload'], 'request: input must be raw, not pre-escaped');

                unset($_GET['payload']);
        }

        /**
         * The real admin header template must escape an untrusted title.
         */
        public function testRealHeaderTemplateEscapesTitle(): void {
                $out = (string)$this->loader->view('common/header', [
                        'title' => '<script>alert(1)</script>',
                ]);

                assert_not_contains('<script>alert(1)</script>', $out, 'header: untrusted title must not reach the output raw');
                assert_contains('&lt;script&gt;alert(1)&lt;/script&gt;', $out, 'header: untrusted title must be escaped');
        }

        /**
         * URL attributes (script src, a href) are HTML escaped only. They must
         * not be JavaScript escaped (which would corrupt the URL).
         */
        public function testScriptSrcNotJsMangled(): void {
                $out = $this->render('<script src="{{ href }}" type="module"></script>', ['href' => 'index.php?route=common/header&c=d']);

                assert_contains('index.php?route=common/header&amp;c=d', $out, 'url attr: & must be html escaped');
                assert_not_contains('\x', $out, 'url attr: must not be js escaped');
        }
}

// Assertions --------------------------------------------------------------- --

/**
 * Assert Same
 *
 * @param mixed  $expected
 * @param mixed  $actual
 * @param string $label
 */
function assert_same(mixed $expected, mixed $actual, string $label): void {
        if ($expected !== $actual) {
                throw new \Exception($label . ': expected ' . var_export($expected, true) . ', got ' . var_export($actual, true));
        }
}

/**
 * Assert Instance Of
 *
 * @param string $class
 * @param mixed  $object
 * @param string $label
 */
function assert_instance_of(string $class, mixed $object, string $label): void {
        if (!is_a($object, $class)) {
                throw new \Exception($label . ': expected instance of ' . $class . ', got ' . get_debug_type($object));
        }
}

/**
 * Assert Contains
 *
 * @param string $needle
 * @param string $haystack
 * @param string $label
 */
function assert_contains(string $needle, string $haystack, string $label): void {
        if (!str_contains($haystack, $needle)) {
                throw new \Exception($label . ': output does not contain "' . $needle . '"');
        }
}

/**
 * Assert Not Contains
 *
 * @param string $needle
 * @param string $haystack
 * @param string $label
 */
function assert_not_contains(string $needle, string $haystack, string $label): void {
        if (str_contains($haystack, $needle)) {
                throw new \Exception($label . ': output must not contain "' . $needle . '"');
        }
}

// Runner -------------------------------------------------------------------- --

if (PHP_SAPI == 'cli' && realpath($argv[0]) == __FILE__) {
        $test = new AutoescapeTest();

        $passed = 0;
        $failed = 0;

        foreach (get_class_methods($test) as $method) {
                if (str_starts_with($method, 'test')) {
                        try {
                                $test->$method();

                                echo 'PASS ' . $method . "\n";

                                $passed++;
                        } catch (\Throwable $e) {
                                echo 'FAIL ' . $method . ': ' . $e->getMessage() . "\n";

                                $failed++;
                        }
                }
        }

        echo "\n" . $passed . ' passed, ' . $failed . ' failed' . "\n";

        exit($failed ? 1 : 0);
}
