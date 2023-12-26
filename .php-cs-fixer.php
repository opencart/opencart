<?php
/*
 * This document has been generated with
 * https://mlocati.github.io/php-cs-fixer-configurator/#version:3.41.1|configurator
 * you can change this configuration by importing this file.
 */
$config = new PhpCsFixer\Config();
return $config
    ->setRiskyAllowed(true)
    ->setIndent("\t")
    ->setRules([
        '@PER-CS2.0:risky' => true,
        '@PER-CS2.0' => true,
        'array_syntax' => true,
        'assign_null_coalescing_to_coalesce_equal' => true,
        'binary_operator_spaces' => ['default' => 'single_space','operators' => ['=' => 'at_least_single_space','=>' => 'align_single_space_minimal']],
        'blank_line_after_namespace' => false,
        'blank_line_after_opening_tag' => false,
        'blank_lines_before_namespace' => false,
        'braces_position' => ['classes_opening_brace' => 'same_line','functions_opening_brace' => 'same_line'],
        'clean_namespace' => true,
        'combine_nested_dirname' => true,
        'comment_to_phpdoc' => true,
        'function_declaration' => ['closure_function_spacing' => 'none'],
        'general_phpdoc_annotation_remove' => true,
        'general_phpdoc_tag_rename' => true,
        'heredoc_indentation' => true,
        'implode_call' => true,
        'list_syntax' => true,
        'no_alias_functions' => true,
        'no_blank_lines_after_phpdoc' => true,
        'no_empty_phpdoc' => true,
        'no_whitespace_before_comma_in_array' => true,
        'non_printable_character' => true,
        'normalize_index_brace' => true,
        'phpdoc_align' => true,
        'phpdoc_annotation_without_dot' => true,
        'phpdoc_indent' => true,
        'phpdoc_inline_tag_normalizer' => true,
        'phpdoc_line_span' => true,
        'phpdoc_no_access' => true,
        'phpdoc_no_alias_tag' => true,
        'phpdoc_no_useless_inheritdoc' => true,
        'phpdoc_order_by_value' => true,
        'phpdoc_param_order' => true,
        'phpdoc_return_self_reference' => true,
        'phpdoc_scalar' => true,
        'phpdoc_separation' => true,
        'phpdoc_single_line_var_spacing' => true,
        'phpdoc_tag_casing' => true,
        'phpdoc_tag_type' => true,
        'phpdoc_to_comment' => true,
        'phpdoc_trim' => true,
        'phpdoc_trim_consecutive_blank_line_separation' => true,
        'phpdoc_types' => true,
        'phpdoc_types_order' => ['null_adjustment' => 'always_last'],
        'phpdoc_var_annotation_correct_order' => true,
        'phpdoc_var_without_name' => true,
        'pow_to_exponentiation' => true,
        'random_api_migration' => true,
        'use_arrow_functions' => true,
        'void_return' => true,
    ])
    ->setFinder(PhpCsFixer\Finder::create()
        ->in(__DIR__ . '/upload/')
         ->exclude([
             __DIR__ . '/upload/system/storage/vendor/',
         ])
        // ->append([
        //     'file-to-include',
        // ])
    )
;
