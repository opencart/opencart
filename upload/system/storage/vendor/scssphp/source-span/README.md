# SourceSpan

`scssphp/source-span` is a library for tracking locations in source code. It's designed
to provide a standard representation for source code locations and spans so that
disparate packages can easily pass them among one another, and to make it easy
to generate human-friendly messages associated with a given piece of code.

The most commonly-used interface is the package's namesake, `SourceSpan\SourceSpan`. It
represents a span of characters in some source file, and is often attached to an
object that has been parsed to indicate where it was parsed from. It provides
access to the text of the span via `SourceSpan::getText()` and can be used to produce
human-friendly messages using `SourceSpan::message()`. It's most simple implementation
is `SourceSpan\SimpleSourceSpan` which holds directly the span information.

When parsing code from a file, `SourceSpan\SourceFile` is useful. Not only does it provide
an efficient means of computing line and column numbers, `SourceFile#span()`
returns special `FileSpan`s that are able to provide more context for their
error messages.

## Credits

This library is a PHP port of the [Dart `source_span` package](https://github.com/dart-lang/source_span).
