<?php declare(strict_types = 1);

namespace ApiGen\Renderer;

use Nette\Utils\FileSystem;
use PhpToken;
use Symfony\Component\Console\Style\OutputStyle;

use function count;
use function explode;
use function htmlspecialchars;
use function sprintf;
use function strlen;
use function strtolower;
use function strval;
use function substr_count;

use const TOKEN_PARSE;
use const T_ABSTRACT;
use const T_ARRAY;
use const T_AS;
use const T_BREAK;
use const T_CASE;
use const T_CATCH;
use const T_CLASS;
use const T_CLONE;
use const T_CLOSE_TAG;
use const T_COMMENT;
use const T_CONST;
use const T_CONSTANT_ENCAPSED_STRING;
use const T_CONTINUE;
use const T_DECLARE;
use const T_DEFAULT;
use const T_DNUMBER;
use const T_DO;
use const T_DOC_COMMENT;
use const T_ECHO;
use const T_ELSE;
use const T_ELSEIF;
use const T_EMPTY;
use const T_ENCAPSED_AND_WHITESPACE;
use const T_ENDDECLARE;
use const T_ENDFOR;
use const T_ENDFOREACH;
use const T_ENDIF;
use const T_ENDSWITCH;
use const T_ENDWHILE;
use const T_ENUM;
use const T_EVAL;
use const T_EXIT;
use const T_EXTENDS;
use const T_FINAL;
use const T_FINALLY;
use const T_FN;
use const T_FOR;
use const T_FOREACH;
use const T_FUNCTION;
use const T_GLOBAL;
use const T_GOTO;
use const T_HALT_COMPILER;
use const T_IF;
use const T_IMPLEMENTS;
use const T_INCLUDE;
use const T_INCLUDE_ONCE;
use const T_INLINE_HTML;
use const T_INSTANCEOF;
use const T_INSTEADOF;
use const T_INTERFACE;
use const T_ISSET;
use const T_LIST;
use const T_LNUMBER;
use const T_LOGICAL_AND;
use const T_LOGICAL_OR;
use const T_LOGICAL_XOR;
use const T_MATCH;
use const T_NAMESPACE;
use const T_NEW;
use const T_OPEN_TAG;
use const T_OPEN_TAG_WITH_ECHO;
use const T_PRINT;
use const T_PRIVATE;
use const T_PROTECTED;
use const T_PUBLIC;
use const T_READONLY;
use const T_REQUIRE;
use const T_REQUIRE_ONCE;
use const T_RETURN;
use const T_STATIC;
use const T_STRING;
use const T_SWITCH;
use const T_THROW;
use const T_TRAIT;
use const T_TRY;
use const T_UNSET;
use const T_USE;
use const T_VAR;
use const T_VARIABLE;
use const T_WHILE;
use const T_WHITESPACE;
use const T_YIELD;
use const T_YIELD_FROM;


class SourceHighlighter
{
	public const PHP_TAG = 'php-tag';
	public const PHP_KEYWORD = 'php-kw';
	public const PHP_NUMBER = 'php-num';
	public const PHP_STRING = 'php-str';
	public const PHP_VARIABLE = 'php-var';
	public const PHP_COMMENT = 'php-comment';

	/** @var string[] indexed by [tokenId] */
	public array $tokenClass = [
		T_OPEN_TAG => self::PHP_TAG,
		T_OPEN_TAG_WITH_ECHO => self::PHP_TAG,
		T_CLOSE_TAG => self::PHP_TAG,
		T_INCLUDE => self::PHP_KEYWORD,
		T_INCLUDE_ONCE => self::PHP_KEYWORD,
		T_REQUIRE => self::PHP_KEYWORD,
		T_REQUIRE_ONCE => self::PHP_KEYWORD,
		T_LOGICAL_OR => self::PHP_KEYWORD,
		T_LOGICAL_XOR => self::PHP_KEYWORD,
		T_LOGICAL_AND => self::PHP_KEYWORD,
		T_PRINT => self::PHP_KEYWORD,
		T_YIELD => self::PHP_KEYWORD,
		T_YIELD_FROM => self::PHP_KEYWORD,
		T_INSTANCEOF => self::PHP_KEYWORD,
		T_NEW => self::PHP_KEYWORD,
		T_CLONE => self::PHP_KEYWORD,
		T_ELSEIF => self::PHP_KEYWORD,
		T_ELSE => self::PHP_KEYWORD,
		T_EVAL => self::PHP_KEYWORD,
		T_EXIT => self::PHP_KEYWORD,
		T_IF => self::PHP_KEYWORD,
		T_ENDIF => self::PHP_KEYWORD,
		T_ECHO => self::PHP_KEYWORD,
		T_DO => self::PHP_KEYWORD,
		T_WHILE => self::PHP_KEYWORD,
		T_ENDWHILE => self::PHP_KEYWORD,
		T_FOR => self::PHP_KEYWORD,
		T_ENDFOR => self::PHP_KEYWORD,
		T_FOREACH => self::PHP_KEYWORD,
		T_ENDFOREACH => self::PHP_KEYWORD,
		T_DECLARE => self::PHP_KEYWORD,
		T_ENDDECLARE => self::PHP_KEYWORD,
		T_AS => self::PHP_KEYWORD,
		T_SWITCH => self::PHP_KEYWORD,
		T_ENDSWITCH => self::PHP_KEYWORD,
		T_CASE => self::PHP_KEYWORD,
		T_DEFAULT => self::PHP_KEYWORD,
		T_BREAK => self::PHP_KEYWORD,
		T_CONTINUE => self::PHP_KEYWORD,
		T_GOTO => self::PHP_KEYWORD,
		T_FUNCTION => self::PHP_KEYWORD,
		T_FN => self::PHP_KEYWORD,
		T_CONST => self::PHP_KEYWORD,
		T_RETURN => self::PHP_KEYWORD,
		T_CATCH => self::PHP_KEYWORD,
		T_TRY => self::PHP_KEYWORD,
		T_FINALLY => self::PHP_KEYWORD,
		T_THROW => self::PHP_KEYWORD,
		T_USE => self::PHP_KEYWORD,
		T_INSTEADOF => self::PHP_KEYWORD,
		T_GLOBAL => self::PHP_KEYWORD,
		T_STATIC => self::PHP_KEYWORD,
		T_ABSTRACT => self::PHP_KEYWORD,
		T_FINAL => self::PHP_KEYWORD,
		T_PRIVATE => self::PHP_KEYWORD,
		T_PROTECTED => self::PHP_KEYWORD,
		T_PUBLIC => self::PHP_KEYWORD,
		T_VAR => self::PHP_KEYWORD,
		T_UNSET => self::PHP_KEYWORD,
		T_ISSET => self::PHP_KEYWORD,
		T_EMPTY => self::PHP_KEYWORD,
		T_HALT_COMPILER => self::PHP_KEYWORD,
		T_CLASS => self::PHP_KEYWORD,
		T_TRAIT => self::PHP_KEYWORD,
		T_INTERFACE => self::PHP_KEYWORD,
		T_EXTENDS => self::PHP_KEYWORD,
		T_IMPLEMENTS => self::PHP_KEYWORD,
		T_LIST => self::PHP_KEYWORD,
		T_ARRAY => self::PHP_KEYWORD,
		T_NAMESPACE => self::PHP_KEYWORD,
		T_ENUM => self::PHP_KEYWORD,
		T_READONLY => self::PHP_KEYWORD,
		T_MATCH => self::PHP_KEYWORD,
		T_LNUMBER => self::PHP_NUMBER,
		T_DNUMBER => self::PHP_NUMBER,
		T_CONSTANT_ENCAPSED_STRING => self::PHP_STRING,
		T_ENCAPSED_AND_WHITESPACE => self::PHP_STRING,
		T_VARIABLE => self::PHP_VARIABLE,
		T_COMMENT => self::PHP_COMMENT,
		T_DOC_COMMENT => self::PHP_COMMENT,
	];

	/** @var string[] indexed by [identifierName] */
	public array $identifierClass = [
		'true' => self::PHP_KEYWORD,
		'false' => self::PHP_KEYWORD,
		'null' => self::PHP_KEYWORD,
	];


	public function __construct(
		protected OutputStyle $output,
	) {
	}


	public function highlight(string $path): string
	{
		$source = FileSystem::read($path);
		$align = strlen(strval(1 + substr_count($source, "\n")));
		$lineStart = "<tr id=\"%1\$d\" class=\"source-line\"><td><a class=\"source-lineNum\" href=\"#%1\$d\">%1\${$align}d: </a></td><td>";
		$lineEnd = '</td></tr>';

		$line = 1;
		$out = sprintf($lineStart, $line);

		foreach ($this->tokenize($path, $source) as $id => $text) {
			if ($text === "\n") {
				$out .= $lineEnd . sprintf($lineStart, ++$line);

			} else {
				$html = htmlspecialchars($text);
				$class = $this->tokenClass[$id] ?? ($id === T_STRING ? $this->identifierClass[strtolower($text)] ?? null : null);
				$out .= $class ? "<span class=\"{$class}\">{$html}</span>" : $html;
			}
		}

		return $out . $lineEnd;
	}


	/**
	 * @return iterable<int, string>
	 */
	protected function tokenize(string $path, string $source): iterable
	{
		try {
			$tokens = PhpToken::tokenize($source, TOKEN_PARSE);

		} catch (\ParseError $e) {
			$this->output->newLine();
			$this->output->warning(sprintf("Parse error in %s:%d\n%s", $path, $e->getLine(), $e->getMessage()));
			$tokens = [new PhpToken(T_INLINE_HTML, $source)];
		}

		foreach ($tokens as $token) {
			$lines = explode("\n", $token->text);
			$lastLine = count($lines) - 1;

			foreach ($lines as $i => $line) {
				yield $token->id => $line;

				if ($i !== $lastLine) {
					yield T_WHITESPACE => "\n";
				}
			}
		}
	}
}
