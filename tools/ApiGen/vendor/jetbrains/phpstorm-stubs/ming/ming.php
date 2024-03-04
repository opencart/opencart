<?php

// Start of ming v.

class SWFShape
{
    public function __construct() {}

    public function setLine() {}

    public function addFill() {}

    public function setLeftFill() {}

    public function setRightFill() {}

    public function movePenTo() {}

    public function movePen() {}

    public function drawLineTo() {}

    public function drawLine() {}

    public function drawCurveTo() {}

    public function drawCurve() {}

    public function drawGlyph() {}

    public function drawCircle() {}

    public function drawArc() {}

    public function drawCubic() {}

    public function drawCubicTo() {}
}

class SWFFill
{
    public function __construct() {}

    public function moveTo() {}

    public function scaleTo() {}

    public function rotateTo() {}

    public function skewXTo() {}

    public function skewYTo() {}
}

class SWFGradient
{
    public function __construct() {}

    public function addEntry() {}
}

class SWFBitmap
{
    public function __construct() {}

    public function getWidth() {}

    public function getHeight() {}
}

class SWFText
{
    public function __construct() {}

    public function setFont() {}

    public function setHeight() {}

    public function setSpacing() {}

    public function setColor() {}

    public function moveTo() {}

    public function addString() {}

    public function addUTF8String() {}

    public function getWidth() {}

    public function getUTF8Width() {}

    public function getAscent() {}

    public function getDescent() {}

    public function getLeading() {}
}

class SWFTextField
{
    public function __construct() {}

    public function setFont() {}

    public function setBounds() {}

    public function align() {}

    public function setHeight() {}

    public function setLeftMargin() {}

    public function setRightMargin() {}

    public function setMargins() {}

    public function setIndentation() {}

    public function setLineSpacing() {}

    public function setColor() {}

    public function setName() {}

    public function addString() {}

    public function setPadding() {}

    public function addChars() {}
}

class SWFFont
{
    public function __construct() {}

    public function getWidth() {}

    public function getUTF8Width() {}

    public function getAscent() {}

    public function getDescent() {}

    public function getLeading() {}

    public function getShape() {}
}

class SWFDisplayItem
{
    public function moveTo() {}

    public function move() {}

    public function scaleTo() {}

    public function scale() {}

    public function rotateTo() {}

    public function rotate() {}

    public function skewXTo() {}

    public function skewX() {}

    public function skewYTo() {}

    public function skewY() {}

    public function setMatrix() {}

    public function setDepth() {}

    public function setRatio() {}

    public function addColor() {}

    public function multColor() {}

    public function setName() {}

    public function addAction() {}

    public function remove() {}

    public function setMaskLevel() {}

    public function endMask() {}

    public function getX() {}

    public function getY() {}

    public function getXScale() {}

    public function getYScale() {}

    public function getXSkew() {}

    public function getYSkew() {}

    public function getRot() {}
}

class SWFMovie
{
    public function __construct() {}

    public function nextFrame() {}

    public function labelFrame() {}

    public function add() {}

    public function remove() {}

    public function output() {}

    public function save() {}

    public function saveToFile() {}

    public function setBackground() {}

    public function setRate() {}

    public function setDimension() {}

    public function setFrames() {}

    public function streamMP3() {}

    public function addExport() {}

    public function writeExports() {}

    public function startSound() {}

    public function stopSound() {}

    public function importChar() {}

    public function importFont() {}

    public function addFont() {}

    public function protect() {}

    public function namedAnchor() {}
}

class SWFButton
{
    public function __construct() {}

    public function setHit() {}

    public function setOver() {}

    public function setUp() {}

    public function setDown() {}

    public function setAction() {}

    public function addShape() {}

    public function setMenu() {}

    public function addAction() {}

    public function addSound() {}
}

class SWFAction
{
    public function __construct() {}
}

class SWFMorph
{
    public function __construct() {}

    public function getShape1() {}

    public function getShape2() {}
}

class SWFSprite
{
    public function __construct() {}

    public function add() {}

    public function remove() {}

    public function nextFrame() {}

    public function labelFrame() {}

    public function setFrames() {}

    public function startSound() {}

    public function stopSound() {}
}

class SWFSound
{
    public function __construct() {}
}

class SWFFontChar
{
    public function addChars() {}

    public function addUTF8Chars() {}
}

class SWFSoundInstance
{
    public function noMultiple() {}

    public function loopInPoint() {}

    public function loopOutPoint() {}

    public function loopCount() {}
}

class SWFVideoStream
{
    public function __construct() {}

    public function setdimension() {}

    public function getnumframes() {}
}

/**
 * Set cubic threshold
 * @link https://php.net/manual/en/function.ming-setcubicthreshold.php
 * @param int $threshold <p>
 * The Threshold. Lower is more accurate, hence larger file size.
 * </p>
 * @return void
 */
function ming_setcubicthreshold($threshold) {}

/**
 * Set the global scaling factor.
 * @link https://php.net/manual/en/function.ming-setscale.php
 * @param float $scale <p>
 * The scale to be set.
 * </p>
 * @return void
 */
function ming_setscale($scale) {}

/**
 * Sets the SWF version
 * @link https://php.net/manual/en/function.ming-useswfversion.php
 * @param int $version <p>
 * SWF version to use.
 * </p>
 * @return void
 */
function ming_useswfversion($version) {}

/**
 * Returns the action flag for keyPress(char)
 * @link https://php.net/manual/en/function.ming-keypress.php
 * @param string $char
 * @return int What the function returns, first on success, then on failure. See
 * also the &amp;return.success; entity
 */
function ming_keypress($char) {}

/**
 * Use constant pool
 * @link https://php.net/manual/en/function.ming-useconstants.php
 * @param int $use <p>
 * Its description
 * </p>
 * @return void
 */
function ming_useconstants($use) {}

/**
 * Sets the SWF output compression
 * @link https://php.net/manual/en/function.ming-setswfcompression.php
 * @param int $level <p>
 * The new compression level. Should be a value between 1 and 9
 * inclusive.
 * </p>
 * @return void
 */
function ming_setswfcompression($level) {}

define('MING_NEW', 1);
define('MING_ZLIB', 1);
define('SWFBUTTON_HIT', 8);
define('SWFBUTTON_DOWN', 4);
define('SWFBUTTON_OVER', 2);
define('SWFBUTTON_UP', 1);
define('SWFBUTTON_MOUSEUPOUTSIDE', 64);
define('SWFBUTTON_DRAGOVER', 160);
define('SWFBUTTON_DRAGOUT', 272);
define('SWFBUTTON_MOUSEUP', 8);
define('SWFBUTTON_MOUSEDOWN', 4);
define('SWFBUTTON_MOUSEOUT', 2);
define('SWFBUTTON_MOUSEOVER', 1);
define('SWFFILL_RADIAL_GRADIENT', 18);
define('SWFFILL_LINEAR_GRADIENT', 16);
define('SWFFILL_TILED_BITMAP', 64);
define('SWFFILL_CLIPPED_BITMAP', 65);
define('SWFTEXTFIELD_HASLENGTH', 2);
define('SWFTEXTFIELD_NOEDIT', 8);
define('SWFTEXTFIELD_PASSWORD', 16);
define('SWFTEXTFIELD_MULTILINE', 32);
define('SWFTEXTFIELD_WORDWRAP', 64);
define('SWFTEXTFIELD_DRAWBOX', 2048);
define('SWFTEXTFIELD_NOSELECT', 4096);
define('SWFTEXTFIELD_HTML', 512);
define('SWFTEXTFIELD_USEFONT', 256);
define('SWFTEXTFIELD_AUTOSIZE', 16384);
define('SWFTEXTFIELD_ALIGN_LEFT', 0);
define('SWFTEXTFIELD_ALIGN_RIGHT', 1);
define('SWFTEXTFIELD_ALIGN_CENTER', 2);
define('SWFTEXTFIELD_ALIGN_JUSTIFY', 3);
define('SWFACTION_ONLOAD', 1);
define('SWFACTION_ENTERFRAME', 2);
define('SWFACTION_UNLOAD', 4);
define('SWFACTION_MOUSEMOVE', 8);
define('SWFACTION_MOUSEDOWN', 16);
define('SWFACTION_MOUSEUP', 32);
define('SWFACTION_KEYDOWN', 64);
define('SWFACTION_KEYUP', 128);
define('SWFACTION_DATA', 256);
define('SWF_SOUND_NOT_COMPRESSED', 0);
define('SWF_SOUND_ADPCM_COMPRESSED', 16);
define('SWF_SOUND_MP3_COMPRESSED', 32);
define('SWF_SOUND_NOT_COMPRESSED_LE', 48);
define('SWF_SOUND_NELLY_COMPRESSED', 96);
define('SWF_SOUND_5KHZ', 0);
define('SWF_SOUND_11KHZ', 4);
define('SWF_SOUND_22KHZ', 8);
define('SWF_SOUND_44KHZ', 12);
define('SWF_SOUND_8BITS', 0);
define('SWF_SOUND_16BITS', 2);
define('SWF_SOUND_MONO', 0);
define('SWF_SOUND_STEREO', 1);

// End of ming v.
