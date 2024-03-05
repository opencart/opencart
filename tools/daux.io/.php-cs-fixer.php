<?php

$finder = PhpCsFixer\Finder::create()
    ->exclude('vendor')
    ->exclude('templates')
    ->exclude('node_modules')
    ->in(__DIR__);

$config = new PhpCsFixer\Config();
$config->setRules([
        // Presets
        '@PSR2' => true,
        '@PHP80Migration' => true,
        '@PhpCsFixer' => true,

        // Disable rules
        'explicit_string_variable' => false,
        'yoda_style' => false,
        'php_unit_internal_class' => false,
        'php_unit_test_class_requires_covers' => false,
        'phpdoc_align' => false,
        'multiline_whitespace_before_semicolons' => false,

        // Namespace configuration
        'no_blank_lines_before_namespace' => true,
        'single_blank_line_before_namespace' => false,
        'blank_line_after_opening_tag' => false,
        'linebreak_after_opening_tag' => false,

        // Options tweaks
        'echo_tag_syntax' => [
            'format' => 'short'
        ],
        'concat_space' => ['spacing' => 'one'],
        'ordered_class_elements' => [
            'order' => [
                'use_trait',
                'constant_public',
                'constant_protected',
                'constant_private',
                'property_public',
                'property_protected',
                'property_private',
                'construct',
                'method'
            ]
        ]
]);
$config->setFinder($finder);

return $config;
