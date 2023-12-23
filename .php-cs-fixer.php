<?php
/*
 * This document has been generated with
 * https://mlocati.github.io/php-cs-fixer-configurator/#version:3.41.1|configurator
 * you can change this configuration by importing this file.
 */
$config = new PhpCsFixer\Config();

return $config->setRiskyAllowed(true)->setIndent("\t")->setRules([
    '@PSR12'                                   => true,
    'array_syntax'                             => true,
    'assign_null_coalescing_to_coalesce_equal' => true,
    'binary_operator_spaces'                   => [
		'default'   => 'single_space',
		'operators' => [
			'='  => 'at_least_single_space',
			'=>' => 'at_least_single_space'
		]
    ],
    'blank_line_after_namespace'               => false,
    'blank_line_after_opening_tag'             => false,
    'blank_lines_before_namespace'             => false,
    'braces_position'                          => [
		'classes_opening_brace'   => 'same_line',
		'functions_opening_brace' => 'same_line'
    ],
    'control_structure_continuation_position'  => ['position' => 'same_line'],
    'combine_nested_dirname'                   => true,
    'function_declaration'                     => ['closure_function_spacing' => 'none'],
    'heredoc_indentation'                      => true,
    'implode_call'                             => true,
    'list_syntax'                              => true,
    'no_alias_functions'                       => true,
    'no_blank_lines_after_phpdoc'              => true,
    'no_whitespace_before_comma_in_array'      => true,
    'non_printable_character'                  => true,
    'normalize_index_brace'                    => true,
    'pow_to_exponentiation'                    => true,
    'use_arrow_functions'                      => true,
    'void_return'                              => true->setFinder(
        PhpCsFixer\Finder::create()->in(__DIR__ . '/upload/')->exclude(
            [
                __DIR__ . '/upload/system/storage/vendor/',
            ]
        )
        // ->append([
        //     'file-to-include',
        // ])
    )
]);
