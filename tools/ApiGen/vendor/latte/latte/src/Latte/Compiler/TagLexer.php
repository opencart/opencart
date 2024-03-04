<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler;

use Latte;
use Latte\CompileException;
use Latte\RegexpException;


/**
 * Lexer for PHP-like expression language used in tags.
 */
final class TagLexer
{
	use Latte\Strict;

	private const Keywords = [
		'and' => Token::Php_LogicalAnd,
		'array' => Token::Php_Array,
		'clone' => Token::Php_Clone,
		'default' => Token::Php_Default,
		'in' => Token::Php_In,
		'instanceof' => Token::Php_Instanceof,
		'new' => Token::Php_New,
		'or' => Token::Php_LogicalOr,
		'return' => Token::Php_Return,
		'xor' => Token::Php_LogicalXor,
		'null' => Token::Php_Null,
		'true' => Token::Php_True,
		'false' => Token::Php_False,
	];

	private const KeywordsFollowed = [ // must follows ( & =
		'empty' => Token::Php_Empty,
		'fn' => Token::Php_Fn,
		'function' => Token::Php_Function,
		'isset' => Token::Php_Isset,
		'list' => Token::Php_List,
		'match' => Token::Php_Match,
		'use' => Token::Php_Use,
	];

	/** @var Token[] */
	private array $tokens;
	private string $input;
	private int $offset;
	private Position $position;


	/** @return Token[] */
	public function tokenize(string $input, ?Position $position = null): array
	{
		$position ??= new Position(1, 1, 0);
		$this->tokens = $this->tokenizePartially($input, $position, 0);
		if ($this->offset !== strlen($input)) {
			$token = str_replace("\n", '\n', substr($input, $this->offset, 10));
			throw new CompileException("Unexpected '$token'", $position);
		}

		$this->tokens[] = new Token(Token::End, '', $position);
		return $this->tokens;
	}


	/** @return Token[] */
	public function tokenizePartially(string $input, Position &$position, int $ofs = null): array
	{
		$this->input = $input;
		$this->offset = $ofs ?? $position->offset;
		$this->position = &$position;
		$this->tokens = [];
		$this->tokenizeCode();
		return $this->tokens;
	}


	/** @return Token[]|null */
	public function tokenizeUnquotedString(string $input, Position $position, bool $colon, int $offsetDelta): ?array
	{
		preg_match(
			$colon
				? '~ ( [./@_a-z0-9#!-] | :(?!:) | \{\$ [_a-z0-9\[\]()>-]+ })++  (?=\s+[!"\'$(\[{,\\|\~\w-] | [,|]  | \s*$) ~xAi'
				: '~ ( [./@_a-z0-9#!-]          | \{\$ [_a-z0-9\[\]()>-]+ })++  (?=\s+[!"\'$(\[{,\\|\~\w-] | [,:|] | \s*$) ~xAi',
			$input,
			$match,
			offset: $position->offset - $offsetDelta,
		);
		$position = new Position($position->line, $position->column - 1, $position->offset - 1);
		return $match && !is_numeric($match[0])
			? $this->tokenize('"' . $match[0] . '"', $position)
			: null;
	}


	private function tokenizeCode(): void
	{
		$re = <<<'XX'
			~(?J)(?n)   # allow duplicate named groups, no auto capture
			(?(DEFINE) (?<label>  [a-zA-Z_\x80-\xff][a-zA-Z0-9_\x80-\xff]*  ) )

			(?<Php_Whitespace>  [ \t\r\n]+  )|
			( (?<Php_ConstantEncapsedString>  '  )  (?<rest>  ( \\. | [^'\\] )*  '  )?  )|
			( (?<string>  "  )  .*  )|
			( (?<Php_StartHeredoc>  <<< [ \t]* (?: (?&label) | ' (?&label) ' | " (?&label) " ) \r?\n  ) .*  )|
			( (?<Php_Comment>  /\*  )   (?<rest>  .*?\*/  )?  )|
			(?<Php_Variable>  \$  (?&label)  )|
			(?<Php_Float>
				((?&lnum) | (?&dnum)) [eE][+-]? (?&lnum)|
				(?<dnum>   (?&lnum)? \. (?&lnum) | (?&lnum) \. (?&lnum)?  )
			)|
			(?<Php_Integer>
				0[xX][0-9a-fA-F]+(_[0-9a-fA-F]+)*|
				0[bB][01]+(_[01]+)*|
				0[oO][0-7]+(_[0-7]+)*|
				(?<lnum>  [0-9]+(_[0-9]+)*  )
			)|
			(?<Php_NameFullyQualified>  \\ (?&label) ( \\ (?&label) )*  )|
			(?<Php_NameQualified>  (?&label) ( \\ (?&label) )+  )|
			(?<Php_IdentifierFollowed>  (?&label)  (?= [ \t\r\n]* [(&=] )  )|
			(?<Php_Identifier>  (?&label)((--?|\.)[a-zA-Z0-9_\x80-\xff]+)*  )|
			(
				(
					(?<Php_ObjectOperator>  ->  )|
					(?<Php_NullsafeObjectOperator>  \?->  )|
					(?<Php_UndefinedsafeObjectOperator>  \?\?->  )
				)
				(?<Php_Whitespace>  [ \t\r\n]+  )?
				(?<Php_Identifier>  (?&label)  )?
			)|
			(?<Php_DoubleArrow>  =>  )|
			(?<Php_PlusEqual>  \+=  )|
			(?<Php_MinusEqual>  -=  )|
			(?<Php_MulEqual>  \*=  )|
			(?<Php_DivEqual>  /=  )|
			(?<Php_ConcatEqual>  \.=  )|
			(?<Php_ModEqual>  %=  )|
			(?<Php_AndEqual>  &=  )|
			(?<Php_OrEqual>  \|=  )|
			(?<Php_XorEqual>  \^=  )|
			(?<Php_SlEqual>  <<=  )|
			(?<Php_SrEqual>  >>=  )|
			(?<Php_PowEqual>  \*\*=  )|
			(?<Php_CoalesceEqual>  \?\?=  )|
			(?<Php_Coalesce>  \?\?  )|
			(?<Php_BooleanOr>  \|\|  )|
			(?<Php_BooleanAnd>  &&  )|
			(?<Php_AmpersandFollowed>  & (?= [ \t\r\n]* (\$|\.\.\.) )  )|
			(?<Php_AmpersandNotFollowed>  &  )|
			(?<Php_IsIdentical>  ===  )|
			(?<Php_IsNotIdentical>  !==  )|
			(?<Php_IsEqual>  ==  )|
			(?<Php_IsNotEqual>  !=  |  <>  )|
			(?<Php_Spaceship>  <=>  )|
			(?<Php_IsSmallerOrEqual>  <=  )|
			(?<Php_IsGreaterOrEqual>  >=  )|
			(?<Php_Sl>  <<  )|
			(?<Php_Sr>  >>  )|
			(?<Php_Inc>  \+\+  )|
			(?<Php_Dec>  --  )|
			(?<Php_Pow>  \*\*  )|
			(?<Php_PaamayimNekudotayim>  ::  )|
			(?<Php_NsSeparator>  \\  )|
			(?<Php_Ellipsis>  \.\.\.  )|
			(?<Php_IntCast>  \( [ \t]* int [ \t]* \)  )|
			(?<Php_FloatCast>  \( [ \t]* float [ \t]* \)  )|
			(?<Php_StringCast>  \( [ \t]* string [ \t]* \)  )|
			(?<Php_ArrayCast>  \( [ \t]* array [ \t]* \)  )|
			(?<Php_ObjectCast>  \( [ \t]* object [ \t]* \)  )|
			(?<Php_BoolCast>  \( [ \t]* bool [ \t]* \)  )|
			(?<Php_ExpandCast>  \( [ \t]* expand [ \t]* \)  )|
			( (?<end>  /?}  ) .* )|
			(?<char>  [;:,.|^&+/*=%!\~$<>?@#(){[\]-]  )|
			(?<badchar>  .  )
			~xsA
			XX;

		$depth = 0;
		matchRE:
		preg_match_all($re, $this->input, $matches, PREG_SET_ORDER | PREG_UNMATCHED_AS_NULL, $this->offset);
		if (preg_last_error()) {
			throw new RegexpException;
		}

		foreach ($matches as $m) {
			if (isset($m['char'])) {
				if ($m['char'] === '{') {
					$depth++;
				}
				$this->addToken(null, $m['char']);

			} elseif (isset($m['end'])) {
				$depth--;
				if ($depth < 0) {
					return;
				}
				foreach (str_split($m['end']) as $ch) {
					$this->addToken(null, $ch);
				}

				goto matchRE;

			} elseif (isset($m[$type = 'Php_ObjectOperator'])
				|| isset($m[$type = 'Php_NullsafeObjectOperator'])
				|| isset($m[$type = 'Php_UndefinedsafeObjectOperator'])
			) {
				$this->addToken(constant(Token::class . '::' . $type), $m[$type]);
				if (isset($m['Php_Whitespace'])) {
					$this->addToken(Token::Php_Whitespace, $m['Php_Whitespace']);
				}
				if (isset($m['Php_Identifier'])) {
					$this->addToken(Token::Php_Identifier, $m['Php_Identifier']);
				}

			} elseif (isset($m['Php_Identifier'])) {
				$lower = strtolower($m['Php_Identifier']);
				$this->addToken(
					self::Keywords[$lower] ?? (preg_match('~[A-Z_][A-Z0-9_]{2,}$~DA', $m['Php_Identifier']) ? Token::Php_Constant : Token::Php_Identifier),
					$m['Php_Identifier'],
				);

			} elseif (isset($m['Php_IdentifierFollowed'])) {
				$lower = strtolower($m['Php_IdentifierFollowed']);
				$this->addToken(self::KeywordsFollowed[$lower] ?? self::Keywords[$lower] ?? Token::Php_Identifier, $m['Php_IdentifierFollowed']);

			} elseif (isset($m['Php_ConstantEncapsedString'])) {
				isset($m['rest'])
					? $this->addToken(Token::Php_ConstantEncapsedString, "'" . $m['rest'])
					: throw new CompileException('Unterminated string.', $this->position);

			} elseif (isset($m['string'])) {
				$pos = $this->position;
				$this->addToken(null, '"');
				$count = count($this->tokens);
				$this->tokenizeString('"');
				$token = $this->tokens[$count] ?? null;
				$this->addToken(null, '"');
				if (
					count($this->tokens) <= $count + 2
					&& (!$token || $token->type === Token::Php_EncapsedAndWhitespace)
				) {
					array_splice($this->tokens, $count - 1, null, [new Token(Token::Php_ConstantEncapsedString, '"' . $token?->text . '"', $pos)]);
				}
				goto matchRE;

			} elseif (isset($m['Php_Integer'])) {
				$num = PhpHelpers::decodeNumber($m['Php_Integer']);
				$this->addToken(is_float($num) ? Token::Php_Float : Token::Php_Integer, $m['Php_Integer']);

			} elseif (isset($m['Php_StartHeredoc'])) {
				$this->addToken(Token::Php_StartHeredoc, $m['Php_StartHeredoc']);
				$endRe = '(?<=\n)[ \t]*' . trim($m['Php_StartHeredoc'], "< \t\r\n'\"") . '\b';
				if (str_contains($m['Php_StartHeredoc'], "'")) { // nowdoc
					if (!preg_match('~(.*?)(' . $endRe . ')~sA', $this->input, $m, 0, $this->offset)) {
						throw new CompileException('Unterminated NOWDOC.', $this->position);
					} elseif ($m[1] !== '') {
						$this->addToken(Token::Php_EncapsedAndWhitespace, $m[1]);
					}
					$this->addToken(Token::Php_EndHeredoc, $m[2]);
				} else {
					$end = $this->tokenizeString($endRe);
					$this->addToken(Token::Php_EndHeredoc, $end);
				}
				goto matchRE;

			} elseif (isset($m['Php_Comment'])) {
				isset($m['rest'])
					? $this->addToken(Token::Php_Comment, '/*' . $m['rest'])
					: throw new CompileException('Unterminated comment.', $this->position);

			} elseif (isset($m['badchar'])) {
				throw new CompileException("Unexpected '$m[badchar]'", $this->position);

			} else {
				foreach ($m as $type => $text) {
					if ($text !== null && !is_int($type)) {
						$this->addToken(constant(Token::class . '::' . $type), $text);
						break;
					}
				}
			}
		}
	}


	private function tokenizeString(string $endRe): string
	{
		$re = <<<'XX'
			~(?J)(?n)   # allow duplicate named groups, no auto capture
			(?(DEFINE) (?<label>  [a-zA-Z_\x80-\xff][a-zA-Z0-9_\x80-\xff]*  ) )

			( (?<Php_CurlyOpen>  \{\$  )  .*  )|
			(?<Php_DollarOpenCurlyBraces>  \$\{  )|
			( (?<Php_Variable>  \$  (?&label)  )
				(
					(
						(?<Php_ObjectOperator>  ->  )|
						(?<Php_NullsafeObjectOperator>  \?->  )|
						(?<Php_UndefinedsafeObjectOperator>  \?\?->  )
					)
					(?<Php_Identifier>  (?&label)  )
					|
					(?<offset>  \[  )
					(
						(?<offsetVar>  \$  (?&label)  )|
						(?<offsetString>  (?&label)  )|
						(?<offsetMinus>  -  )?
						(?<Php_NumString>
							0[xX][0-9a-fA-F]+(_[0-9a-fA-F]+)*|
							0[bB][01]+(_[01]+)*|
							0[oO][0-7]+(_[0-7]+)*|
							[0-9]+(_[0-9]+)*
						)
					)?
					(?<offsetEnd>  ]  )?
					|
				)
			)|
			XX . "
			((?<end>  $endRe  )  .*  )|
			(?<char>  ( \\\\. | [^\\\\] )  )
			~xsA";

		matchRE:
		preg_match_all($re, $this->input, $matches, PREG_SET_ORDER | PREG_UNMATCHED_AS_NULL, $this->offset);
		if (preg_last_error()) {
			throw new RegexpException;
		}

		$buffer = '';
		foreach ($matches as $m) {
			if (isset($m['char'])) {
				$buffer .= $m['char'];
				continue;
			} elseif ($buffer !== '') {
				$this->addToken(Token::Php_EncapsedAndWhitespace, $buffer);
				$buffer = '';
			}

			if (isset($m['Php_CurlyOpen'])) {
				$this->addToken(Token::Php_CurlyOpen, '{');
				$this->tokenizeCode();
				if (($this->input[$this->offset] ?? null) === '}') {
					$this->addToken(null, '}');
				}
				goto matchRE;

			} elseif (isset($m['Php_DollarOpenCurlyBraces'])) {
				throw new CompileException('Syntax ${...} is not supported.', $this->position);

			} elseif (isset($m['Php_Variable'])) {
				$this->addToken(Token::Php_Variable, $m['Php_Variable']);
				if (isset($m[$type = 'Php_ObjectOperator'])
					|| isset($m[$type = 'Php_NullsafeObjectOperator'])
					|| isset($m[$type = 'Php_UndefinedsafeObjectOperator'])
				) {
					$this->addToken(constant(Token::class . '::' . $type), $m[$type]);
					$this->addToken(Token::Php_Identifier, $m['Php_Identifier']);

				} elseif (isset($m['offset'])) {
					$this->addToken(null, '[');
					if (!isset($m['offsetEnd'])) {
						throw new CompileException("Missing ']'", $this->position);
					} elseif (isset($m['offsetVar'])) {
						$this->addToken(Token::Php_Variable, $m['offsetVar']);
					} elseif (isset($m['offsetString'])) {
						$this->addToken(Token::Php_Identifier, $m['offsetString']);
					} elseif (isset($m['Php_NumString'])) {
						if (isset($m['offsetMinus'])) {
							$this->addToken(null, '-');
						}
						$this->addToken(Token::Php_NumString, $m['Php_NumString']);
					} else {
						throw new CompileException("Unexpected '" . substr($this->input, $this->offset - 1, 5) . "'", $this->position);
					}
					$this->addToken(null, ']');
				}

			} elseif (isset($m['end'])) {
				return $m['end'];
			}
		}

		throw new CompileException('Unterminated string.', $this->position->advance($buffer));
	}


	private function addToken(?int $type, string $text): void
	{
		$this->tokens[] = new Token($type ?? ord($text), $text, $this->position);
		$this->position = $this->position->advance($text);
		$this->offset += strlen($text);
	}
}
