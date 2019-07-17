<?php
$finder = PhpCsFixer\Finder::create()->in([
    __DIR__ . '/src',
    __DIR__ . '/tests'
]);

return PhpCsFixer\Config::create()->setRules([
    "@PSR2" => true,
    "binary_operator_spaces" => [
        "operators" => [
            "=" => "single_space",
            "=>" => "single_space"
        ]
    ],
    "ordered_imports" => true,
    "no_leading_import_slash" => true,
    "phpdoc_order" => true,
    "visibility_required" => true,
    "no_extra_blank_lines" => true,
    "class_attributes_separation" => true,
    "no_useless_return" => true,
    "no_whitespace_before_comma_in_array" => true,
    "no_singleline_whitespace_before_semicolons" => true,
    "no_spaces_after_function_name" => true,
    "no_whitespace_in_blank_line" => true,
    "no_spaces_inside_parenthesis" => true,
    "phpdoc_no_package" => true,
    "phpdoc_separation" => true,
    "ternary_operator_spaces" => true,
    "ternary_to_null_coalescing" => true,
    "trim_array_spaces" => true,
    "unary_operator_spaces" => true,
    "whitespace_after_comma_in_array" => true,
    "no_spaces_around_offset" => true,
    "no_blank_lines_after_phpdoc" => true,
    "no_blank_lines_after_class_opening" => true,
    "function_typehint_space" => true,
    "single_quote" => true,
    "cast_spaces" => true,
    "blank_line_before_return" => false,
    "blank_line_after_opening_tag" => true,
    "no_empty_statement" => true,
    "multiline_whitespace_before_semicolons" => true,
    "concat_space" => [
        "spacing" => "none"
    ],
    "ordered_class_elements" => [
        "order" => [
            "use_trait",
            "constant_public",
            "constant_protected",
            "constant_private",
            "property_public",
            "property_protected",
            "property_private",
            "construct",
            "destruct",
            "magic",
            "phpunit",
            "method"
        ]
    ],
    "declare_equal_normalize" => true,
    "array_syntax" => [
        "syntax" => "short"
    ]
])->setFinder($finder);