<?php

/**
 * PECL xlswriter stubs for PhpStorm
 * https://pecl.php.net/package/xlswriter
 * https://www.php.net/manual/en/book.xlswriter.php
 */

namespace Vtiful\Kernel;

    /**
     * Class Excel
     * @link https://www.php.net/manual/en/class.vtiful-kernel-excel.php
     * @package Vtiful\Kernel
     */
    class Excel
    {
        public const TYPE_STRING = 0x01;
        public const TYPE_INT = 0x02;
        public const TYPE_DOUBLE = 0x04;
        public const TYPE_TIMESTAMP = 0x08;
        public const SKIP_NONE = 0x00;
        public const SKIP_EMPTY_ROW = 0x01;
        public const SKIP_EMPTY_CELLS = 0x02;
        public const GRIDLINES_HIDE_ALL = 0;
        public const GRIDLINES_SHOW_SCREEN = 1;
        public const GRIDLINES_SHOW_PRINT = 2;
        public const GRIDLINES_SHOW_ALL = 3;

        /**
         * Excel constructor.
         *
         * @param array $config
         */
        public function __construct(array $config) {}

        /**
         * File Name
         *
         * @param string $fileName
         * @param string $sheetName
         *
         * @return Excel
         */
        public function fileName(string $fileName, string $sheetName = 'Sheet1'): self
        {
            return $this;
        }

        /**
         * Const memory model
         *
         * @param string $fileName
         * @param string $sheetName
         *
         * @return Excel
         */
        public function constMemory(string $fileName, string $sheetName = 'Sheet1'): self
        {
            return $this;
        }

        /**
         * Add a new worksheet to a workbook.
         *
         * The worksheet name must be a valid Excel worksheet name, i.e. it must be
         * less than 32 character and it cannot contain any of the characters:
         *
         *     / \ [ ] : * ?
         *
         * In addition, you cannot use the same, case insensitive, `$sheetName` for more
         * than one worksheet.
         *
         * @param string|null $sheetName
         *
         * @return Excel
         */
        public function addSheet(?string $sheetName): self
        {
            return $this;
        }

        /**
         * Checkout worksheet
         *
         * @param string $sheetName
         *
         * @return Excel
         */
        public function checkoutSheet(string $sheetName): self
        {
            return $this;
        }

        /**
         * Insert data on the first line of the worksheet
         *
         * @param array $header
         *
         * @return Excel
         */
        public function header(array $header): self
        {
            return $this;
        }

        /**
         * Insert data on the worksheet
         *
         * @param array $data
         *
         * @return Excel
         */
        public function data(array $data): self
        {
            return $this;
        }

        /**
         * Generate file
         *
         * @return string
         */
        public function output(): string
        {
            return 'FilePath';
        }

        /**
         * Get file resource
         *
         * @return resource
         */
        public function getHandle() {}

        /**
         * Auto filter on the worksheet
         *
         * @param string $range
         *
         * @return Excel
         */
        public function autoFilter(string $range): self
        {
            return $this;
        }

        /**
         * Insert data on the cell
         *
         * @param int $row
         * @param int $column
         * @param int|string|float $data
         * @param string|null $format
         * @param resource|null $formatHandle
         *
         * @return Excel
         */
        public function insertText(int $row, int $column, $data, string $format = null, $formatHandle = null): self
        {
            return $this;
        }

        /**
         * Insert date on the cell
         *
         * @param int $row
         * @param int $column
         * @param int $timestamp
         * @param string|null $format
         * @param resource|null $formatHandle
         *
         * @return Excel
         */
        public function insertDate(int $row, int $column, int $timestamp, string $format = null, $formatHandle = null): self
        {
            return $this;
        }

        /**
         * Insert chart on the cell
         *
         * @param int $row
         * @param int $column
         * @param resource $chartResource
         *
         * @return Excel
         */
        public function insertChart(int $row, int $column, $chartResource): self
        {
            return $this;
        }

        /**
         * Insert url on the cell
         *
         * @param int $row
         * @param int $column
         * @param string $url
         * @param resource|null $formatHandle
         *
         * @return Excel
         */
        public function insertUrl(int $row, int $column, string $url, $formatHandle = null): self
        {
            return $this;
        }

        /**
         * Insert image on the cell
         *
         * @param int $row
         * @param int $column
         * @param string $imagePath
         * @param float $width
         * @param float $height
         *
         * @return Excel
         */
        public function insertImage(int $row, int $column, string $imagePath, float $width = 1, float $height = 1): self
        {
            return $this;
        }

        /**
         * Insert Formula on the cell
         *
         * @param int $row
         * @param int $column
         * @param string $formula
         *
         * @return Excel
         */
        public function insertFormula(int $row, int $column, string $formula): self
        {
            return $this;
        }

        /**
         * Merge cells
         *
         * @param string $range
         * @param string $data
         *
         * @return Excel
         */
        public function MergeCells(string $range, string $data): self
        {
            return $this;
        }

        /**
         * Set column cells width or format
         *
         * @param string $range
         * @param float $cellWidth
         * @param resource|null $formatHandle
         *
         * @return Excel
         */
        public function setColumn(string $range, float $cellWidth, $formatHandle = null): self
        {
            return $this;
        }

        /**
         * Set row cells height or format
         *
         * @param string $range
         * @param float $cellHeight
         * @param resource|null $formatHandle
         *
         * @return Excel
         */
        public function setRow(string $range, float $cellHeight, $formatHandle = null): self
        {
            return $this;
        }

        /**
         * Open xlsx file
         *
         * @param string $fileName
         *
         * @return Excel
         */
        public function openFile(string $fileName): self
        {
            return $this;
        }

        /**
         * Open sheet
         *
         * default open first sheet
         *
         * @param string|null $sheetName
         * @param int         $skipFlag
         *
         * @return Excel
         */
        public function openSheet(string $sheetName = null, int $skipFlag = 0x00): self
        {
            return $this;
        }

        /**
         * Set row cell data type
         *
         * @param array $types
         *
         * @return Excel
         */
        public function setType(array $types): self
        {
            return $this;
        }

        /**
         * Read values from the sheet
         *
         * @return array
         */
        public function getSheetData(): array
        {
            return [];
        }

        /**
         * Read values from the sheet
         *
         * @return array
         */
        public function nextRow(): array
        {
            return [];
        }

        /**
         * Next Cell In Callback
         *
         * @param callable $callback function(int $row, int $cell, string $data)
         * @param string|null $sheetName sheet name
         */
        public function nextCellCallback(callable $callback, string $sheetName = null): void {}

        /**
         * Freeze panes
         *
         * freezePanes(1, 0); // Freeze the first row.
         * freezePanes(0, 1); // Freeze the first column.
         * freezePanes(1, 1); // Freeze first row/column.
         *
         * @param int $row
         * @param int $column
         *
         * @return static
         */
        public function freezePanes(int $row, int $column): self
        {
            return $this;
        }

        /**
         * Gridline
         *
         * Display or hide screen and print gridlines using one of the values of
         *
         * \Vtiful\Kernel\Excel::GRIDLINES_HIDE_ALL
         * \Vtiful\Kernel\Excel::GRIDLINES_SHOW_ALL
         * \Vtiful\Kernel\Excel::GRIDLINES_SHOW_PRINT
         * \Vtiful\Kernel\Excel::GRIDLINES_SHOW_SCREEN
         *
         * Excel default is that the screen gridlines are on and the printed worksheet is off.
         *
         * @param int $option
         *
         * @return static
         */
        public function gridline(int $option): self
        {
            return $this;
        }

        /**
         * Worksheet zoom
         *
         * Set the worksheet zoom factor in the range 10 <= zoom <= 400:
         *
         * @param int $scale
         *
         * @return static
         */
        public function zoom(int $scale): self
        {
            return $this;
        }
    }

    /**
     * Class Format
     *
     * @link https://www.php.net/manual/en/class.vtiful-kernel-format.php
     * @package Vtiful\Kernel
     */
    class Format
    {
        public const UNDERLINE_SINGLE = 0x00;
        public const UNDERLINE_DOUBLE = 0x00;
        public const UNDERLINE_SINGLE_ACCOUNTING = 0x00;
        public const UNDERLINE_DOUBLE_ACCOUNTING = 0x00;
        public const FORMAT_ALIGN_LEFT = 0x00;
        public const FORMAT_ALIGN_CENTER = 0x00;
        public const FORMAT_ALIGN_RIGHT = 0x00;
        public const FORMAT_ALIGN_FILL = 0x00;
        public const FORMAT_ALIGN_JUSTIFY = 0x00;
        public const FORMAT_ALIGN_CENTER_ACROSS = 0x00;
        public const FORMAT_ALIGN_DISTRIBUTED = 0x00;
        public const FORMAT_ALIGN_VERTICAL_TOP = 0x00;
        public const FORMAT_ALIGN_VERTICAL_BOTTOM = 0x00;
        public const FORMAT_ALIGN_VERTICAL_CENTER = 0x00;
        public const FORMAT_ALIGN_VERTICAL_JUSTIFY = 0x00;
        public const FORMAT_ALIGN_VERTICAL_DISTRIBUTED = 0x00;
        public const COLOR_BLACK = 0x00;
        public const COLOR_BLUE = 0x00;
        public const COLOR_BROWN = 0x00;
        public const COLOR_CYAN = 0x00;
        public const COLOR_GRAY = 0x00;
        public const COLOR_GREEN = 0x00;
        public const COLOR_LIME = 0x00;
        public const COLOR_MAGENTA = 0x00;
        public const COLOR_NAVY = 0x00;
        public const COLOR_ORANGE = 0x00;
        public const COLOR_PINK = 0x00;
        public const COLOR_PURPLE = 0x00;
        public const COLOR_RED = 0x00;
        public const COLOR_SILVER = 0x00;
        public const COLOR_WHITE = 0x00;
        public const COLOR_YELLOW = 0x00;
        public const PATTERN_NONE = 0x00;
        public const PATTERN_SOLID = 0x00;
        public const PATTERN_MEDIUM_GRAY = 0x00;
        public const PATTERN_DARK_GRAY = 0x00;
        public const PATTERN_LIGHT_GRAY = 0x00;
        public const PATTERN_DARK_HORIZONTAL = 0x00;
        public const PATTERN_DARK_VERTICAL = 0x00;
        public const PATTERN_DARK_DOWN = 0x00;
        public const PATTERN_DARK_UP = 0x00;
        public const PATTERN_DARK_GRID = 0x00;
        public const PATTERN_DARK_TRELLIS = 0x00;
        public const PATTERN_LIGHT_HORIZONTAL = 0x00;
        public const PATTERN_LIGHT_VERTICAL = 0x00;
        public const PATTERN_LIGHT_DOWN = 0x00;
        public const PATTERN_LIGHT_UP = 0x00;
        public const PATTERN_LIGHT_GRID = 0x00;
        public const PATTERN_LIGHT_TRELLIS = 0x00;
        public const PATTERN_GRAY_125 = 0x00;
        public const PATTERN_GRAY_0625 = 0x00;
        public const BORDER_THIN = 0x00;
        public const BORDER_MEDIUM = 0x00;
        public const BORDER_DASHED = 0x00;
        public const BORDER_DOTTED = 0x00;
        public const BORDER_THICK = 0x00;
        public const BORDER_DOUBLE = 0x00;
        public const BORDER_HAIR = 0x00;
        public const BORDER_MEDIUM_DASHED = 0x00;
        public const BORDER_DASH_DOT = 0x00;
        public const BORDER_MEDIUM_DASH_DOT = 0x00;
        public const BORDER_DASH_DOT_DOT = 0x00;
        public const BORDER_MEDIUM_DASH_DOT_DOT = 0x00;
        public const BORDER_SLANT_DASH_DOT = 0x00;

        /**
         * Format constructor.
         *
         * @param resource $fileHandle
         */
        public function __construct($fileHandle) {}

        /**
         * Wrap
         *
         * @return static
         */
        public function wrap(): self
        {
            return $this;
        }

        /**
         * Bold
         *
         * @return static
         */
        public function bold(): self
        {
            return $this;
        }

        /**
         * Italic
         *
         * @return static
         */
        public function italic(): self
        {
            return $this;
        }

        /**
         * Cells border
         *
         * @param int $style const BORDER_***
         *
         * @return static
         */
        public function border(int $style): self
        {
            return $this;
        }

        /**
         * Align
         *
         * @param int ...$style const FORMAT_ALIGN_****
         *
         * @return static
         */
        public function align(...$style): self
        {
            return $this;
        }

        /**
         * Number format
         *
         * @param string $format
         *
         * #,##0
         *
         * @return static
         */
        public function number(string $format): self
        {
            return $this;
        }

        /**
         * Font color
         *
         * @param int $color const COLOR_****
         *
         * @return static
         */
        public function fontColor(int $color): self
        {
            return $this;
        }

        /**
         * Font
         *
         * @param string $fontName
         *
         * @return static
         */
        public function font(string $fontName): self
        {
            return $this;
        }

        /**
         * Font size
         *
         * @param float $size
         *
         * @return static
         */
        public function fontSize(float $size): self
        {
            return $this;
        }

        /**
         * String strikeout
         *
         * @return Format
         */
        public function strikeout(): self
        {
            return $this;
        }

        /**
         * Underline
         *
         * @param int $style const UNDERLINE_****
         *
         * @return static
         */
        public function underline(int $style): self
        {
            return $this;
        }

        /**
         * Cell background
         *
         * @param int $color const COLOR_****
         * @param int $pattern const PATTERN_****
         *
         * @return static
         */
        public function background(int $color, int $pattern = self::PATTERN_SOLID): self
        {
            return $this;
        }

        /**
         * Format to resource
         *
         * @return resource
         */
        public function toResource() {}
    }
