<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler;

use Latte\Compiler\Nodes\Php as Node;
use Latte\Compiler\Nodes\Php\Expression;
use Latte\Compiler\Nodes\Php\Scalar;


/** @internal generated trait used by TagParser */
abstract class TagParserData
{
	/** Symbol number of error recovery token */
	protected const ErrorSymbol = 1;

	/** Action number signifying default action */
	protected const DefaultAction = -8190;

	/** Rule number signifying that an unexpected token was encountered */
	protected const UnexpectedTokenRule = 8191;

	protected const Yy2Tblstate = 257;

	/** Number of non-leaf states */
	protected const NumNonLeafStates = 350;

	/** Map of lexer tokens to internal symbols */
	protected const TokenToSymbol = [
		0,     112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,
		112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   48,    107,   112,   108,   47,    112,   112,
		101,   102,   45,    43,    2,     44,    39,    46,    112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   22,    105,
		35,    7,     37,    21,    59,    112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,
		112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   61,    112,   106,   27,    112,   112,   100,   112,   112,
		112,   98,    112,   112,   112,   112,   112,   112,   112,   99,    112,   112,   112,   112,   112,   112,   112,   112,   112,   112,
		112,   112,   112,   103,   26,    104,   50,    112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,
		112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,
		112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,
		112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,
		112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,
		112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,
		112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   112,   1,     3,     4,     5,
		6,     8,     9,     10,    11,    12,    13,    14,    15,    16,    17,    18,    19,    20,    23,    24,    25,    28,    29,    30,
		31,    32,    33,    34,    36,    38,    40,    41,    42,    49,    51,    52,    53,    54,    55,    56,    57,    58,    60,    62,
		63,    64,    65,    66,    67,    68,    69,    70,    71,    72,    73,    74,    75,    76,    77,    78,    79,    80,    81,    82,
		83,    84,    85,    86,    87,    88,    89,    90,    109,   91,    92,    93,    94,    110,   111,   95,    96,    97,
	];

	/** Map of states to a displacement into the self::Action table. The corresponding action for this
	 *  state/symbol pair is self::Action[self::ActionBase[$state] + $symbol]. If self::ActionBase[$state] is 0, the
	 *  action is defaulted, i.e. self::ActionDefault[$state] should be used instead. */
	protected const ActionBase = [
		261,   304,   304,   304,   304,   99,    140,   304,   263,   181,   222,   304,   384,   384,   384,   384,   384,   159,   159,   159,
		247,   247,   212,   226,   354,   372,   374,   376,   -43,   -43,   -43,   -43,   -43,   -43,   -43,   -43,   -43,   -43,   -43,   -43,
		-43,   -43,   -43,   -43,   -43,   -43,   -43,   -43,   -43,   -43,   -43,   -43,   -43,   -43,   -43,   -43,   -43,   -43,   -43,   -43,
		-43,   -43,   -43,   -43,   -43,   -43,   -43,   -43,   -43,   -43,   -43,   -43,   -43,   -43,   -43,   -43,   -43,   -43,   -43,   -43,
		-43,   -43,   -43,   -43,   -43,   -43,   -43,   -43,   -43,   -43,   -43,   -43,   -43,   -43,   -43,   -43,   -43,   -43,   -43,   -43,
		-43,   -43,   -43,   -43,   -43,   -43,   -43,   -43,   -43,   21,    216,   380,   398,   382,   399,   412,   432,   435,   440,   457,
		52,    52,    52,    52,    52,    52,    52,    52,    52,    52,    52,    52,    52,    52,    167,   177,   538,   234,   234,   234,
		234,   234,   234,   234,   234,   234,   234,   234,   234,   234,   234,   234,   234,   234,   234,   543,   543,   543,   370,   499,
		494,   390,   5,     411,   411,   462,   462,   462,   462,   462,   58,    58,    58,    58,    156,   156,   156,   156,   45,    45,
		45,    45,    45,    45,    45,    45,    236,   3,     3,     7,     208,   250,   250,   250,   139,   139,   139,   139,   139,   267,
		111,   111,   111,   192,   233,   387,   257,   -64,   -64,   -64,   -64,   -64,   -64,   268,   405,   -12,   444,   444,   447,   168,
		168,   444,   469,   76,    286,   -15,   68,    471,   443,   453,   429,   318,   379,   219,   223,   232,   38,    38,    38,    38,
		159,   442,   442,   159,   159,   159,   98,    98,    98,    -84,   231,   -63,   8,     404,   231,   231,   231,   90,    57,    -32,
		316,   237,   291,   310,   33,    117,   46,    317,   320,   316,   316,   120,   46,    46,    244,   278,   240,   197,   113,   240,
		217,   217,   123,   31,    322,   321,   324,   289,   285,   407,   176,   221,   272,   214,   284,   239,   218,   322,   321,   324,
		224,   176,   227,   227,   227,   270,   227,   227,   227,   227,   227,   227,   227,   445,   1,     273,   326,   327,   335,   342,
		213,   220,   315,   227,   230,   209,   205,   450,   176,   275,   455,   406,   225,   243,   242,   262,   241,   456,   347,   403,
		194,   329,   198,   409,   215,   408,   228,   352,   428,   410,   0,     -43,   -43,   -43,   -43,   -43,   -43,   -43,   -43,   -43,
		-43,   -43,   -43,   -43,   -43,   -43,   -43,   -43,   -43,   -43,   -43,   -43,   -43,   -43,   -43,   -43,   -43,   -43,   0,     0,
		0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
		0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
		0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
		0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     52,
		52,    52,    52,    52,    52,    52,    52,    52,    52,    52,    0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
		0,     0,     0,     0,     52,    52,    52,    52,    52,    52,    52,    52,    52,    52,    52,    52,    52,    52,    52,    52,
		52,    52,    52,    52,    52,    52,    52,    52,    52,    52,    52,    0,     58,    52,    52,    52,    52,    52,    52,    52,
		0,     0,     0,     0,     111,   111,   111,   111,   139,   139,   139,   139,   139,   139,   139,   139,   111,   139,   139,   139,
		139,   139,   139,   139,   0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     469,   217,   217,   217,
		217,   217,   217,   469,   469,   0,     0,     0,     0,     111,   111,   0,     0,     469,   217,   0,     0,     0,     0,     0,
		0,     0,     159,   159,   159,   469,   0,     0,     0,     0,     0,     217,   217,   0,     0,     0,     0,     0,     0,     0,
		227,   0,     0,     1,     227,   227,   227,
	];

	/** Table of actions. Indexed according to self::ActionBase comment. */
	protected const Action = [
		34,    35,    374,   7,     375,   36,    -176,  37,    178,   179,   38,    39,    40,    41,    42,    43,    44,    -176,  1,     191,
		45,    544,   545,   203,   194,   527,   376,   -175,  194,   542,   284,   0,     243,   244,   173,   11,    285,   286,   -175,  99,
		12,    287,   288,   206,   289,   32,    -213,  172,   15,    -211,  529,   528,   550,   548,   549,   54,    55,    56,    29,    16,
		13,    357,   358,   356,   222,   289,   -213,  -213,  -213,  -211,  -211,  -211,  510,   23,    376,   57,    58,    59,    -211,  60,
		61,    62,    63,    64,    65,    66,    67,    68,    69,    70,    71,    72,    73,    74,    75,    76,    77,    78,    79,    80,
		367,   193,   357,   358,   356,   -45,   1,     361,   98,    -28,   493,   251,   81,    289,   376,   21,    195,   542,   196,   101,
		360,   359,   189,   205,   371,   232,   372,   355,   354,   30,    288,   364,   363,   366,   365,   362,   368,   369,   370,   14,
		-8190, 367,   -8190, 357,   358,   356,   49,    -8190, 361,   207,   208,   209,   222,   289,   76,    77,    78,    79,    80,    434,
		193,   192,   46,    553,   374,   199,   375,   296,   355,   354,   -8190, 81,    297,   363,   233,   234,   362,   368,   298,   299,
		-8190, -8190, 367,   95,    357,   358,   356,   355,   354,   361,   24,    -8191, -8191, -8191, -8191, 72,    73,    74,    75,    378,
		105,   416,   192,   46,    106,   525,   199,   527,   296,   355,   354,   -8190, -8190, 297,   363,   233,   234,   362,   368,   298,
		299,   107,   18,    367,   405,   357,   358,   356,   96,    194,   361,   25,    529,   528,   407,   108,   406,   -8190, -8190, -8190,
		-22,   -16,   416,   192,   46,    -15,   19,    199,   97,    296,   355,   354,   104,   -214,  297,   363,   233,   234,   362,   368,
		298,   299,   190,   170,   367,   171,   357,   358,   356,   197,   198,   361,   26,    -214,  -214,  -214,  73,    74,    75,    320,
		193,   102,   -175,  416,   192,   46,    376,   81,    199,   -21,   296,   355,   354,   -175,  -210,  297,   363,   233,   234,   362,
		368,   298,   299,   -8190, -184,  367,   -243,  357,   358,   356,   -241,  630,   361,   27,    -210,  -210,  -210,  625,   -260,  376,
		-260,  277,   341,   -210,  416,   192,   46,    274,   -214,  199,   336,   296,   355,   354,   -217,  -8190, 297,   363,   233,   234,
		362,   368,   298,   299,   554,   -8190, 367,   -8190, -214,  -214,  -214,  50,    628,   361,   100,   555,   629,   -175,  289,   33,
		261,   3,     162,   241,   276,   416,   192,   46,    -175,  386,   199,   -217,  296,   -8190, -8190, -8190, -216,  297,   363,   233,
		234,   362,   368,   298,   299,   -215,  2,     357,   358,   356,   4,     -8190, 5,     -8190, -8190, 47,    48,    17,    82,    83,
		84,    85,    86,    87,    88,    89,    90,    91,    92,    93,    94,    6,     355,   354,   -8190, -8190, -8190, 8,     9,     627,
		-260,  10,    28,    51,    -260,  52,    367,   187,   188,   -257,  240,   -257,  -8190, 361,   -8190, -8190, -8190, 498,   -8190, -8190,
		-8190, 456,   458,   -254,  540,   -254,  192,   46,    -209,  417,   199,   -208,  296,   -71,   513,   -71,   519,   297,   363,   233,
		234,   362,   368,   298,   299,   -8190, -8190, -8190, -209,  -209,  -209,  -208,  -208,  -208,  521,   100,   523,   -209,  572,   -71,
		-208,  -28,   329,   -8190, 331,   -8190, -8190, -8190, -216,  -8190, -8190, -8190, -8191, -8191, -8191, -8191, -8191, -8190, -8190, -8190,
		532,   499,   -8190, -8190, -8190, -208,  597,   387,   31,    20,    53,    259,   543,   626,   509,   -8190, 624,   -8190, -8190, -8190,
		-8190, -8190, -8190, -8190, -8190, -208,  -208,  -208,  210,   211,   212,   -257,  224,   349,   -208,  -257,  581,   242,   376,   592,
		617,   -8190, -8190, -8190, 595,   -254,  -8190, -8190, -8190, -254,  289,   539,   246,   247,   248,   -71,   569,   22,    181,   0,
		103,   585,   620,   343,   -8190,
	];

	/** Table indexed analogously to self::Action. If self::ActionCheck[self::ActionBase[$state] + $symbol] != $symbol
	 *  then the action is defaulted, i.e. self::ActionDefault[$state] should be used instead. */
	protected const ActionCheck = [
		43,    44,    66,    2,     68,    48,    90,    50,    51,    52,    53,    54,    55,    56,    57,    58,    59,    101,   61,    62,
		63,    64,    65,    66,    21,    68,    69,    90,    21,    72,    73,    0,     75,    76,    26,    2,     79,    80,    101,   103,
		2,     84,    85,    86,    108,   77,    61,    26,    2,     61,    93,    94,    95,    96,    97,    3,     4,     5,     101,   2,
		22,    3,     4,     5,     107,   108,   81,    82,    83,    81,    82,    83,    104,   21,    69,    23,    24,    25,    90,    27,
		28,    29,    30,    31,    32,    33,    34,    35,    36,    37,    38,    39,    40,    41,    42,    43,    44,    45,    46,    47,
		42,    49,    3,     4,     5,     102,   61,    49,    103,   102,   102,   66,    60,    108,   69,    2,     26,    72,    28,    2,
		62,    63,    2,     102,   66,    2,     68,    28,    29,    61,    85,    73,    74,    75,    76,    77,    78,    79,    80,    101,
		95,    42,    97,    3,     4,     5,     101,   71,    49,    81,    82,    83,    107,   108,   43,    44,    45,    46,    47,    102,
		49,    62,    63,    87,    66,    66,    68,    68,    28,    29,    3,     60,    73,    74,    75,    76,    77,    78,    79,    80,
		3,     4,     42,    7,     3,     4,     5,     28,    29,    49,    91,    35,    36,    37,    38,    39,    40,    41,    42,    2,
		6,     102,   62,    63,    6,     66,    66,    68,    68,    28,    29,    43,    44,    73,    74,    75,    76,    77,    78,    79,
		80,    6,     6,     42,    85,    3,     4,     5,     7,     21,    49,    91,    93,    94,    95,    7,     97,    3,     4,     5,
		22,    22,    102,   62,    63,    22,    22,    66,    22,    68,    28,    29,    22,    61,    73,    74,    75,    76,    77,    78,
		79,    80,    22,    26,    42,    26,    3,     4,     5,     26,    28,    49,    91,    81,    82,    83,    40,    41,    42,    67,
		49,    61,    90,    102,   62,    63,    69,    60,    66,    22,    68,    28,    29,    101,   61,    73,    74,    75,    76,    77,
		78,    79,    80,    71,    90,    42,    101,   3,     4,     5,     101,   70,    49,    91,    81,    82,    83,    104,   0,     69,
		2,     74,    78,    90,    102,   62,    63,    102,   61,    66,    44,    68,    28,    29,    101,   85,    73,    74,    75,    76,
		77,    78,    79,    80,    87,    95,    42,    97,    81,    82,    83,    101,   66,    49,    91,    87,    70,    90,    108,   98,
		99,    100,   90,    90,    102,   102,   62,    63,    101,   91,    66,    101,   68,    3,     4,     5,     101,   73,    74,    75,
		76,    77,    78,    79,    80,    101,   101,   3,     4,     5,     101,   21,    101,   23,    24,    91,    92,    7,     8,     9,
		10,    11,    12,    13,    14,    15,    16,    17,    18,    19,    20,    101,   28,    29,    3,     4,     5,     101,   101,   104,
		102,   101,   101,   101,   106,   101,   42,    101,   101,   0,     101,   2,     21,    49,    23,    24,    25,    102,   27,    28,
		29,    51,    52,    0,     102,   2,     62,    63,    61,    102,   66,    61,    68,    0,     102,   2,     102,   73,    74,    75,
		76,    77,    78,    79,    80,    3,     4,     5,     81,    82,    83,    81,    82,    83,    102,   91,    102,   90,    102,   26,
		90,    102,   102,   21,    102,   23,    24,    25,    101,   27,    28,    29,    30,    31,    32,    33,    34,    3,     4,     5,
		102,   102,   3,     4,     5,     61,    102,   104,   61,    103,   103,   103,   107,   104,   104,   21,    104,   23,    24,    25,
		21,    27,    23,    24,    25,    81,    82,    83,    81,    82,    83,    102,   61,    105,   90,    106,   104,   90,    69,    104,
		71,    3,     4,     5,     104,   102,   3,     4,     5,     106,   108,   106,   81,    82,    83,    102,   106,   88,    89,    -1,
		22,    106,   106,   106,   21,
	];

	/** Map of states to their default action */
	protected const ActionDefault = [
		8191,  252,   252,   30,    252,   8191,  8191,  252,   8191,  8191,  8191,  28,    8191,  8191,  8191,  28,    8191,  8191,  8191,  8191,
		38,    28,    8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  206,   206,   206,   8191,  8191,  8191,  8191,  8191,  8191,  8191,
		8191,  8191,  8191,  8191,  8191,  8191,  9,     8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,
		8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,
		8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,
		8191,  28,    8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  253,   8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,
		1,     261,   262,   76,    70,    207,   256,   259,   72,    75,    73,    42,    43,    49,    112,   114,   146,   113,   88,    93,
		94,    95,    96,    97,    98,    99,    100,   101,   102,   103,   104,   105,   86,    87,    158,   147,   145,   144,   110,   111,
		117,   85,    8191,  115,   116,   134,   135,   132,   133,   136,   8191,  8191,  8191,  8191,  137,   138,   139,   140,   8191,  8191,
		8191,  8191,  8191,  8191,  8191,  8191,  118,   62,    62,    62,    8191,  8191,  10,    8191,  8191,  8191,  8191,  8191,  8191,  197,
		124,   125,   127,   197,   196,   142,   8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  202,   107,   109,   181,   119,
		120,   89,    8191,  8191,  8191,  201,   8191,  269,   208,   208,   208,   208,   33,    33,    33,    8191,  81,    81,    81,    81,
		33,    8191,  8191,  33,    33,    33,    8191,  8191,  8191,  187,   130,   214,   8191,  8191,  121,   122,   123,   50,    8191,  8191,
		185,   8191,  174,   8191,  27,    27,    27,    8191,  227,   228,   229,   27,    27,    27,    162,   35,    64,    27,    27,    64,
		8191,  8191,  27,    8191,  8191,  8191,  8191,  8191,  8191,  8191,  8191,  191,   8191,  212,   225,   2,     177,   14,    19,    20,
		8191,  255,   128,   129,   131,   210,   150,   151,   152,   153,   154,   155,   156,   8191,  248,   180,   8191,  8191,  8191,  8191,
		268,   8191,  208,   126,   8191,  188,   232,   8191,  258,   209,   8191,  8191,  8191,  52,    53,    8191,  8191,  8191,  8191,  8191,
		8191,  8191,  8191,  8191,  8191,  8191,  48,    8191,  8191,  8191,
	];

	/** Map of non-terminals to a displacement into the self::Goto table. The corresponding goto state for this
	 *  non-terminal/state pair is self::Goto[self::GotoBase[$nonTerminal] + $state] (unless defaulted) */
	protected const GotoBase = [
		0,     0,     -1,    0,     0,     107,   0,     162,   15,    -39,   -84,   0,     261,   14,    0,     0,     0,     0,     118,   140,
		-2,    0,     1,     0,     5,     -75,   0,     0,     -60,   -8,    -255,  103,   -11,   21,    0,     0,     13,    242,   29,    0,
		47,    0,     0,     234,   0,     0,     0,     27,    0,     0,     0,     0,     -21,   -43,   0,     0,     37,    45,    7,     53,
		-25,   -66,   0,     0,     -50,   -32,   0,     28,    109,   4,     33,    0,     0,
	];

	/** Table of states to goto after reduction. Indexed according to self::GotoBase comment. */
	protected const Goto = [
		110,   110,   110,   110,   419,   419,   110,   518,   520,   319,   110,   599,   522,   571,   573,   574,   138,   126,   127,   123,
		123,   115,   136,   128,   128,   128,   128,   123,   109,   125,   125,   125,   120,   302,   303,   250,   304,   306,   307,   308,
		309,   310,   311,   312,   442,   442,   121,   122,   111,   112,   113,   114,   116,   134,   135,   137,   155,   158,   159,   160,
		163,   164,   165,   166,   167,   168,   169,   174,   175,   176,   177,   186,   200,   201,   202,   219,   220,   254,   255,   256,
		323,   139,   140,   141,   142,   143,   144,   145,   146,   147,   148,   149,   150,   151,   152,   153,   156,   117,   118,   128,
		129,   119,   157,   130,   131,   154,   132,   133,   180,   180,   180,   180,   326,   253,   180,   272,   273,   258,   180,   401,
		408,   410,   409,   411,   183,   184,   185,   404,   404,   404,   404,   524,   524,   524,   404,   404,   404,   404,   404,   391,
		223,   584,   584,   584,   526,   526,   526,   526,   526,   526,   526,   526,   526,   526,   526,   526,   235,   596,   596,   596,
		596,   596,   596,   300,   300,   300,   300,   227,   318,   300,   315,   315,   315,   300,   227,   227,   269,   270,   586,   587,
		588,   424,   338,   227,   227,   631,   335,   394,   433,   432,   397,   582,   582,   344,   389,   415,   227,   214,   514,   216,
		217,   228,   322,   229,   221,   230,   231,   541,   541,   541,   541,   541,   541,   541,   541,   563,   563,   563,   563,   563,
		563,   563,   563,   561,   561,   561,   561,   561,   561,   561,   561,   305,   305,   305,   305,   305,   305,   305,   305,   615,
		491,   346,   517,   301,   301,   301,   301,   316,   317,   301,   430,   427,   428,   301,   0,     615,   616,   316,   317,   275,
		328,   621,   622,   623,   381,   330,   348,   515,   616,   589,   590,   345,   382,   0,     0,     0,     0,     0,     0,     0,
		0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
		0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,     0,
		0,     0,     0,     0,     325,   0,     0,     0,     0,     0,     0,     0,     236,   237,   238,   239,   0,     0,     0,     384,
		384,   384,   0,     0,     0,     0,     0,     384,   0,     0,     384,   384,   384,
	];

	/** Table indexed analogously to self::Goto. If self::GotoCheck[self::GotoBase[$nonTerminal] + $state] != $nonTerminal
	 *  then the goto state is defaulted, i.e. self::GotoDefault[$nonTerminal] should be used. */
	protected const GotoCheck = [
		2,     2,     2,     2,     32,    32,    2,     30,    30,    58,    2,     69,    30,    30,    30,    30,    2,     2,     2,     2,
		2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,
		2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,
		2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,
		2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,
		2,     2,     2,     2,     2,     2,     2,     2,     5,     5,     5,     5,     64,    68,    5,     31,    31,    31,    5,     25,
		25,    25,    25,    25,    5,     5,     5,     28,    28,    28,    28,    28,    28,    28,    28,    28,    28,    28,    28,    18,
		61,    64,    64,    64,    53,    53,    53,    53,    53,    53,    53,    53,    53,    53,    53,    53,    61,    64,    64,    64,
		64,    64,    64,    7,     7,     7,     7,     9,     19,    7,     52,    52,    52,    7,     9,     9,     65,    65,    65,    65,
		65,    10,    10,    9,     9,     9,     20,    10,    10,    10,    22,    64,    64,    10,    10,    24,    9,     60,    10,    33,
		33,    33,    33,    33,    33,    33,    33,    38,    38,    38,    38,    38,    38,    38,    38,    56,    56,    56,    56,    56,
		56,    56,    56,    57,    57,    57,    57,    57,    57,    57,    57,    59,    59,    59,    59,    59,    59,    59,    59,    70,
		40,    9,     9,     37,    37,    37,    37,    13,    13,    37,    36,    36,    36,    37,    -1,    70,    70,    13,    13,    13,
		37,    8,     8,     8,     12,    43,    43,    47,    70,    67,    67,    29,    12,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
		-1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
		-1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,    -1,
		-1,    -1,    -1,    -1,    7,     -1,    -1,    -1,    -1,    -1,    -1,    -1,    7,     7,     7,     7,     -1,    -1,    -1,    5,
		5,     5,     -1,    -1,    -1,    -1,    -1,    5,     -1,    -1,    5,     5,     5,
	];

	/** Map of non-terminals to the default state to goto after their reduction */
	protected const GotoDefault = [
		-8192, 283,   124,   295,   353,   182,   373,   324,   594,   580,   379,   264,   601,   281,   280,   441,   339,   278,   390,   340,
		332,   271,   396,   245,   413,   257,   333,   334,   262,   342,   536,   266,   418,   161,   265,   252,   429,   290,   291,   440,
		260,   507,   279,   327,   511,   347,   282,   516,   570,   263,   292,   267,   533,   249,   218,   293,   225,   215,   313,   204,
		213,   614,   226,   294,   568,   268,   576,   583,   314,   600,   613,   321,   337,
	];

	/** Map of rules to the non-terminal on their left-hand side, i.e. the non-terminal to use for
	 *  determining the state to goto after reduction. */
	protected const RuleToNonTerminal = [
		0,     1,     1,     1,     5,     5,     6,     6,     6,     6,     6,     6,     6,     6,     6,     6,     6,     6,     6,     6,
		6,     7,     7,     7,     8,     8,     9,     10,    10,    4,     4,     11,    11,    13,    13,    14,    14,    15,    16,    16,
		17,    17,    18,    18,    20,    20,    21,    21,    22,    22,    24,    24,    24,    24,    25,    25,    25,    25,    26,    26,
		27,    27,    23,    23,    29,    29,    30,    30,    31,    31,    32,    32,    32,    32,    19,    34,    34,    35,    35,    3,
		3,     36,    36,    36,    36,    2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,
		2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,
		2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,
		2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,     2,
		2,     39,    42,    42,    45,    46,    46,    47,    48,    48,    48,    48,    48,    48,    52,    28,    28,    53,    53,    53,
		40,    40,    40,    50,    50,    44,    44,    56,    57,    38,    59,    59,    59,    59,    41,    41,    41,    41,    41,    41,
		41,    41,    41,    41,    41,    41,    43,    43,    55,    55,    55,    55,    62,    62,    62,    49,    49,    49,    63,    63,
		63,    63,    63,    63,    63,    33,    33,    33,    33,    33,    64,    64,    67,    66,    54,    54,    54,    54,    54,    54,
		54,    51,    51,    51,    65,    65,    65,    37,    58,    68,    68,    69,    69,    12,    12,    12,    12,    12,    12,    12,
		12,    12,    12,    60,    60,    60,    60,    61,    71,    70,    70,    70,    70,    70,    70,    70,    70,    70,    72,    72,
		72,    72,
	];

	/** Map of rules to the length of their right-hand side, which is the number of elements that have to
	 *  be popped from the stack(s) on reduction. */
	protected const RuleToLength = [
		1,     2,     2,     2,     1,     1,     1,     1,     1,     1,     1,     1,     1,     1,     1,     1,     1,     1,     1,     1,
		1,     1,     1,     1,     1,     1,     1,     0,     1,     2,     0,     1,     3,     0,     1,     0,     1,     7,     0,     2,
		1,     3,     3,     4,     2,     0,     1,     3,     4,     6,     1,     2,     1,     1,     1,     1,     1,     1,     3,     3,
		3,     3,     0,     1,     0,     2,     2,     4,     1,     3,     1,     2,     2,     3,     2,     3,     1,     4,     4,     3,
		4,     0,     3,     3,     3,     1,     3,     3,     3,     4,     1,     1,     2,     3,     3,     3,     3,     3,     3,     3,
		3,     3,     3,     3,     3,     3,     2,     2,     2,     2,     3,     3,     3,     3,     3,     3,     3,     3,     3,     3,
		3,     3,     3,     3,     3,     3,     3,     3,     2,     2,     2,     2,     3,     3,     3,     3,     3,     3,     3,     3,
		3,     3,     3,     3,     5,     4,     3,     3,     4,     4,     2,     2,     2,     2,     2,     2,     2,     1,     8,     12,
		9,     3,     0,     4,     2,     1,     3,     2,     2,     4,     2,     4,     4,     6,     1,     1,     1,     1,     1,     1,
		1,     1,     3,     1,     1,     0,     1,     1,     3,     3,     4,     1,     1,     3,     1,     1,     1,     1,     1,     1,
		1,     1,     1,     3,     2,     3,     0,     1,     1,     3,     1,     1,     1,     1,     1,     1,     3,     1,     1,     4,
		1,     4,     6,     4,     4,     1,     1,     3,     3,     3,     1,     4,     1,     3,     1,     4,     3,     3,     3,     3,
		3,     1,     3,     1,     1,     3,     1,     4,     1,     3,     1,     1,     0,     1,     2,     1,     3,     4,     3,     3,
		4,     2,     2,     2,     2,     1,     2,     1,     1,     1,     4,     3,     3,     3,     3,     3,     6,     3,     1,     1,
		2,     1,
	];

	/** Map of symbols to their names */
	protected const SymbolToName = [
		'end',
		'error',
		"','",
		"'or'",
		"'xor'",
		"'and'",
		"'=>'",
		"'='",
		"'+='",
		"'-='",
		"'*='",
		"'/='",
		"'.='",
		"'%='",
		"'&='",
		"'|='",
		"'^='",
		"'<<='",
		"'>>='",
		"'**='",
		"'??='",
		"'?'",
		"':'",
		"'??'",
		"'||'",
		"'&&'",
		"'|'",
		"'^'",
		"'&'",
		"'&'",
		"'=='",
		"'!='",
		"'==='",
		"'!=='",
		"'<=>'",
		"'<'",
		"'<='",
		"'>'",
		"'>='",
		"'.'",
		"'<<'",
		"'>>'",
		"'in'",
		"'+'",
		"'-'",
		"'*'",
		"'/'",
		"'%'",
		"'!'",
		"'instanceof'",
		"'~'",
		"'++'",
		"'--'",
		"'(int)'",
		"'(float'",
		"'(string)'",
		"'(array)'",
		"'(object)'",
		"'(bool)'",
		"'@'",
		"'**'",
		"'['",
		"'new'",
		"'clone'",
		'integer',
		'floating-point number',
		'identifier',
		'variable name',
		'constant',
		'variable',
		'number',
		'string content',
		'quoted string',
		"'match'",
		"'default'",
		"'function'",
		"'fn'",
		"'return'",
		"'use'",
		"'isset'",
		"'empty'",
		"'->'",
		"'?->'",
		"'??->'",
		"'list'",
		"'array'",
		"'heredoc start'",
		"'heredoc end'",
		"'\${'",
		"'{\$'",
		"'::'",
		"'...'",
		"'(expand)'",
		'fully qualified name',
		'namespaced name',
		"'null'",
		"'true'",
		"'false'",
		"'e'",
		"'m'",
		"'a'",
		"'('",
		"')'",
		"'{'",
		"'}'",
		"';'",
		"']'",
		"'\"'",
		"'$'",
		"'\\\\'",
		'whitespace',
		'comment',
	];

	/** Temporary value containing the result of last semantic action (reduction) */
	protected mixed $semValue = null;

	/** Semantic value stack (contains values of tokens and semantic action results) */
	protected array $semStack;

	/** @var Token[] Start attribute stack */
	protected array $startTokenStack;


	protected function reduce(int $rule, int $pos): void
	{
		(match ($rule) {
			0, 1, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 27, 28, 50, 63, 65, 85, 90, 91, 157, 174, 176, 180, 181, 183, 184, 186, 191, 196, 201, 202, 207, 208, 210, 211, 212, 213, 215, 217, 218, 220, 225, 226, 230, 234, 241, 243, 244, 246, 251, 269, 281 => fn() => $this->semValue = $this->semStack[$pos],
			2 => fn() => $this->semValue = new Node\ModifierNode($this->semStack[$pos], position: $this->startTokenStack[$pos]->position),
			3 => fn() => $this->semValue = new Expression\ArrayNode($this->semStack[$pos], position: $this->startTokenStack[$pos]->position),
			21, 22, 23, 24, 25, 55, 56, 57 => fn() => $this->semValue = new Node\IdentifierNode($this->semStack[$pos], $this->startTokenStack[$pos]->position),
			26 => fn() => $this->semValue = new Expression\VariableNode(substr($this->semStack[$pos], 1), $this->startTokenStack[$pos]->position),
			29, 39, 44, 74, 82, 83, 84, 142, 143, 163, 164, 182, 209, 216, 242, 245, 277 => fn() => $this->semValue = $this->semStack[$pos - 1],
			30, 38, 45, 66, 81, 162, 185 => fn() => $this->semValue = [],
			31, 40, 46, 68, 76, 165, 250, 265 => fn() => $this->semValue = [$this->semStack[$pos]],
			32, 41, 47, 59, 61, 69, 75, 166, 249 => function () use ($pos) {
				$this->semStack[$pos - 2][] = $this->semStack[$pos];
				$this->semValue = $this->semStack[$pos - 2];
			},
			33, 35 => fn() => $this->semValue = false,
			34, 36 => fn() => $this->semValue = true,
			37 => fn() => $this->semValue = new Expression\MatchNode($this->semStack[$pos - 4], $this->semStack[$pos - 1], $this->startTokenStack[$pos - 6]->position),
			42 => fn() => $this->semValue = new Node\MatchArmNode($this->semStack[$pos - 2], $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			43 => fn() => $this->semValue = new Node\MatchArmNode(null, $this->semStack[$pos], $this->startTokenStack[$pos - 3]->position),
			48 => fn() => $this->semValue = new Node\ParameterNode($this->semStack[$pos], null, $this->semStack[$pos - 3], $this->semStack[$pos - 2], $this->semStack[$pos - 1], $this->startTokenStack[$pos - 3]->position),
			49 => fn() => $this->semValue = new Node\ParameterNode($this->semStack[$pos - 2], $this->semStack[$pos], $this->semStack[$pos - 5], $this->semStack[$pos - 4], $this->semStack[$pos - 3], $this->startTokenStack[$pos - 5]->position),
			51 => fn() => $this->semValue = new Node\NullableTypeNode($this->semStack[$pos], $this->startTokenStack[$pos - 1]->position),
			52 => fn() => $this->semValue = new Node\UnionTypeNode($this->semStack[$pos], $this->startTokenStack[$pos]->position),
			53 => fn() => $this->semValue = new Node\IntersectionTypeNode($this->semStack[$pos], $this->startTokenStack[$pos]->position),
			54 => fn() => $this->semValue = TagParser::handleBuiltinTypes($this->semStack[$pos]),
			58, 60 => fn() => $this->semValue = [$this->semStack[$pos - 2], $this->semStack[$pos]],
			62, 64, 206, 252 => fn() => $this->semValue = null,
			67 => fn() => $this->semValue = $this->semStack[$pos - 2],
			70 => fn() => $this->semValue = new Node\ArgumentNode($this->semStack[$pos], false, false, null, $this->startTokenStack[$pos]->position),
			71 => fn() => $this->semValue = new Node\ArgumentNode($this->semStack[$pos], true, false, null, $this->startTokenStack[$pos - 1]->position),
			72 => fn() => $this->semValue = new Node\ArgumentNode($this->semStack[$pos], false, true, null, $this->startTokenStack[$pos - 1]->position),
			73 => fn() => $this->semValue = new Node\ArgumentNode($this->semStack[$pos], false, false, $this->semStack[$pos - 2], $this->startTokenStack[$pos - 2]->position),
			77, 78 => fn() => $this->semValue = new Expression\FilterCallNode($this->semStack[$pos - 3], new Node\FilterNode($this->semStack[$pos - 1], $this->semStack[$pos], $this->startTokenStack[$pos - 3]->position), $this->startTokenStack[$pos - 3]->position),
			79 => fn() => $this->semValue = [new Node\FilterNode($this->semStack[$pos - 1], $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position)],
			80 => function () use ($pos) {
				$this->semStack[$pos - 3][] = new Node\FilterNode($this->semStack[$pos - 1], $this->semStack[$pos], $this->startTokenStack[$pos - 3]->position);
				$this->semValue = $this->semStack[$pos - 3];
			},
			86, 87, 88 => fn() => $this->semValue = new Expression\AssignNode($this->semStack[$pos - 2], $this->semStack[$pos], false, $this->startTokenStack[$pos - 2]->position),
			89 => fn() => $this->semValue = new Expression\AssignNode($this->semStack[$pos - 3], $this->semStack[$pos], true, $this->startTokenStack[$pos - 3]->position),
			92 => fn() => $this->semValue = new Expression\CloneNode($this->semStack[$pos], $this->startTokenStack[$pos - 1]->position),
			93 => fn() => $this->semValue = new Expression\AssignOpNode($this->semStack[$pos - 2], '+', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			94 => fn() => $this->semValue = new Expression\AssignOpNode($this->semStack[$pos - 2], '-', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			95 => fn() => $this->semValue = new Expression\AssignOpNode($this->semStack[$pos - 2], '*', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			96 => fn() => $this->semValue = new Expression\AssignOpNode($this->semStack[$pos - 2], '/', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			97 => fn() => $this->semValue = new Expression\AssignOpNode($this->semStack[$pos - 2], '.', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			98 => fn() => $this->semValue = new Expression\AssignOpNode($this->semStack[$pos - 2], '%', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			99 => fn() => $this->semValue = new Expression\AssignOpNode($this->semStack[$pos - 2], '&', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			100 => fn() => $this->semValue = new Expression\AssignOpNode($this->semStack[$pos - 2], '|', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			101 => fn() => $this->semValue = new Expression\AssignOpNode($this->semStack[$pos - 2], '^', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			102 => fn() => $this->semValue = new Expression\AssignOpNode($this->semStack[$pos - 2], '<<', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			103 => fn() => $this->semValue = new Expression\AssignOpNode($this->semStack[$pos - 2], '>>', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			104 => fn() => $this->semValue = new Expression\AssignOpNode($this->semStack[$pos - 2], '**', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			105 => fn() => $this->semValue = new Expression\AssignOpNode($this->semStack[$pos - 2], '??', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			106 => fn() => $this->semValue = new Expression\PostOpNode($this->semStack[$pos - 1], '++', $this->startTokenStack[$pos - 1]->position),
			107 => fn() => $this->semValue = new Expression\PreOpNode($this->semStack[$pos], '++', $this->startTokenStack[$pos - 1]->position),
			108 => fn() => $this->semValue = new Expression\PostOpNode($this->semStack[$pos - 1], '--', $this->startTokenStack[$pos - 1]->position),
			109 => fn() => $this->semValue = new Expression\PreOpNode($this->semStack[$pos], '--', $this->startTokenStack[$pos - 1]->position),
			110 => fn() => $this->semValue = new Expression\BinaryOpNode($this->semStack[$pos - 2], '||', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			111 => fn() => $this->semValue = new Expression\BinaryOpNode($this->semStack[$pos - 2], '&&', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			112 => fn() => $this->semValue = new Expression\BinaryOpNode($this->semStack[$pos - 2], 'or', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			113 => fn() => $this->semValue = new Expression\BinaryOpNode($this->semStack[$pos - 2], 'and', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			114 => fn() => $this->semValue = new Expression\BinaryOpNode($this->semStack[$pos - 2], 'xor', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			115, 116 => fn() => $this->semValue = new Expression\BinaryOpNode($this->semStack[$pos - 2], '&', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			117 => fn() => $this->semValue = new Expression\BinaryOpNode($this->semStack[$pos - 2], '^', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			118 => fn() => $this->semValue = new Expression\BinaryOpNode($this->semStack[$pos - 2], '.', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			119 => fn() => $this->semValue = new Expression\BinaryOpNode($this->semStack[$pos - 2], '+', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			120 => fn() => $this->semValue = new Expression\BinaryOpNode($this->semStack[$pos - 2], '-', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			121 => fn() => $this->semValue = new Expression\BinaryOpNode($this->semStack[$pos - 2], '*', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			122 => fn() => $this->semValue = new Expression\BinaryOpNode($this->semStack[$pos - 2], '/', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			123 => fn() => $this->semValue = new Expression\BinaryOpNode($this->semStack[$pos - 2], '%', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			124 => fn() => $this->semValue = new Expression\BinaryOpNode($this->semStack[$pos - 2], '<<', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			125 => fn() => $this->semValue = new Expression\BinaryOpNode($this->semStack[$pos - 2], '>>', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			126 => fn() => $this->semValue = new Expression\BinaryOpNode($this->semStack[$pos - 2], '**', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			127 => fn() => $this->semValue = new Expression\InNode($this->semStack[$pos - 2], $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			128 => fn() => $this->semValue = new Expression\UnaryOpNode($this->semStack[$pos], '+', $this->startTokenStack[$pos - 1]->position),
			129 => fn() => $this->semValue = new Expression\UnaryOpNode($this->semStack[$pos], '-', $this->startTokenStack[$pos - 1]->position),
			130 => fn() => $this->semValue = new Expression\NotNode($this->semStack[$pos], $this->startTokenStack[$pos - 1]->position),
			131 => fn() => $this->semValue = new Expression\UnaryOpNode($this->semStack[$pos], '~', $this->startTokenStack[$pos - 1]->position),
			132 => fn() => $this->semValue = new Expression\BinaryOpNode($this->semStack[$pos - 2], '===', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			133 => fn() => $this->semValue = new Expression\BinaryOpNode($this->semStack[$pos - 2], '!==', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			134 => fn() => $this->semValue = new Expression\BinaryOpNode($this->semStack[$pos - 2], '==', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			135 => fn() => $this->semValue = new Expression\BinaryOpNode($this->semStack[$pos - 2], '!=', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			136 => fn() => $this->semValue = new Expression\BinaryOpNode($this->semStack[$pos - 2], '<=>', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			137 => fn() => $this->semValue = new Expression\BinaryOpNode($this->semStack[$pos - 2], '<', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			138 => fn() => $this->semValue = new Expression\BinaryOpNode($this->semStack[$pos - 2], '<=', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			139 => fn() => $this->semValue = new Expression\BinaryOpNode($this->semStack[$pos - 2], '>', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			140 => fn() => $this->semValue = new Expression\BinaryOpNode($this->semStack[$pos - 2], '>=', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			141 => fn() => $this->semValue = new Expression\InstanceofNode($this->semStack[$pos - 2], $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			144 => fn() => $this->semValue = new Expression\TernaryNode($this->semStack[$pos - 4], $this->semStack[$pos - 2], $this->semStack[$pos], $this->startTokenStack[$pos - 4]->position),
			145 => fn() => $this->semValue = new Expression\TernaryNode($this->semStack[$pos - 3], null, $this->semStack[$pos], $this->startTokenStack[$pos - 3]->position),
			146 => fn() => $this->semValue = new Expression\TernaryNode($this->semStack[$pos - 2], $this->semStack[$pos], null, $this->startTokenStack[$pos - 2]->position),
			147 => fn() => $this->semValue = new Expression\BinaryOpNode($this->semStack[$pos - 2], '??', $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			148 => fn() => $this->semValue = new Expression\IssetNode($this->semStack[$pos - 1], $this->startTokenStack[$pos - 3]->position),
			149 => fn() => $this->semValue = new Expression\EmptyNode($this->semStack[$pos - 1], $this->startTokenStack[$pos - 3]->position),
			150 => fn() => $this->semValue = new Expression\CastNode('int', $this->semStack[$pos], $this->startTokenStack[$pos - 1]->position),
			151 => fn() => $this->semValue = new Expression\CastNode('float', $this->semStack[$pos], $this->startTokenStack[$pos - 1]->position),
			152 => fn() => $this->semValue = new Expression\CastNode('string', $this->semStack[$pos], $this->startTokenStack[$pos - 1]->position),
			153 => fn() => $this->semValue = new Expression\CastNode('array', $this->semStack[$pos], $this->startTokenStack[$pos - 1]->position),
			154 => fn() => $this->semValue = new Expression\CastNode('object', $this->semStack[$pos], $this->startTokenStack[$pos - 1]->position),
			155 => fn() => $this->semValue = new Expression\CastNode('bool', $this->semStack[$pos], $this->startTokenStack[$pos - 1]->position),
			156 => fn() => $this->semValue = new Expression\ErrorSuppressNode($this->semStack[$pos], $this->startTokenStack[$pos - 1]->position),
			158 => fn() => $this->semValue = new Expression\ClosureNode((bool) $this->semStack[$pos - 6], $this->semStack[$pos - 4], [], $this->semStack[$pos - 2], $this->semStack[$pos], $this->startTokenStack[$pos - 7]->position),
			159 => fn() => $this->semValue = new Expression\ClosureNode((bool) $this->semStack[$pos - 10], $this->semStack[$pos - 8], $this->semStack[$pos - 6], $this->semStack[$pos - 5], $this->semStack[$pos - 2], $this->startTokenStack[$pos - 11]->position),
			160 => fn() => $this->semValue = new Expression\ClosureNode((bool) $this->semStack[$pos - 7], $this->semStack[$pos - 5], $this->semStack[$pos - 3], $this->semStack[$pos - 2], null, $this->startTokenStack[$pos - 8]->position),
			161 => fn() => $this->semValue = new Expression\NewNode($this->semStack[$pos - 1], $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			167 => fn() => $this->semValue = new Node\ClosureUseNode($this->semStack[$pos], $this->semStack[$pos - 1], $this->startTokenStack[$pos - 1]->position),
			168, 170 => fn() => $this->semValue = $this->checkFunctionName(new Expression\FunctionCallNode($this->semStack[$pos - 1], $this->semStack[$pos], $this->startTokenStack[$pos - 1]->position)),
			169, 171 => fn() => $this->semValue = $this->checkFunctionName(new Expression\FunctionCallableNode($this->semStack[$pos - 3], $this->startTokenStack[$pos - 3]->position)),
			172 => fn() => $this->semValue = new Expression\StaticCallNode($this->semStack[$pos - 3], $this->semStack[$pos - 1], $this->semStack[$pos], $this->startTokenStack[$pos - 3]->position),
			173 => fn() => $this->semValue = new Expression\StaticCallableNode($this->semStack[$pos - 5], $this->semStack[$pos - 3], $this->startTokenStack[$pos - 5]->position),
			175, 177, 178 => fn() => $this->semValue = new Node\NameNode($this->semStack[$pos], Node\NameNode::KindNormal, $this->startTokenStack[$pos]->position),
			179 => fn() => $this->semValue = new Node\NameNode($this->semStack[$pos], Node\NameNode::KindFullyQualified, $this->startTokenStack[$pos]->position),
			187 => fn() => $this->semValue = new Expression\ConstantFetchNode($this->semStack[$pos], $this->startTokenStack[$pos]->position),
			188 => fn() => $this->semValue = new Expression\ClassConstantFetchNode($this->semStack[$pos - 2], $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			189 => fn() => $this->semValue = new Expression\ArrayNode($this->semStack[$pos - 1], $this->startTokenStack[$pos - 2]->position),
			190, 247 => fn() => $this->semValue = new Expression\ArrayNode($this->semStack[$pos - 1], $this->startTokenStack[$pos - 3]->position),
			192 => fn() => $this->semValue = Scalar\StringNode::parse($this->semStack[$pos], $this->startTokenStack[$pos]->position),
			193 => fn() => $this->semValue = Scalar\InterpolatedStringNode::parse($this->semStack[$pos - 1], $this->startTokenStack[$pos - 2]->position),
			194 => fn() => $this->semValue = Scalar\IntegerNode::parse($this->semStack[$pos], $this->startTokenStack[$pos]->position),
			195 => fn() => $this->semValue = Scalar\FloatNode::parse($this->semStack[$pos], $this->startTokenStack[$pos]->position),
			197, 278 => fn() => $this->semValue = new Scalar\StringNode($this->semStack[$pos], $this->startTokenStack[$pos]->position),
			198 => fn() => $this->semValue = new Scalar\BooleanNode(true, $this->startTokenStack[$pos]->position),
			199 => fn() => $this->semValue = new Scalar\BooleanNode(false, $this->startTokenStack[$pos]->position),
			200 => fn() => $this->semValue = new Scalar\NullNode($this->startTokenStack[$pos]->position),
			203 => fn() => $this->semValue = $this->parseDocString($this->semStack[$pos - 2], [$this->semStack[$pos - 1]], $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position, $this->startTokenStack[$pos]->position),
			204 => fn() => $this->semValue = $this->parseDocString($this->semStack[$pos - 1], [], $this->semStack[$pos], $this->startTokenStack[$pos - 1]->position, $this->startTokenStack[$pos]->position),
			205 => fn() => $this->semValue = $this->parseDocString($this->semStack[$pos - 2], $this->semStack[$pos - 1], $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position, $this->startTokenStack[$pos]->position),
			214 => fn() => $this->semValue = new Expression\ConstantFetchNode(new Node\NameNode($this->semStack[$pos], Node\NameNode::KindNormal, $this->startTokenStack[$pos]->position), $this->startTokenStack[$pos]->position),
			219, 235, 270 => fn() => $this->semValue = new Expression\ArrayAccessNode($this->semStack[$pos - 3], $this->semStack[$pos - 1], $this->startTokenStack[$pos - 3]->position),
			221 => fn() => $this->semValue = new Expression\MethodCallNode($this->semStack[$pos - 3], $this->semStack[$pos - 1], $this->semStack[$pos], false, $this->startTokenStack[$pos - 3]->position),
			222 => fn() => $this->semValue = new Expression\MethodCallableNode($this->semStack[$pos - 5], $this->semStack[$pos - 3], $this->startTokenStack[$pos - 5]->position),
			223 => fn() => $this->semValue = new Expression\MethodCallNode($this->semStack[$pos - 3], $this->semStack[$pos - 1], $this->semStack[$pos], true, $this->startTokenStack[$pos - 3]->position),
			224 => fn() => $this->semValue = new Expression\MethodCallNode(new Expression\BinaryOpNode($this->semStack[$pos - 3], '??', new Scalar\NullNode($this->startTokenStack[$pos - 3]->position), $this->startTokenStack[$pos - 3]->position), $this->semStack[$pos - 1], $this->semStack[$pos], true, $this->startTokenStack[$pos - 3]->position),
			227, 236, 271 => fn() => $this->semValue = new Expression\PropertyFetchNode($this->semStack[$pos - 2], $this->semStack[$pos], false, $this->startTokenStack[$pos - 2]->position),
			228, 237, 272 => fn() => $this->semValue = new Expression\PropertyFetchNode($this->semStack[$pos - 2], $this->semStack[$pos], true, $this->startTokenStack[$pos - 2]->position),
			229, 238, 273 => fn() => $this->semValue = new Expression\PropertyFetchNode(new Expression\BinaryOpNode($this->semStack[$pos - 2], '??', new Scalar\NullNode($this->startTokenStack[$pos - 2]->position), $this->startTokenStack[$pos - 2]->position), $this->semStack[$pos], true, $this->startTokenStack[$pos - 2]->position),
			231 => fn() => $this->semValue = new Expression\VariableNode($this->semStack[$pos - 1], $this->startTokenStack[$pos - 3]->position),
			232 => function () use ($pos) {
				$var = $this->semStack[$pos]->name;
				$this->semValue = \is_string($var)
					? new Node\VarLikeIdentifierNode($var, $this->startTokenStack[$pos]->position)
					: $var;
			},
			233, 239, 240 => fn() => $this->semValue = new Expression\StaticPropertyFetchNode($this->semStack[$pos - 2], $this->semStack[$pos], $this->startTokenStack[$pos - 2]->position),
			248 => function () use ($pos) {
				$this->semValue = $this->semStack[$pos];
				$end = count($this->semValue) - 1;
				if ($this->semValue[$end] === null) {
					array_pop($this->semValue);
				}
			},
			253, 255 => fn() => $this->semValue = new Node\ArrayItemNode($this->semStack[$pos], null, false, false, $this->startTokenStack[$pos]->position),
			254 => fn() => $this->semValue = new Node\ArrayItemNode($this->semStack[$pos], null, true, false, $this->startTokenStack[$pos - 1]->position),
			256, 258, 259 => fn() => $this->semValue = new Node\ArrayItemNode($this->semStack[$pos], $this->semStack[$pos - 2], false, false, $this->startTokenStack[$pos - 2]->position),
			257, 260 => fn() => $this->semValue = new Node\ArrayItemNode($this->semStack[$pos], $this->semStack[$pos - 3], true, false, $this->startTokenStack[$pos - 3]->position),
			261, 262 => fn() => $this->semValue = new Node\ArrayItemNode($this->semStack[$pos], null, false, true, $this->startTokenStack[$pos - 1]->position),
			263, 264 => function () use ($pos) {
				$this->semStack[$pos - 1][] = $this->semStack[$pos];
				$this->semValue = $this->semStack[$pos - 1];
			},
			266 => fn() => $this->semValue = [$this->semStack[$pos - 1], $this->semStack[$pos]],
			267 => fn() => $this->semValue = new Node\InterpolatedStringPartNode($this->semStack[$pos], $this->startTokenStack[$pos]->position),
			268 => fn() => $this->semValue = new Expression\VariableNode($this->semStack[$pos], $this->startTokenStack[$pos]->position),
			274, 275 => fn() => $this->semValue = new Expression\VariableNode($this->semStack[$pos - 1], $this->startTokenStack[$pos - 2]->position),
			276 => fn() => $this->semValue = new Expression\ArrayAccessNode($this->semStack[$pos - 4], $this->semStack[$pos - 2], $this->startTokenStack[$pos - 5]->position),
			279 => fn() => $this->semValue = TagParser::parseOffset($this->semStack[$pos], $this->startTokenStack[$pos]->position),
			280 => fn() => $this->semValue = TagParser::parseOffset('-' . $this->semStack[$pos], $this->startTokenStack[$pos - 1]->position),
		})();
	}
}
