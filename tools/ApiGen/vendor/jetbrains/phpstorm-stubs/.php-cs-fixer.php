<?php
declare(strict_types=1);

use StubTests\CodeStyle\BracesOneLineFixer;

require_once __DIR__ . '/vendor/autoload.php';

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__)
    ->append(['.php-cs-fixer.php'])
    ->notName('PhpStormStubsMap.php');

return (new PhpCsFixer\Config())
    ->registerCustomFixers([
        new BracesOneLineFixer(),
    ])
    ->setRules([
        'array_syntax' => ['syntax' => 'short'],
        'binary_operator_spaces' => ['operators' => ['|' => 'no_space']],
        'cast_spaces' => ['space' => 'none'],
        'class_definition' => ['single_line' => true],
        'clean_namespace' => true,
        'concat_space' => ['spacing' => 'one'],
        'echo_tag_syntax' => true,
        'fully_qualified_strict_types' => true,
        'function_typehint_space' => true,
        'general_phpdoc_tag_rename' => ['replacements' => ['inheritDocs' => 'inheritDoc']],
        'include' => true,
        'lambda_not_used_import' => true,
        'linebreak_after_opening_tag' => true,
        'magic_constant_casing' => true,
        'magic_method_casing' => true,
        'method_argument_space' => true,
        'native_function_casing' => true,
        'native_function_type_declaration_casing' => true,
        'no_alternative_syntax' => true,
        'no_binary_string' => true,
        'no_blank_lines_after_phpdoc' => true,
        'no_empty_comment' => true,
        'no_empty_phpdoc' => true,
        'no_extra_blank_lines' => ['tokens' => [
            'case', 'continue', 'curly_brace_block', 'default', 'extra', 'parenthesis_brace_block',
            'square_brace_block', 'switch', 'throw', 'use'
        ]
        ],
        'class_attributes_separation' => [
          'elements' => ['const' => 'only_if_meta', 'method' => 'one', 'property' => 'only_if_meta', 'trait_import' => 'none']
        ],
        'no_leading_namespace_whitespace' => true,
        'no_multiline_whitespace_around_double_arrow' => true,
        'no_short_bool_cast' => true,
        'no_singleline_whitespace_before_semicolons' => true,
        'no_spaces_around_offset' => true,
        'no_trailing_comma_in_list_call' => true,
        'no_trailing_comma_in_singleline_array' => true,
        'no_unneeded_control_parentheses' => ['statements' => ['break', 'clone', 'continue', 'echo_print', 'return', 'switch_case', 'yield', 'yield_from']],
        'no_unneeded_curly_braces' => ['namespaces' => true],
        'no_unset_cast' => true,
        'no_unused_imports' => true,
        'no_whitespace_before_comma_in_array' => true,
        'normalize_index_brace' => true,
        'object_operator_without_whitespace' => true,
        //'phpdoc_align' => ['align' => 'left'],
        'phpdoc_indent' => true,
        'phpdoc_inline_tag_normalizer' => true,
        'phpdoc_no_useless_inheritdoc' => true,
        'phpdoc_return_self_reference' => true,
        'phpdoc_scalar' => true,
        'phpdoc_single_line_var_spacing' => true,
        'phpdoc_trim' => true,
        //'phpdoc_trim_consecutive_blank_line_separation' => true,
        'phpdoc_types' => true,
        //'phpdoc_types_order' => ['null_adjustment' => 'always_last', 'sort_algorithm' => 'none'],
        'phpdoc_var_without_name' => true,
        'semicolon_after_instruction' => true,
        'single_line_throw' => true,
        //'single_quote' => true,
        'single_space_after_construct' => true,
        'space_after_semicolon' => ['remove_in_empty_for_expressions' => true],
        'standardize_not_equals' => true,
        'switch_continue_to_break' => true,
        'trim_array_spaces' => true,
        'unary_operator_spaces' => true,
        'visibility_required' => ['elements' => ['method', 'property']],
        'whitespace_after_comma_in_array' => true,
        'encoding' => true,
        'full_opening_tag' => true,
        'blank_line_after_namespace' => true,
        'elseif' => true,
        'function_declaration' => ['closure_function_spacing' => 'one'],
        'indentation_type' => true,
        'line_ending' => true,
        'lowercase_keywords' => true,
        'no_closing_tag' => true,
        'no_spaces_after_function_name' => true,
        'no_spaces_inside_parenthesis' => true,
        'single_class_element_per_statement' => ['elements' => ['property']],
        'single_import_per_statement' => true,
        'single_line_after_imports' => true,
        'switch_case_semicolon_to_colon' => true,
        'switch_case_space' => true,
        'blank_line_after_opening_tag' => false,
        'compact_nullable_typehint' => true,
        'declare_equal_normalize' => true,
        'lowercase_cast' => true,
        'lowercase_static_reference' => true,
        'new_with_braces' => true,
        'no_blank_lines_after_class_opening' => true,
        'no_leading_import_slash' => true,
        'ordered_class_elements' => [
            'order' => ['use_trait'],
        ],
        'ordered_imports' => [
            'imports_order' => ['class', 'function', 'const'],
            'sort_algorithm' => 'none',
        ],
        'return_type_declaration' => true,
        'short_scalar_cast' => true,
        'single_blank_line_before_namespace' => true,
        'single_trait_insert_per_statement' => true,
        'ternary_operator_spaces' => true,
        'PhpStorm/braces_one_line' => true,
        'no_trailing_whitespace' => true,
        'no_trailing_whitespace_in_comment' => true,
        'no_whitespace_in_blank_line' => true,
        'single_blank_line_at_eof' => true,
        'constant_case' => true
    ])
    ->setFinder($finder)
    ->setCacheFile(__DIR__ . '/.php_cs.cache');
