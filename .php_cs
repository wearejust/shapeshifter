<?php

$finder = Symfony\CS\Finder\DefaultFinder::create()
    ->in([
        __DIR__ . "/src"
    ]);

return Symfony\CS\Config\Config::create()
    ->setUsingCache(true)
    ->setUsingLinter(true)
    ->finder($finder)
    ->level(Symfony\CS\FixerInterface::PSR2_LEVEL)
    ->fixers([
        "-concat_without_spaces",
        "-multiline_array_trailing_comma",
        "-phpdoc_no_empty_return",
        "-phpdoc_short_description",
        "-phpdoc_to_comment",
        "-phpdoc_var_without_name",

        // Enabled aan
        "blankline_after_open_tag",
        "double_arrow_multiline_whitespaces",
        "duplicate_semicolon",
        "empty_return",
        "extra_empty_lines",
        "include",
        "join_function",
        "list_commas",
        "namespace_no_leading_whitespace",
        "new_with_braces",
        "no_blank_lines_after_class_opening",
        "no_empty_lines_after_phpdocs",
        "object_operator",
        "operators_spaces",
        "phpdoc_indent",
        "phpdoc_no_package",
        "phpdoc_params",
        "phpdoc_scalar",
        "phpdoc_separation",
        "phpdoc_trim",
        "phpdoc_type_to_var",
        "remove_leading_slash_use",
        "remove_lines_between_uses",
        "return",
        "single_array_no_trailing_comma",
        "single_blank_line_before_namespace",
        "single_quote",
        "spaces_before_semicolon",
        "spaces_cast",
        "standardize_not_equal",
        "ternary_spaces",
        "trim_array_spaces",
        "unused_use",
        "whitespacy_lines",

        // Contrib uit
        "-header_comment",
        "-long_array_syntax",
        "-no_blank_lines_before_namespace",
        "-php4_constructor",
        "-phpdoc_order",
        "-phpdoc_var_to_type",
        "-strict",
        "-strict_param",

        // Contrib aan
        "align_double_arrow",
        "align_equals",
        "concat_with_spaces",
        "ereg_to_preg",
        "multiline_spaces_before_semicolon",
        "newline_after_open_tag",
        "ordered_use",
        "short_array_syntax",
    ]);