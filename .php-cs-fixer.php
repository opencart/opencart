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
        '@PSR12' => true,
        'assign_null_coalescing_to_coalesce_equal' => true,
        'binary_operator_spaces' => false,
        'blank_line_after_namespace' => false,
        'blank_line_after_opening_tag' => false,
        'blank_lines_before_namespace' => false,
        'braces_position' => false,
        'class_definition' => false,
        'constant_case' => false,
        'control_structure_braces' => false,
        'control_structure_continuation_position' => false,
        'elseif' => false,
        'function_declaration' => false,
        'heredoc_indentation' => true,
        'implode_call' => true,
        'indentation_type' => false,
        'method_argument_space' => false,
        'new_with_parentheses' => false,
        'no_blank_lines_after_class_opening' => false,
        'no_closing_tag' => false,
        'no_leading_import_slash' => false,
        'no_multiple_statements_per_line' => false,
        'no_spaces_after_function_name' => false,
        'no_trailing_whitespace' => false,
        'no_trailing_whitespace_in_comment' => false,
        'no_whitespace_before_comma_in_array' => true,
        'no_whitespace_in_blank_line' => false,
        'normalize_index_brace' => true,
        'short_scalar_cast' => false,
        'single_blank_line_at_eof' => false,
        'spaces_inside_parentheses' => false,
        'statement_indentation' => false,
        'switch_case_semicolon_to_colon' => false,
        'switch_case_space' => false,
        'ternary_operator_spaces' => false,
        'unary_operator_spaces' => false,
        'visibility_required' => false,
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
