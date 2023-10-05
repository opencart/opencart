<?php

declare(strict_types = 1);

namespace CodeLts\CliTools;

/**
 * ANSI escape sequences
 * @see https://tldp.org/HOWTO/Bash-Prompt-HOWTO/x361.html
 * @see http://ascii-table.com/ansi-escape-sequences.php
 */
class AnsiEscapeSequences
{
    /**
     * Move the cursor up 1 lines
     */
    public const MOVE_CURSOR_UP_1 = "\033[1A";

    /**
     * Move the cursor up 2 lines
     */
    public const MOVE_CURSOR_UP_2 = "\033[2A";

    /**
     * Move the cursor down 1 lines
     */
    public const MOVE_CURSOR_DOWN_1 = "\033[1B";

    /**
     * Move the cursor down 2 lines
     */
    public const MOVE_CURSOR_DOWN_2 = "\033[2B";

    /**
     * Erase to the end of the line
     */
    public const ERASE_TO_LINE_END = "\033[K";

    /**
     * Clear the screen, move to (0,0)
     */
    public const CLEAR_SCREEN_MOVE_0_0 = "\033[2J";
}
