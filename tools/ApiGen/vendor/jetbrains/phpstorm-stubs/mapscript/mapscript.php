<?php

/**
 * Mapscript extension (version 7.0.*)
 * Parsed from documentation
 * Generated at 2017-08-24 16:06:54
 *
 * @see https://mapserver.org/mapscript/php/phpmapscript.html
 * @see https://raw.githubusercontent.com/mapserver/docs/branch-7-0/en/mapscript/php/phpmapscript.txt
 */

// constants
const MS_TRUE = 1;
const MS_FALSE = 0;
const MS_ON = 1;
const MS_OFF = 0;
const MS_YES = 1;
const MS_NO = 0;
const MS_INCHES = 0;
const MS_FEET = 1;
const MS_MILES = 2;
const MS_METERS = 3;
const MS_KILOMETERS = 4;
const MS_DD = 5;
const MS_PIXELS = 6;
const MS_NAUTICALMILES = 8;
const MS_LAYER_POINT = 0;
const MS_LAYER_LINE = 1;
const MS_LAYER_POLYGON = 2;
const MS_LAYER_RASTER = 3;
// (deprecated since 6.2)
const MS_LAYER_ANNOTATION = 4;
const MS_LAYER_QUERY = 5;
const MS_LAYER_CIRCLE = 6;
const MS_LAYER_TILEINDEX = 7;
const MS_LAYER_CHART = 8;
const MS_DEFAULT = 2;
const MS_EMBED = 3;
const MS_DELETE = 4;
const MS_GD_ALPHA = 1000;
const MS_UL = 101;
const MS_LR = 102;
const MS_UR = 103;
const MS_LL = 104;
const MS_CR = 105;
const MS_CL = 106;
const MS_UC = 107;
const MS_LC = 108;
const MS_CC = 109;
const MS_XY = 111;
const MS_AUTO = 110;
const MS_AUTO2 = 114;
const MS_FOLLOW = 112;
const MS_NONE = 113;
const MS_TINY = 0;
const MS_SMALL = 1;
const MS_MEDIUM = 2;
const MS_LARGE = 3;
const MS_GIANT = 4;
const MS_SHAPE_POINT = 0;
const MS_SHAPE_LINE = 1;
const MS_SHAPE_POLYGON = 2;
const MS_SHAPE_NULL = 3;
const MS_SHP_POINT = 1;
const MS_SHP_ARC = 3;
const MS_SHP_POLYGON = 5;
const MS_SHP_MULTIPOINT = 8;
const MS_SINGLE = 0;
const MS_MULTIPLE = 1;
const MS_NORMAL = 0;
const MS_HILITE = 1;
const MS_SELECTED = 2;
const MS_INLINE = 0;
const MS_SHAPEFILE = 1;
const MS_TILED_SHAPEFILE = 2;
const MS_SDE = 3;
const MS_OGR = 4;
const MS_TILED_OGR = 5;
const MS_POSTGIS = 6;
const MS_WMS = 7;
const MS_ORACLESPATIAL = 8;
const MS_WFS = 9;
const MS_GRATICULE = 10;
const MS_RASTER = 12;
const MS_PLUGIN = 13;
const MS_UNION = 14;
const MS_NOERR = 0;
const MS_IOERR = 1;
const MS_MEMERR = 2;
const MS_TYPEERR = 3;
const MS_SYMERR = 4;
const MS_REGEXERR = 5;
const MS_TTFERR = 6;
const MS_DBFERR = 7;
const MS_GDERR = 8;
const MS_IDENTERR = 9;
const MS_EOFERR = 10;
const MS_PROJERR = 11;
const MS_MISCERR = 12;
const MS_CGIERR = 13;
const MS_WEBERR = 14;
const MS_IMGERR = 15;
const MS_HASHERR = 16;
const MS_JOINERR = 17;
const MS_NOTFOUND = 18;
const MS_SHPERR = 19;
const MS_PARSEERR = 20;
const MS_SDEERR = 21;
const MS_OGRERR = 22;
const MS_QUERYERR = 23;
const MS_WMSERR = 24;
const MS_WMSCONNERR = 25;
const MS_ORACLESPATIALERR = 26;
const MS_WFSERR = 27;
const MS_WFSCONNERR = 28;
const MS_MAPCONTEXTERR = 29;
const MS_HTTPERR = 30;
const MS_WCSERR = 32;
const MS_SYMBOL_SIMPLE = 1000;
const MS_SYMBOL_VECTOR = 1001;
const MS_SYMBOL_ELLIPSE = 1002;
const MS_SYMBOL_PIXMAP = 1003;
const MS_SYMBOL_TRUETYPE = 1004;
const MS_IMAGEMODE_PC256 = 0;
const MS_IMAGEMODE_RGB = 1;
const MS_IMAGEMODE_RGBA = 2;
const MS_IMAGEMODE_INT16 = 3;
const MS_IMAGEMODE_FLOAT32 = 4;
const MS_IMAGEMODE_BYTE = 5;
const MS_IMAGEMODE_FEATURE = 6;
const MS_IMAGEMODE_NULL = 7;
const MS_STYLE_BINDING_SIZE = 0;
const MS_STYLE_BINDING_ANGLE = 2;
const MS_STYLE_BINDING_COLOR = 3;
const MS_STYLE_BINDING_OUTLINECOLOR = 4;
const MS_STYLE_BINDING_SYMBOL = 5;
const MS_STYLE_BINDING_WIDTH = 1;
const MS_LABEL_BINDING_SIZE = 0;
const MS_LABEL_BINDING_ANGLE = 1;
const MS_LABEL_BINDING_COLOR = 2;
const MS_LABEL_BINDING_OUTLINECOLOR = 3;
const MS_LABEL_BINDING_FONT = 4;
const MS_LABEL_BINDING_PRIORITY = 5;
const MS_LABEL_BINDING_POSITION = 6;
const MS_LABEL_BINDING_SHADOWSIZEX = 7;
const MS_LABEL_BINDING_SHADOWSIZEY = 8;
const MS_ALIGN_LEFT = 0;
const MS_ALIGN_CENTER = 1;
const MS_ALIGN_RIGHT = 2;
const MS_GET_REQUEST = 0;
const MS_POST_REQUEST = 1;

/**
 * Returns the MapServer version and options in a string.  This
 * string can be parsed to find out which modules were compiled in,
 * etc.
 *
 * @return string
 */
function ms_GetVersion() {}

/**
 * Returns the MapServer version number (x.y.z) as an integer
 * (x*10000 + y*100 + z). (New in v5.0) e.g. V5.4.3 would return
 * 50403.
 *
 * @return int
 */
function ms_GetVersionInt() {}

/**
 * Writes the current buffer to stdout.  The PHP header() function
 * should be used to set the documents's content-type prior to
 * calling the function.  Returns the number of bytes written if
 * output is sent to stdout.  See :ref:`mapscript_ows` for more info.
 *
 * @return int
 */
function ms_iogetStdoutBufferBytes() {}

/**
 * Fetch the current stdout buffer contents as a string.  This method
 * does not clear the buffer.
 *
 * @return void
 */
function ms_iogetstdoutbufferstring() {}

/**
 * Installs a mapserver IO handler directing future stdin reading
 * (ie. post request capture) to come from a buffer.
 *
 * @return void
 */
function ms_ioinstallstdinfrombuffer() {}

/**
 * Installs a mapserver IO handler directing future stdout output
 * to a memory buffer.
 *
 * @return void
 */
function ms_ioinstallstdouttobuffer() {}

/**
 * Resets the default stdin and stdout handlers in place of "buffer"
 * based handlers.
 *
 * @return void
 */
function ms_ioresethandlers() {}

/**
 * Strip the Content-type header off the stdout buffer if it has one,
 * and if a content type is found it is return. Otherwise return
 * false.
 *
 * @return string
 */
function ms_iostripstdoutbuffercontenttype() {}

/**
 * Strip all the Content-* headers off the stdout buffer if it has
 * some.
 *
 * @return void
 */
function ms_iostripstdoutbuffercontentheaders() {}

/**
 * Preparses a mapfile through the MapServer parser and return an
 * array with one item for each token from the mapfile.  Strings,
 * logical expressions, regex expressions and comments are returned
 * as individual tokens.
 *
 * @param string $map_file_name
 * @return array
 */
function ms_TokenizeMap($map_file_name) {}

/**
 * Returns a reference to the head of the list of errorObj.
 *
 * @return errorObj
 */
function ms_GetErrorObj() {}

/**
 * Clear the current error list.
 * Note that clearing the list invalidates any errorObj handles obtained
 * via the $error->next() method.
 *
 * @return void
 */
function ms_ResetErrorList() {}

/**
 * Class Objects can be returned by the `layerObj`_ class, or can be
 * created using:
 */
final class classObj
{
    /**
     * @var string
     */
    public $group;

    /**
     * @var string
     */
    public $keyimage;

    /**
     * Removed (6.2) - use addLabel, getLabel, ...
     *
     * @var labelObj
     */
    public $label;

    /**
     * @var float
     */
    public $maxscaledenom;

    /**
     * @var hashTableObj
     */
    public $metadata;

    /**
     * @var float
     */
    public $minscaledenom;

    /**
     * @var string
     */
    public $name;

    /**
     * read-only (since 6.2)
     *
     * @var int
     */
    public $numlabels;

    /**
     * read-only
     *
     * @var int
     */
    public $numstyles;

    /**
     * MS_ON, MS_OFF or MS_DELETE
     *
     * @var int
     */
    public $status;

    /**
     * @var string
     */
    public $template;

    /**
     * @var string
     */
    public $title;

    /**
     * @var int
     */
    public $type;

    /**
     * The second argument class is optional. If given, the new class
     * created will be a copy of this class.
     *
     * @param layerObj $layer
     * @param classObj $class
     */
    final public function __construct(layerObj $layer, classObj $class) {}

    /**
     * Old style constructor
     *
     * @param layerObj $layer
     * @param classObj $class
     * @return classObj
     */
    final public function ms_newClassObj(layerObj $layer, classObj $class) {}

    /**
     * Add a labelObj to the classObj and return its index in the labels
     * array.
     * .. versionadded:: 6.2
     *
     * @param labelObj $label
     * @return int
     */
    final public function addLabel(labelObj $label) {}

    /**
     * Saves the object to a string.  Provides the inverse option for
     * updateFromString.
     *
     * @return string
     */
    final public function convertToString() {}

    /**
     * Draw the legend icon and return a new imageObj.
     *
     * @param int $width
     * @param int $height
     * @return imageObj
     */
    final public function createLegendIcon($width, $height) {}

    /**
     * Delete the style specified by the style index. If there are any
     * style that follow the deleted style, their index will decrease by 1.
     *
     * @param int $index
     * @return int
     */
    final public function deletestyle($index) {}

    /**
     * Draw the legend icon on im object at dstX, dstY.
     * Returns MS_SUCCESS/MS_FAILURE.
     *
     * @param int $width
     * @param int $height
     * @param imageObj $im
     * @param int $dstX
     * @param int $dstY
     * @return int
     */
    final public function drawLegendIcon($width, $height, imageObj $im, $dstX, $dstY) {}

    /**
     * Free the object properties and break the internal references.
     * Note that you have to unset the php variable to free totally the
     * resources.
     *
     * @return void
     */
    final public function free() {}

    /**
     * Returns the :ref:`expression <expressions>` string for the class
     * object.
     *
     * @return string
     */
    final public function getExpressionString() {}

    /**
     * Return a reference to the labelObj at *index* in the labels array.
     * See the labelObj_ section for more details on multiple class
     * labels.
     * .. versionadded:: 6.2
     *
     * @param int $index
     * @return labelObj
     */
    final public function getLabel($index) {}

    /**
     * Fetch class metadata entry by name.  Returns "" if no entry
     * matches the name.  Note that the search is case sensitive.
     * .. note::
     * getMetaData's query is case sensitive.
     *
     * @param string $name
     * @return int
     */
    final public function getMetaData($name) {}

    /**
     * Return the style object using an index. index >= 0 &&
     * index < class->numstyles.
     *
     * @param int $index
     * @return styleObj
     */
    final public function getStyle($index) {}

    /**
     * Returns the text string for the class object.
     *
     * @return string
     */
    final public function getTextString() {}

    /**
     * The style specified by the style index will be moved down into
     * the array of classes. Returns MS_SUCCESS or MS_FAILURE.
     * ex class->movestyledown(0) will have the effect of moving style 0
     * up to position 1, and the style at position 1 will be moved
     * to position 0.
     *
     * @param int $index
     * @return int
     */
    final public function movestyledown($index) {}

    /**
     * The style specified by the style index will be moved up into
     * the array of classes. Returns MS_SUCCESS or MS_FAILURE.
     * ex class->movestyleup(1) will have the effect of moving style 1
     * up to position 0, and the style at position 0 will be moved
     * to position 1.
     *
     * @param int $index
     * @return int
     */
    final public function movestyleup($index) {}

    /**
     * Remove the labelObj at *index* from the labels array and return a
     * reference to the labelObj.  numlabels is decremented, and the
     * array is updated.
     * .. versionadded:: 6.2
     *
     * @param int $index
     * @return labelObj
     */
    final public function removeLabel($index) {}

    /**
     * Remove a metadata entry for the class.  Returns MS_SUCCESS/MS_FAILURE.
     *
     * @param string $name
     * @return int
     */
    final public function removeMetaData($name) {}

    /**
     * Set object property to a new value.
     *
     * @param string $property_name
     * @param  $new_value
     * @return int
     */
    final public function set($property_name, $new_value) {}

    /**
     * Set the :ref:`expression <expressions>` string for the class
     * object.
     *
     * @param string $expression
     * @return int
     */
    final public function setExpression($expression) {}

    /**
     * Set a metadata entry for the class.  Returns MS_SUCCESS/MS_FAILURE.
     *
     * @param string $name
     * @param string $value
     * @return int
     */
    final public function setMetaData($name, $value) {}

    /**
     * Set the text string for the class object.
     *
     * @param string $text
     * @return int
     */
    final public function settext($text) {}

    /**
     * Update a class from a string snippet. Returns MS_SUCCESS/MS_FAILURE.
     * .. code-block:: php
     * set the color
     * $oClass->updateFromString('CLASS STYLE COLOR 255 0 255 END END');
     *
     * @param string $snippet
     * @return int
     */
    final public function updateFromString($snippet) {}
}

/**
 * Instance of clusterObj is always embedded inside the `layerObj`_.
 */
final class clusterObj
{
    /**
     * @var float
     */
    public $buffer;

    /**
     * @var float
     */
    public $maxdistance;

    /**
     * @var string
     */
    public $region;

    /**
     * Saves the object to a string.  Provides the inverse option for
     * updateFromString.
     *
     * @return string
     */
    final public function convertToString() {}

    /**
     * Returns the :ref:`expression <expressions>` for this cluster
     * filter or NULL on error.
     *
     * @return string
     */
    final public function getFilterString() {}

    /**
     * Returns the :ref:`expression <expressions>` for this cluster group
     * or NULL on error.
     *
     * @return string
     */
    final public function getGroupString() {}

    /**
     * Set layer filter :ref:`expression <expressions>`.
     *
     * @param string $expression
     * @return int
     */
    final public function setFilter($expression) {}

    /**
     * Set layer group :ref:`expression <expressions>`.
     *
     * @param string $expression
     * @return int
     */
    final public function setGroup($expression) {}
}

/**
 * Instances of colorObj are always embedded inside other classes.
 */
final class colorObj
{
    /**
     * @var int
     */
    public $red;

    /**
     * @var int
     */
    public $green;

    /**
     * @var int
     */
    public $blue;

    /**
     * @var int
     */
    public $alpha;

    /**
     * Get the color as a hex string "#rrggbb" or (if alpha is not 255)
     * "#rrggbbaa".
     *
     * @return string
     */
    final public function toHex() {}

    /**
     * Set red, green, blue and alpha values. The hex string should have the form
     * "#rrggbb" (alpha will be set to 255) or "#rrggbbaa". Returns MS_SUCCESS.
     *
     * @param string $hex
     * @return int
     */
    final public function setHex($hex) {}
}

final class errorObj
{
    /**
     * //See error code constants above
     *
     * @var int
     */
    public $code;

    /**
     * @var string
     */
    public $message;

    /**
     * @var string
     */
    public $routine;
}

/**
 * The grid is always embedded inside a layer object defined as
 * a grid (layer->connectiontype = MS_GRATICULE)
 * (for more docs : https://github.com/mapserver/mapserver/wiki/MapServerGrid)
 * A layer can become a grid layer by adding a grid object to it using :
 * ms_newGridObj(layerObj layer)
 * $oLayer = ms_newlayerobj($oMap);
 * $oLayer->set("name", "GRID");
 * ms_newgridobj($oLayer);
 * $oLayer->grid->set("labelformat", "DDMMSS");
 */
final class gridObj
{
    /**
     * @var string
     */
    public $labelformat;

    /**
     * @var float
     */
    public $maxacrs;

    /**
     * @var float
     */
    public $maxinterval;

    /**
     * @var float
     */
    public $maxsubdivide;

    /**
     * @var float
     */
    public $minarcs;

    /**
     * @var float
     */
    public $mininterval;

    /**
     * @var float
     */
    public $minsubdivide;

    /**
     * Set object property to a new value.
     *
     * @param string $property_name
     * @param  $new_value
     * @return int
     */
    final public function set($property_name, $new_value) {}
}

/**
 * Instance of hashTableObj is always embedded inside the `classObj`_,
 * `layerObj`_, `mapObj`_ and `webObj`_. It is uses a read only.
 * $hashTable = $oLayer->metadata;
 * $key = null;
 * while ($key = $hashTable->nextkey($key))
 * echo "Key: ".$key." value: ".$hashTable->get($key)."<br/>";
 */
final class hashTableObj
{
    /**
     * Clear all items in the hashTable (To NULL).
     *
     * @return void
     */
    final public function clear() {}

    /**
     * Fetch class metadata entry by name.  Returns "" if no entry
     * matches the name.  Note that the search is case sensitive.
     *
     * @param string $key
     * @return string
     */
    final public function get($key) {}

    /**
     * Return the next key or first key if previousKey = NULL.
     * Return NULL if no item is in the hashTable or end of hashTable is
     * reached
     *
     * @param string $previousKey
     * @return string
     */
    final public function nextkey($previousKey) {}

    /**
     * Remove a metadata entry in the hashTable.  Returns MS_SUCCESS/MS_FAILURE.
     *
     * @param string $key
     * @return int
     */
    final public function remove($key) {}

    /**
     * Set a metadata entry in the hashTable. Returns MS_SUCCESS/MS_FAILURE.
     *
     * @param string $key
     * @param string $value
     * @return int
     */
    final public function set($key, $value) {}
}

/**
 * Instances of imageObj are always created by the `mapObj`_ class methods.
 */
final class imageObj
{
    /**
     * read-only
     *
     * @var int
     */
    public $width;

    /**
     * read-only
     *
     * @var int
     */
    public $height;

    /**
     * read-only
     *
     * @var int
     */
    public $resolution;

    /**
     * read-only
     *
     * @var int
     */
    public $resolutionfactor;

    /**
     * @var string
     */
    public $imagepath;

    /**
     * @var string
     */
    public $imageurl;

    /**
     * Copy srcImg on top of the current imageObj.
     * transparentColorHex is the color (in 0xrrggbb format) from srcImg
     * that should be considered transparent (i.e. those pixels won't
     * be copied).  Pass -1 if you don't want any transparent color.
     * If optional dstx,dsty are provided then it defines the position
     * where the image should be copied (dstx,dsty = top-left corner
     * position).
     * The optional angle is a value between 0 and 360 degrees to rotate
     * the source image counterclockwise.  Note that if an angle is specified
     * (even if its value is zero) then the dstx and dsty coordinates
     * specify the CENTER of the destination area.
     * Note: this function works only with 8 bits GD images (PNG or GIF).
     *
     * @param imageObj $srcImg
     * @param int $transparentColorHex
     * @param int $dstX
     * @param int $dstY
     * @param int $angle
     * @return void
     */
    final public function pasteImage(imageObj $srcImg, $transparentColorHex, $dstX, $dstY, $angle) {}

    /**
     * Writes image object to specified filename.
     * Passing no filename or an empty filename sends output to stdout.  In
     * this case, the PHP header() function should be used to set the
     * document's content-type prior to calling saveImage().  The output
     * format is the one that is currently selected in the map file.  The
     * second argument oMap is not manadatory. It is usful when saving to
     * formats like GTIFF that needs georeference information contained in
     * the map file. On success, it returns either MS_SUCCESS if writing to an
     * external file, or the number of bytes written if output is sent to
     * stdout.
     *
     * @param string $filename
     * @param mapObj $oMap
     * @return int
     */
    final public function saveImage($filename, mapObj $oMap) {}

    /**
     * Writes image to temp directory.  Returns image URL.
     * The output format is the one that is currently selected in the
     * map file.
     *
     * @return string
     */
    final public function saveWebImage() {}
}

final class labelcacheMemberObj
{
    /**
     * read-only
     *
     * @var int
     */
    public $classindex;

    /**
     * read-only
     *
     * @var int
     */
    public $featuresize;

    /**
     * read-only
     *
     * @var int
     */
    public $layerindex;

    /**
     * read-only
     *
     * @var int
     */
    public $markerid;

    /**
     * read-only
     *
     * @var int
     */
    public $numstyles;

    /**
     * read-only
     *
     * @var int
     */
    public $shapeindex;

    /**
     * read-only
     *
     * @var int
     */
    public $status;

    /**
     * read-only
     *
     * @var string
     */
    public $text;

    /**
     * read-only
     *
     * @var int
     */
    public $tileindex;
}

final class labelcacheObj
{
    /**
     * Free the label cache. Always returns MS_SUCCESS.
     * Ex : map->labelcache->freeCache();
     *
     * @return bool
     */
    final public function freeCache() {}
}

/**
 * labelObj are always embedded inside other classes.
 */
final class labelObj
{
    /**
     * @var int
     */
    public $align;

    /**
     * @var float
     */
    public $angle;

    /**
     * @var int
     */
    public $anglemode;

    /**
     * @var int
     */
    public $antialias;

    /**
     * @var int
     */
    public $autominfeaturesize;

    /**
     * (deprecated since 6.0)
     *
     * @var colorObj
     */
    public $backgroundcolor;

    /**
     * (deprecated since 6.0)
     *
     * @var colorObj
     */
    public $backgroundshadowcolor;

    /**
     * (deprecated since 6.0)
     *
     * @var int
     */
    public $backgroundshadowsizex;

    /**
     * (deprecated since 6.0)
     *
     * @var int
     */
    public $backgroundshadowsizey;

    /**
     * @var int
     */
    public $buffer;

    /**
     * @var colorObj
     */
    public $color;

    /**
     * @var string
     */
    public $encoding;

    /**
     * @var string
     */
    public $font;

    /**
     * @var int
     */
    public $force;

    /**
     * @var int
     */
    public $maxlength;

    /**
     * @var int
     */
    public $maxsize;

    /**
     * @var int
     */
    public $mindistance;

    /**
     * @var int
     */
    public $minfeaturesize;

    /**
     * @var int
     */
    public $minlength;

    /**
     * @var int
     */
    public $minsize;

    /**
     * @var int
     */
    public $numstyles;

    /**
     * @var int
     */
    public $offsetx;

    /**
     * @var int
     */
    public $offsety;

    /**
     * @var colorObj
     */
    public $outlinecolor;

    /**
     * @var int
     */
    public $outlinewidth;

    /**
     * @var int
     */
    public $partials;

    /**
     * @var int
     */
    public $position;

    /**
     * @var int
     */
    public $priority;

    /**
     * @var int
     */
    public $repeatdistance;

    /**
     * @var colorObj
     */
    public $shadowcolor;

    /**
     * @var int
     */
    public $shadowsizex;

    /**
     * @var int
     */
    public $shadowsizey;

    /**
     * @var int
     */
    public $size;

    /**
     * @var int
     */
    public $wrap;

    final public function __construct() {}

    /**
     * Saves the object to a string.  Provides the inverse option for
     * updateFromString.
     *
     * @return string
     */
    final public function convertToString() {}

    /**
     * Delete the style specified by the style index. If there are any
     * style that follow the deleted style, their index will decrease by 1.
     *
     * @param int $index
     * @return int
     */
    final public function deleteStyle($index) {}

    /**
     * Free the object properties and break the internal references.
     * Note that you have to unset the php variable to free totally the
     * resources.
     *
     * @return void
     */
    final public function free() {}

    /**
     * Get the attribute binding for a specified label property. Returns
     * NULL if there is no binding for this property.
     * Example:
     * .. code-block:: php
     * $oLabel->setbinding(MS_LABEL_BINDING_COLOR, "FIELD_NAME_COLOR");
     * echo $oLabel->getbinding(MS_LABEL_BINDING_COLOR); // FIELD_NAME_COLOR
     *
     * @param mixed $labelbinding
     * @return string
     */
    final public function getBinding($labelbinding) {}

    /**
     * Returns the label expression string.
     *
     * @return string
     */
    final public function getExpressionString() {}

    /**
     * Return the style object using an index. index >= 0 &&
     * index < label->numstyles.
     *
     * @param int $index
     * @return styleObj
     */
    final public function getStyle($index) {}

    /**
     * Returns the label text string.
     *
     * @return string
     */
    final public function getTextString() {}

    /**
     * The style specified by the style index will be moved down into
     * the array of classes. Returns MS_SUCCESS or MS_FAILURE.
     * ex label->movestyledown(0) will have the effect of moving style 0
     * up to position 1, and the style at position 1 will be moved
     * to position 0.
     *
     * @param int $index
     * @return int
     */
    final public function moveStyleDown($index) {}

    /**
     * The style specified by the style index will be moved up into
     * the array of classes. Returns MS_SUCCESS or MS_FAILURE.
     * ex label->movestyleup(1) will have the effect of moving style 1
     * up to position 0, and the style at position 0 will be moved
     * to position 1.
     *
     * @param int $index
     * @return int
     */
    final public function moveStyleUp($index) {}

    /**
     * Remove the attribute binding for a specfiled style property.
     * Example:
     * .. code-block:: php
     * $oStyle->removebinding(MS_LABEL_BINDING_COLOR);
     *
     * @param mixed $labelbinding
     * @return int
     */
    final public function removeBinding($labelbinding) {}

    /**
     * Set object property to a new value.
     *
     * @param string $property_name
     * @param  $new_value
     * @return int
     */
    final public function set($property_name, $new_value) {}

    /**
     * Set the attribute binding for a specified label property.
     * Example:
     * .. code-block:: php
     * $oLabel->setbinding(MS_LABEL_BINDING_COLOR, "FIELD_NAME_COLOR");
     * This would bind the color parameter with the data (ie will extract
     * the value of the color from the field called "FIELD_NAME_COLOR"
     *
     * @param mixed $labelbinding
     * @param string $value
     * @return int
     */
    final public function setBinding($labelbinding, $value) {}

    /**
     * Set the label expression.
     *
     * @param string $expression
     * @return int
     */
    final public function setExpression($expression) {}

    /**
     * Set the label text.
     *
     * @param string $text
     * @return int
     */
    final public function setText($text) {}

    /**
     * Update a label from a string snippet. Returns MS_SUCCESS/MS_FAILURE.
     *
     * @param string $snippet
     * @return int
     */
    final public function updateFromString($snippet) {}
}

/**
 * Layer Objects can be returned by the `mapObj`_ class, or can be
 * created using:
 * A second optional argument can be given to ms_newLayerObj() to create
 * the new layer as a copy of an existing layer. If a layer is given as
 * argument then all members of a this layer will be copied in the new
 * layer created.
 */
final class layerObj
{
    /**
     * @var int
     */
    public $annotate;

    /**
     * @var hashTableObj
     */
    public $bindvals;

    /**
     * @var string
     */
    public $classgroup;

    /**
     * @var string
     */
    public $classitem;

    /**
     * @var clusterObj
     */
    public $cluster;

    /**
     * @var string
     */
    public $connection;

    /**
     * read-only, use setConnectionType() to set it
     *
     * @var int
     */
    public $connectiontype;

    /**
     * @var string
     */
    public $data;

    /**
     * @var int
     */
    public $debug;

    /**
     * deprecated since 6.0
     *
     * @var int
     */
    public $dump;

    /**
     * @var string
     */
    public $filteritem;

    /**
     * @var string
     */
    public $footer;

    /**
     * only available on a layer defined as grid (MS_GRATICULE)
     *
     * @var gridObj
     */
    public $grid;

    /**
     * @var string
     */
    public $group;

    /**
     * @var string
     */
    public $header;

    /**
     * read-only
     *
     * @var int
     */
    public $index;

    /**
     * @var int
     */
    public $labelcache;

    /**
     * @var string
     */
    public $labelitem;

    /**
     * @var float
     */
    public $labelmaxscaledenom;

    /**
     * @var float
     */
    public $labelminscaledenom;

    /**
     * @var string
     */
    public $labelrequires;

    /**
     * @var string
     */
    public $mask;

    /**
     * @var int
     */
    public $maxfeatures;

    /**
     * @var float
     */
    public $maxscaledenom;

    /**
     * @var hashTableObj
     */
    public $metadata;

    /**
     * @var float
     */
    public $minscaledenom;

    /**
     * @var string
     */
    public $name;

    /**
     * @var int
     */
    public $num_processing;

    /**
     * read-only
     *
     * @var int
     */
    public $numclasses;

    /**
     * @var colorObj
     */
    public $offsite;

    /**
     * @var int
     */
    public $opacity;

    /**
     * @var projectionObj
     */
    public $projection;

    /**
     * @var int
     */
    public $postlabelcache;

    /**
     * @var string
     */
    public $requires;

    /**
     * @var int
     */
    public $sizeunits;

    /**
     * @var int
     */
    public $startindex;

    /**
     * MS_ON, MS_OFF, MS_DEFAULT or MS_DELETE
     *
     * @var int
     */
    public $status;

    /**
     * @var string
     */
    public $styleitem;

    /**
     * @var float
     */
    public $symbolscaledenom;

    /**
     * @var string
     */
    public $template;

    /**
     * @var string
     */
    public $tileindex;

    /**
     * @var string
     */
    public $tileitem;

    /**
     * @var float
     */
    public $tolerance;

    /**
     * @var int
     */
    public $toleranceunits;

    /**
     * @var int
     */
    public $transform;

    /**
     * @var int
     */
    public $type;

    /**
     * Old style constructor
     *
     * @param mapObj $map
     * @param layerObj $layer
     * @return layerObj
     */
    final public function ms_newLayerObj(mapObj $map, layerObj $layer) {}

    /**
     * Add a new feature in a layer. Returns MS_SUCCESS or MS_FAILURE on
     * error.
     *
     * @param shapeObj $shape
     * @return int
     */
    final public function addFeature(shapeObj $shape) {}

    /**
     * Apply the :ref:`SLD <sld>` document to the layer object.
     * The matching between the sld document and the layer will be done
     * using the layer's name.
     * If a namedlayer argument is passed (argument is optional),
     * the NamedLayer in the sld that matchs it will be used to style
     * the layer.
     * See :ref:`SLD HowTo <sld>` for more information on the SLD support.
     *
     * @param string $sldxml
     * @param string $namedlayer
     * @return int
     */
    final public function applySLD($sldxml, $namedlayer) {}

    /**
     * Apply the :ref:`SLD <sld>` document pointed by the URL to the
     * layer object. The matching between the sld document and the layer
     * will be done using the layer's name.  If a namedlayer argument is
     * passed (argument is optional), the NamedLayer in the sld that
     * matchs it will be used to style the layer.  See :ref:`SLD HowTo <sld>`
     * for more information on the SLD support.
     *
     * @param string $sldurl
     * @param string $namedlayer
     * @return int
     */
    final public function applySLDURL($sldurl, $namedlayer) {}

    /**
     * Clears all the processing strings.
     *
     * @return void
     */
    final public function clearProcessing() {}

    /**
     * Close layer previously opened with open().
     *
     * @return void
     */
    final public function close() {}

    /**
     * Saves the object to a string.  Provides the inverse option for
     * updateFromString.
     *
     * @return string
     */
    final public function convertToString() {}

    /**
     * Draw a single layer, add labels to cache if required.
     * Returns MS_SUCCESS or MS_FAILURE on error.
     *
     * @param imageObj $image
     * @return int
     */
    final public function draw(imageObj $image) {}

    /**
     * Draw query map for a single layer.
     * string   executeWFSGetfeature()
     * Executes a GetFeature request on a WFS layer and returns the
     * name of the temporary GML file created. Returns an empty
     * string on error.
     *
     * @param imageObj $image
     * @return int
     */
    final public function drawQuery(imageObj $image) {}

    /**
     * Free the object properties and break the internal references.
     * Note that you have to unset the php variable to free totally the
     * resources.
     *
     * @return void
     */
    final public function free() {}

    /**
     * Returns an SLD XML string based on all the classes found in the
     * layer (the layer must have `STATUS` `on`).
     *
     * @return string
     */
    final public function generateSLD() {}

    /**
     * Returns a classObj from the layer given an index value (0=first class)
     *
     * @param int $classIndex
     * @return classObj
     */
    final public function getClass($classIndex) {}

    /**
     * Get the class index of a shape for a given scale. Returns -1 if no
     * class matches. classgroup is an array of class ids to check
     * (Optional). numclasses is the number of classes that the classgroup
     * array contains. By default, all the layer classes will be checked.
     *
     * @param  $shape
     * @param  $classgroup
     * @param  $numclasses
     * @return int
     */
    final public function getClassIndex($shape, $classgroup, $numclasses) {}

    /**
     * Returns the layer's data extents or NULL on error.
     * If the layer's EXTENT member is set then this value is used,
     * otherwise this call opens/closes the layer to read the
     * extents. This is quick on shapefiles, but can be
     * an expensive operation on some file formats or data sources.
     * This function is safe to use on both opened or closed layers: it
     * is not necessary to call open()/close() before/after calling it.
     *
     * @return rectObj
     */
    final public function getExtent() {}

    /**
     * Returns the :ref:`expression <expressions>` for this layer or NULL
     * on error.
     *
     * @return string|null
     */
    final public function getFilterString() {}

    /**
     * Returns an array containing the grid intersection coordinates. If
     * there are no coordinates, it returns an empty array.
     *
     * @return array
     */
    final public function getGridIntersectionCoordinates() {}

    /**
     * Returns an array containing the items. Must call open function first.
     * If there are no items, it returns an empty array.
     *
     * @return array
     */
    final public function getItems() {}

    /**
     * Fetch layer metadata entry by name.  Returns "" if no entry
     * matches the name.  Note that the search is case sensitive.
     * .. note::
     * getMetaData's query is case sensitive.
     *
     * @param string $name
     * @return int
     */
    final public function getMetaData($name) {}

    /**
     * Returns the number of results in the last query.
     *
     * @return int
     */
    final public function getNumResults() {}

    /**
     * Returns an array containing the processing strings.
     * If there are no processing strings, it returns an empty array.
     *
     * @return array
     */
    final public function getProcessing() {}

    /**
     * Returns a string representation of the :ref:`projection <projection>`.
     * Returns NULL on error or if no projection is set.
     *
     * @return string
     */
    final public function getProjection() {}

    /**
     * Returns a resultObj by index from a layer object with
     * index in the range 0 to numresults-1.
     * Returns a valid object or FALSE(0) if index is invalid.
     *
     * @param int $index
     * @return resultObj
     */
    final public function getResult($index) {}

    /**
     * Returns the bounding box of the latest result.
     *
     * @return rectObj
     */
    final public function getResultsBounds() {}

    /**
     * If the resultObj passed has a valid resultindex, retrieve shapeObj from
     * a layer's resultset. (You get it from the resultObj returned by
     * getResult() for instance). Otherwise, it will do a single query on
     * the layer to fetch the shapeindex
     * .. code-block:: php
     * $map = new mapObj("gmap75.map");
     * $l = $map->getLayerByName("popplace");
     * $l->queryByRect($map->extent);
     * for ($i = 0; $i < $l->getNumResults(); $i++) {
     *     $s = $l->getShape($l->getResult($i));
     *     echo $s->getValue($l,"Name");
     *     echo "\n";
     * }
     *
     * @param resultObj $result
     * @return shapeObj
     */
    final public function getShape(resultObj $result) {}

    /**
     * Returns a WMS GetFeatureInfo URL (works only for WMS layers)
     * clickX, clickY is the location of to query in pixel coordinates
     * with (0,0) at the top left of the image.
     * featureCount is the number of results to return.
     * infoFormat is the format the format in which the result should be
     * requested.  Depends on remote server's capabilities.  MapServer
     * WMS servers support only "MIME" (and should support "GML.1" soon).
     * Returns "" and outputs a warning if layer is not a WMS layer
     * or if it is not queriable.
     *
     * @param int $clickX
     * @param int $clickY
     * @param int $featureCount
     * @param string $infoFormat
     * @return string
     */
    final public function getWMSFeatureInfoURL($clickX, $clickY, $featureCount, $infoFormat) {}

    /**
     * Returns MS_TRUE/MS_FALSE depending on whether the layer is
     * currently visible in the map (i.e. turned on, in scale, etc.).
     *
     * @return bool
     */
    final public function isVisible() {}

    /**
     * The class specified by the class index will be moved down into
     * the array of layers. Returns MS_SUCCESS or MS_FAILURE.
     * ex layer->moveclassdown(0) will have the effect of moving class 0
     * up to position 1, and the class at position 1 will be moved
     * to position 0.
     *
     * @param int $index
     * @return int
     */
    final public function moveclassdown($index) {}

    /**
     * The class specified by the class index will be moved up into
     * the array of layers. Returns MS_SUCCESS or MS_FAILURE.
     * ex layer->moveclassup(1) will have the effect of moving class 1
     * up to position 0, and the class at position 0 will be moved
     * to position 1.
     *
     * @param int $index
     * @return int
     */
    final public function moveclassup($index) {}

    /**
     * Open the layer for use with getShape().
     * Returns MS_SUCCESS/MS_FAILURE.
     *
     * @return int
     */
    final public function open() {}

    /**
     * Called after msWhichShapes has been called to actually retrieve
     * shapes within a given area. Returns a shape object or NULL on
     * error.
     * .. code-block:: php
     * $map = ms_newmapobj("d:/msapps/gmap-ms40/htdocs/gmap75.map");
     * $layer = $map->getLayerByName('road');
     * $status = $layer->open();
     * $status = $layer->whichShapes($map->extent);
     * while ($shape = $layer->nextShape())
     * {
     * echo $shape->index ."<br>\n";
     * }
     * $layer->close();
     *
     * @return shapeObj
     */
    final public function nextShape() {}

    /**
     * Query layer for shapes that intersect current map extents.  qitem
     * is the item (attribute) on which the query is performed, and
     * qstring is the expression to match.  The query is performed on all
     * the shapes that are part of a :ref:`CLASS` that contains a
     * :ref:`TEMPLATE <template>` value or that match any class in a
     * layer that contains a :ref:`LAYER` :ref:`TEMPLATE <template>`
     * value.  Note that the layer's FILTER/FILTERITEM are ignored by
     * this function.  Mode is MS_SINGLE or MS_MULTIPLE depending on
     * number of results you want.  Returns MS_SUCCESS if shapes were
     * found or MS_FAILURE if nothing was found or if some other error
     * happened (note that the error message in case nothing was found
     * can be avoided in PHP using the '@' control operator).
     *
     * @param string $qitem
     * @param string $qstring
     * @param int $mode
     * @return int
     */
    final public function queryByAttributes($qitem, $qstring, $mode) {}

    /**
     * Perform a query set based on a previous set of results from
     * another layer. At present the results MUST be based on a polygon
     * layer.
     * Returns MS_SUCCESS if shapes were found or MS_FAILURE if nothing
     * was found or if some other error happened (note that the error
     * message in case nothing was found can be avoided in PHP using
     * the '@' control operator).
     *
     * @param int $slayer
     * @return int
     */
    final public function queryByFeatures($slayer) {}

    /**
     * Query layer at point location specified in georeferenced map
     * coordinates (i.e. not pixels).
     * The query is performed on all the shapes that are part of a CLASS
     * that contains a TEMPLATE value or that match any class in a
     * layer that contains a LAYER TEMPLATE value.
     * Mode is MS_SINGLE or MS_MULTIPLE depending on number of results
     * you want.
     * Passing buffer -1 defaults to tolerances set in the map file
     * (in pixels) but you can use a constant buffer (specified in
     * ground units) instead.
     * Returns MS_SUCCESS if shapes were found or MS_FAILURE if nothing
     * was found or if some other error happened (note that the error
     * message in case nothing was found can be avoided in PHP using
     * the '@' control operator).
     *
     * @param pointObj $point
     * @param int $mode
     * @param float $buffer
     * @return int
     */
    final public function queryByPoint(pointObj $point, $mode, $buffer) {}

    /**
     * Query layer using a rectangle specified in georeferenced map
     * coordinates (i.e. not pixels).
     * The query is performed on all the shapes that are part of a CLASS
     * that contains a TEMPLATE value or that match any class in a
     * layer that contains a LAYER TEMPLATE value.
     * Returns MS_SUCCESS if shapes were found or MS_FAILURE if nothing
     * was found or if some other error happened (note that the error
     * message in case nothing was found can be avoided in PHP using
     * the '@' control operator).
     *
     * @param rectObj $rect
     * @return int
     */
    final public function queryByRect(rectObj $rect) {}

    /**
     * Query layer based on a single shape, the shape has to be a polygon
     * at this point.
     * Returns MS_SUCCESS if shapes were found or MS_FAILURE if nothing
     * was found or if some other error happened (note that the error
     * message in case nothing was found can be avoided in PHP using
     * the '@' control operator).
     *
     * @param shapeObj $shape
     * @return int
     */
    final public function queryByShape(shapeObj $shape) {}

    /**
     * Removes the class indicated and returns a copy, or NULL in the case
     * of a failure.  Note that subsequent classes will be renumbered by
     * this operation. The numclasses field contains the number of classes
     * available.
     *
     * @param int $index
     * @return classObj|null
     */
    final public function removeClass($index) {}

    /**
     * Remove a metadata entry for the layer.  Returns MS_SUCCESS/MS_FAILURE.
     *
     * @param string $name
     * @return int
     */
    final public function removeMetaData($name) {}

    /**
     * Set object property to a new value.
     *
     * @param string $property_name
     * @param  $new_value
     * @return int
     */
    final public function set($property_name, $new_value) {}

    /**
     * Changes the connectiontype of the layer and recreates the vtable
     * according to the new connection type. This method should be used
     * instead of setting the connectiontype parameter directly.
     * In the case when the layer.connectiontype = MS_PLUGIN the plugin_library
     * parameter should also be specified so as to select the library to
     * load by MapServer. For the other connection types this parameter
     * is not used.
     *
     * @param int $connectiontype
     * @param string $plugin_library
     * @return int
     */
    final public function setConnectionType($connectiontype, $plugin_library) {}

    /**
     * Set layer filter :ref:`expression <expressions>`.
     *
     * @param string $expression
     * @return int
     */
    final public function setFilter($expression) {}

    /**
     * Set a metadata entry for the layer.  Returns MS_SUCCESS/MS_FAILURE.
     * int setProcessing(string)
     * Add the string to the processing string list for the layer.
     * The layer->num_processing is incremented by 1.
     * Returns MS_SUCCESS or MS_FAILURE on error.
     * .. code-block:: php
     * $oLayer->setprocessing("SCALE_1=AUTO");
     * $oLayer->setprocessing("SCALE_2=AUTO");
     *
     * @param string $name
     * @param string $value
     * @return int
     */
    final public function setMetaData($name, $value) {}

    /**
     * Set layer :ref:`projection <projection>` and coordinate system.
     * Parameters are given as a single string of comma-delimited PROJ.4
     * parameters. Returns MS_SUCCESS or MS_FAILURE on error.
     *
     * @param string $proj_params
     * @return int
     */
    final public function setProjection($proj_params) {}

    /**
     * Same as setProjection(), but takes an OGC WKT projection
     * definition string as input.
     * .. note::
     * setWKTProjection requires GDAL support
     *
     * @param string $proj_params
     * @return int
     */
    final public function setWKTProjection($proj_params) {}

    /**
     * Update a layer from a string snippet. Returns MS_SUCCESS/MS_FAILURE.
     * .. code-block:: php
     * modify the name
     * $oLayer->updateFromString('LAYER NAME land_fn2 END');
     * add a new class
     * $oLayer->updateFromString('LAYER CLASS STYLE COLOR 255 255 0 END END END');
     * int whichshapes(rectobj)
     * Performs a spatial, and optionally an attribute based feature
     * search.  The function basically prepares things so that candidate
     * features can be accessed by query or drawing functions (eg using
     * nextshape function).  Returns MS_SUCCESS, MS_FAILURE or MS_DONE.
     * MS_DONE is returned if the layer extent does not overlap the
     * rectObj.
     *
     * @param string $snippet
     * @return int
     */
    final public function updateFromString($snippet) {}
}

/**
 * Instances of legendObj are always are always embedded inside the `mapObj`_.
 */
final class legendObj
{
    /**
     * @var int
     */
    public $height;

    /**
     * @var colorObj
     */
    public $imagecolor;

    /**
     * @var int
     */
    public $keysizex;

    /**
     * @var int
     */
    public $keysizey;

    /**
     * @var int
     */
    public $keyspacingx;

    /**
     * @var int
     */
    public $keyspacingy;

    /**
     * @var labelObj
     */
    public $label;

    /**
     * Color of outline of box, -1 for no outline
     *
     * @var colorObj
     */
    public $outlinecolor;

    /**
     * for embedded legends, MS_UL, MS_UC, ...
     *
     * @var int
     */
    public $position;

    /**
     * MS_TRUE, MS_FALSE
     *
     * @var int
     */
    public $postlabelcache;

    /**
     * MS_ON, MS_OFF, MS_EMBED
     *
     * @var int
     */
    public $status;

    /**
     * @var string
     */
    public $template;

    /**
     * @var int
     */
    public $width;

    /**
     * Saves the object to a string.  Provides the inverse option for
     * updateFromString.
     *
     * @return string
     */
    final public function convertToString() {}

    /**
     * Free the object properties and break the internal references.
     * Note that you have to unset the php variable to free totally the
     * resources.
     *
     * @return void
     */
    final public function free() {}

    /**
     * Set object property to a new value.
     *
     * @param string $property_name
     * @param  $new_value
     * @return int
     */
    final public function set($property_name, $new_value) {}

    /**
     * Update a legend from a string snippet. Returns MS_SUCCESS/MS_FAILURE.
     *
     * @param string $snippet
     * @return int
     */
    final public function updateFromString($snippet) {}
}

final class lineObj
{
    /**
     * read-only
     *
     * @var int
     */
    public $numpoints;

    final public function __construct() {}

    /**
     * Old style constructor
     *
     * @return lineObj
     */
    final public function ms_newLineObj() {}

    /**
     * Add a point to the end of line. Returns MS_SUCCESS/MS_FAILURE.
     *
     * @param pointObj $point
     * @return int
     */
    final public function add(pointObj $point) {}

    /**
     * Add a point to the end of line. Returns MS_SUCCESS/MS_FAILURE.
     * .. note::
     * the 3rd parameter m is used for measured shape files only.
     * It is not mandatory.
     *
     * @param float $x
     * @param float $y
     * @param float $m
     * @return int
     */
    final public function addXY($x, $y, $m) {}

    /**
     * Add a point to the end of line. Returns MS_SUCCESS/MS_FAILURE.
     * .. note::
     * the 4th parameter m is used for measured shape files only.
     * It is not mandatory.
     *
     * @param float $x
     * @param float $y
     * @param float $z
     * @param float $m
     * @return int
     */
    final public function addXYZ($x, $y, $z, $m) {}

    /**
     * Returns a reference to point number i.
     *
     * @param int $i
     * @return pointObj
     */
    final public function point($i) {}

    /**
     * Project the line from "in" projection (1st argument) to "out"
     * projection (2nd argument).  Returns MS_SUCCESS/MS_FAILURE.
     *
     * @param projectionObj $in
     * @param projectionObj $out
     * @return int
     */
    final public function project(projectionObj $in, projectionObj $out) {}
}

final class mapObj
{
    /**
     * @var float
     */
    public $cellsize;

    /**
     * @var int
     */
    public $debug;

    /**
     * pixels per inch, defaults to 72
     *
     * @var float
     */
    public $defresolution;

    /**
     * ;
     *
     * @var rectObj
     */
    public $extent;

    /**
     * read-only, set by setFontSet()
     *
     * @var string
     */
    public $fontsetfilename;

    /**
     * see setSize()
     *
     * @var int
     */
    public $height;

    /**
     * @var colorObj
     */
    public $imagecolor;

    /**
     * @var int
     */
    public $keysizex;

    /**
     * @var int
     */
    public $keysizey;

    /**
     * @var int
     */
    public $keyspacingx;

    /**
     * @var int
     */
    public $keyspacingy;

    /**
     * no members. Used only to free the
     * label cache (map->labelcache->free()
     *
     * @var labelcacheObj
     */
    public $labelcache;

    /**
     * @var legendObj
     */
    public $legend;

    /**
     * @var string
     */
    public $mappath;

    /**
     * @var int
     */
    public $maxsize;

    /**
     * @var hashTableObj
     */
    public $metadata;

    /**
     * @var string
     */
    public $name;

    /**
     * read-only
     *
     * @var int
     */
    public $numlayers;

    /**
     * @var outputformatObj
     */
    public $outputformat;

    /**
     * read-only
     *
     * @var int
     */
    public $numoutputformats;

    /**
     * @var projectionObj
     */
    public $projection;

    /**
     * @var querymapObj
     */
    public $querymap;

    /**
     * @var referenceMapObj
     */
    public $reference;

    /**
     * pixels per inch, defaults to 72
     *
     * @var float
     */
    public $resolution;

    /**
     * @var scalebarObj
     */
    public $scalebar;

    /**
     * read-only, set by drawMap()
     *
     * @var float
     */
    public $scaledenom;

    /**
     * @var string
     */
    public $shapepath;

    /**
     * @var int
     */
    public $status;

    /**
     * read-only, set by setSymbolSet()
     *
     * @var string
     */
    public $symbolsetfilename;

    /**
     * map units type
     *
     * @var int
     */
    public $units;

    /**
     * @var webObj
     */
    public $web;

    /**
     * see setSize()
     *
     * @var int
     */
    public $width;

    /**
     * Returns a new object to deal with a MapServer map file.
     * Construct a new mapObj from a mapfile string. Returns a new object to deal
     * with a MapServer map file.
     * .. note::
     * By default, the SYMBOLSET, FONTSET, and other paths in the mapfile
     * are relative to the mapfile location.  If new_map_path is provided
     * then this directory will be used as the base path for all the
     * rewlative paths inside the mapfile.
     *
     * @param string $map_file_name
     * @param string $new_map_path
     */
    final public function __construct($map_file_name, $new_map_path) {}

    /**
     * Old style constructor
     *
     * @param string $map_file_string
     * @param string $new_map_path
     * @return mapObj
     */
    final public function ms_newMapObjFromString($map_file_string, $new_map_path) {}

    /**
     * Applies the config options set in the map file. For example
     * setting the PROJ_LIB using the setconfigoption only modifies
     * the value in the map object. applyconfigoptions will actually
     * change the PROJ_LIB value that will be used when dealing with
     * projection.
     *
     * @return int
     */
    final public function applyconfigoptions() {}

    /**
     * Apply the :ref:`SLD` document to the map file. The matching between the
     * sld document and the map file will be done using the layer's name.
     * See :ref:`SLD HowTo <sld>` for more information on the SLD support.
     *
     * @param string $sldxml
     * @return int
     */
    final public function applySLD($sldxml) {}

    /**
     * Apply the SLD document pointed by the URL to the map file. The
     * matching between the sld document and the map file will be done
     * using the layer's name.
     * See :ref:`SLD HowTo <sld>` for more information on the SLD support.
     *
     * @param string $sldurl
     * @return int
     */
    final public function applySLDURL($sldurl) {}

    /**
     * Saves the object to a string.
     * .. note::
     * The inverse method updateFromString does not exist for the mapObj
     * .. versionadded:: 6.4
     *
     * @return string
     */
    final public function convertToString() {}

    /**
     * Render map and return an image object or NULL on error.
     *
     * @return imageObj|null
     */
    final public function draw() {}

    /**
     * Renders the labels for a map. Returns MS_SUCCESS or MS_FAILURE on error.
     *
     * @param imageObj $image
     * @return int
     */
    final public function drawLabelCache(imageObj $image) {}

    /**
     * Render legend and return an image object.
     *
     * @return imageObj
     */
    final public function drawLegend() {}

    /**
     * Render a query map and return an image object or NULL on error.
     *
     * @return imageObj|null
     */
    final public function drawQuery() {}

    /**
     * Render reference map and return an image object.
     *
     * @return imageObj
     */
    final public function drawReferenceMap() {}

    /**
     * Render scale bar and return an image object.
     *
     * @return imageObj
     */
    final public function drawScaleBar() {}

    /**
     * embeds a legend. Actually the legend is just added to the label
     * cache so you must invoke drawLabelCache() to actually do the
     * rendering (unless postlabelcache is set in which case it is
     * drawn right away). Returns MS_SUCCESS or MS_FAILURE on error.
     *
     * @param imageObj $image
     * @return int
     */
    final public function embedLegend(imageObj $image) {}

    /**
     * embeds a scalebar. Actually the scalebar is just added to the label
     * cache so you must invoke drawLabelCache() to actually do the rendering
     * (unless postlabelcache is set in which case it is drawn right away).
     * Returns MS_SUCCESS or MS_FAILURE on error.
     *
     * @param imageObj $image
     * @return int
     */
    final public function embedScalebar(imageObj $image) {}

    /**
     * Free the object properties and break the internal references.
     * Note that you have to unset the php variable to free totally the
     * resources.
     * void freeQuery(layerindex)
     * Frees the query result on a specified layer. If the layerindex is -1,
     * all queries on layers will be freed.
     *
     * @return void
     */
    final public function free() {}

    /**
     * Returns an SLD XML string based on all the classes found in all
     * the layers that have `STATUS` `on`.
     *
     * @return string
     */
    final public function generateSLD() {}

    /**
     * Return an array containing all the group names used in the
     * layers. If there are no groups, it returns an empty array.
     *
     * @return array
     */
    final public function getAllGroupNames() {}

    /**
     * Return an array containing all the layer names.
     * If there are no layers, it returns an empty array.
     *
     * @return array
     */
    final public function getAllLayerNames() {}

    /**
     * Returns a colorObj corresponding to the color index in the
     * palette.
     *
     * @param int $iCloIndex
     * @return colorObj
     */
    final public function getColorbyIndex($iCloIndex) {}

    /**
     * Returns the config value associated with the key.
     * Returns an empty sting if key not found.
     *
     * @param string $key
     * @return string
     */
    final public function getConfigOption($key) {}

    /**
     * Returns a labelcacheMemberObj from the map given an index value
     * (0=first label).  Labelcache has to be enabled.
     * .. code-block:: php
     * while ($oLabelCacheMember = $oMap->getLabel($i)) {
     *  do something with the labelcachemember
     * ++$i;
     * }
     *
     * @param int $index
     * @return labelcacheMemberObj
     */
    final public function getLabel($index) {}

    /**
     * Returns a layerObj from the map given an index value (0=first layer)
     *
     * @param int $index
     * @return layerObj
     */
    final public function getLayer($index) {}

    /**
     * Returns a layerObj from the map given a layer name.
     * Returns NULL if layer doesn't exist.
     *
     * @param string $layer_name
     * @return layerObj
     */
    final public function getLayerByName($layer_name) {}

    /**
     * Return an array containing layer's index in the order which they
     * are drawn. If there are no layers, it returns an empty array.
     *
     * @return array
     */
    final public function getLayersDrawingOrder() {}

    /**
     * Return an array containing all the layer's indexes given
     * a group name. If there are no layers, it returns an empty array.
     *
     * @param string $groupname
     * @return array
     */
    final public function getLayersIndexByGroup($groupname) {}

    /**
     * Fetch metadata entry by name (stored in the :ref:`WEB` object in
     * the map file).  Returns "" if no entry matches the name.
     * .. note::
     * getMetaData's query is case sensitive.
     *
     * @param string $name
     * @return int
     */
    final public function getMetaData($name) {}

    /**
     * Return the number of symbols in map.
     *
     * @return int
     */
    final public function getNumSymbols() {}

    /**
     * Returns a string representation of the projection.
     * Returns NULL on error or if no projection is set.
     *
     * @return string
     */
    final public function getProjection() {}

    /**
     * Returns the symbol index using the name.
     *
     * @param string $symbol_name
     * @return int
     */
    final public function getSymbolByName($symbol_name) {}

    /**
     * Returns the symbol object using a symbol id. Refer to
     * the symbol object reference section for more details.
     * int insertLayer( layerObj layer [, int nIndex=-1 ] )
     * Insert a copy of *layer* into the Map at index *nIndex*.  The
     * default value of *nIndex* is -1, which means the last possible
     * index.  Returns the index of the new Layer, or -1 in the case of a
     * failure.
     *
     * @param int $symbolid
     * @return symbolObj
     */
    final public function getSymbolObjectById($symbolid) {}

    /**
     * Available only if WMS support is enabled.  Load a :ref:`WMS Map Context <map_context>`
     * XML file into the current mapObj.  If the
     * map already contains some layers then the layers defined in the
     * WMS Map context document are added to the current map.  The 2nd
     * argument unique_layer_name is optional and if set to MS_TRUE
     * layers created will have a unique name (unique prefix added to the
     * name). If set to MS_FALSE the layer name will be the the same name
     * as in the context. The default value is MS_FALSE.  Returns
     * MS_SUCCESS/MS_FAILURE.
     *
     * @param string $filename
     * @param bool $unique_layer_name
     * @return int
     */
    final public function loadMapContext($filename, $unique_layer_name) {}

    /**
     * Load OWS request parameters (BBOX, LAYERS, &c.) into map.  Returns
     * MS_SUCCESS or MS_FAILURE.  2nd argument version is not mandatory.
     * If not given, the version will be set to 1.1.1
     * int loadQuery(filename)
     * Loads a query from a file. Returns MS_SUCCESS or MS_FAILURE.
     * To be used with savequery.
     *
     * @param OwsrequestObj $request
     * @param string $version
     * @return int
     */
    final public function loadOWSParameters(OwsrequestObj $request, $version) {}

    /**
     * Move layer down in the hierarchy of drawing.
     * Returns MS_SUCCESS or MS_FAILURE on error.
     *
     * @param int $layerindex
     * @return int
     */
    final public function moveLayerDown($layerindex) {}

    /**
     * Move layer up in the hierarchy of drawing.
     * Returns MS_SUCCESS or MS_FAILURE on error.
     *
     * @param int $layerindex
     * @return int
     */
    final public function moveLayerUp($layerindex) {}

    /**
     * Offset the map extent based on the given distances in map coordinates.
     * Returns MS_SUCCESS or MS_FAILURE.
     *
     * @param float $x
     * @param float $y
     * @return int
     */
    final public function offsetExtent($x, $y) {}

    /**
     * Processes and executes the passed OpenGIS Web Services request on
     * the map.  Returns MS_DONE (2) if there is no valid OWS request in
     * the req object, MS_SUCCESS (0) if an OWS request was successfully
     * processed and MS_FAILURE (1) if an OWS request was not
     * successfully processed.  OWS requests include :ref:`WMS <wms_server>`,
     * :ref:`WFS <wfs_server>`, :ref:`WCS <wcs_server>`
     * and :ref:`SOS <sos_server>` requests supported by MapServer.
     * Results of a dispatched request are written to stdout and can be
     * captured using the msIO services (ie. ms_ioinstallstdouttobuffer()
     * and ms_iogetstdoutbufferstring())
     *
     * @param OwsrequestObj $request
     * @return int
     */
    final public function owsDispatch(OwsrequestObj $request) {}

    /**
     * Return a blank image object.
     *
     * @return imageObj
     */
    final public function prepareImage() {}

    /**
     * Calculate the scale of the map and set map->scaledenom.
     *
     * @return void
     */
    final public function prepareQuery() {}

    /**
     * Process legend template files and return the result in a buffer.
     * .. seealso::
     * :ref:`processtemplate <processtemplate>`
     *
     * @param array $params
     * @return string
     */
    final public function processLegendTemplate(array $params) {}

    /**
     * Process query template files and return the result in a buffer.
     * Second argument generateimages is not mandatory. If not given
     * it will be set to TRUE.
     * .. seealso::
     * :ref:`processtemplate <processtemplate>`
     * .. _processtemplate:
     *
     * @param array $params
     * @param bool $generateimages
     * @return string
     */
    final public function processQueryTemplate(array $params, $generateimages) {}

    /**
     * Process the template file specified in the web object and return the
     * result in a buffer. The processing consists of opening the template
     * file and replace all the tags found in it. Only tags that have an
     * equivalent element in the map object are replaced (ex [scaledenom]).
     * The are two exceptions to the previous statement :
     * - [img], [scalebar], [ref], [legend] would be replaced with the
     * appropriate url if the parameter generateimages is set to
     * MS_TRUE. (Note :  the images corresponding to the different objects
     * are generated if the object is set to MS_ON in the map file)
     * - the user can use the params parameter to specify tags and
     * their values. For example if the user have a specific tag call
     * [my_tag] and would like it to be replaced by "value_of_my_tag"
     * he would do
     * .. code-block:: php
     * $tmparray["my_tag"] = "value_of_my_tag";
     * $map->processtemplate($tmparray, MS_FALSE);
     *
     * @param array $params
     * @param bool $generateimages
     * @return string
     */
    final public function processTemplate(array $params, $generateimages) {}

    /**
     * Perform a query based on a previous set of results from
     * a layer. At present the results MUST be based on a polygon layer.
     * Returns MS_SUCCESS if shapes were found or MS_FAILURE if nothing
     * was found or if some other error happened (note that the error
     * message in case nothing was found can be avoided in PHP using
     * the '@' control operator).
     *
     * @param int $slayer
     * @return int
     */
    final public function queryByFeatures($slayer) {}

    /**
     * Add a specific shape on a given layer to the query result.
     * If addtoquery (which is a non mandatory argument) is set to MS_TRUE,
     * the shape will be added to the existing query list. Default behavior
     * is to free the existing query list and add only the new shape.
     *
     * @param  $layerindex
     * @param  $tileindex
     * @param  $shapeindex
     * @param  $addtoquery
     * @return int
     */
    final public function queryByIndex($layerindex, $tileindex, $shapeindex, $addtoquery) {}

    /**
     * Query all selected layers in map at point location specified in
     * georeferenced map coordinates (i.e. not pixels).
     * The query is performed on all the shapes that are part of a :ref:`CLASS`
     * that contains a :ref:`TEMPLATE` value or that match any class in a
     * layer that contains a :ref:`LAYER` :ref:`TEMPLATE <template>` value.
     * Mode is MS_SINGLE or MS_MULTIPLE depending on number of results
     * you want.
     * Passing buffer -1 defaults to tolerances set in the map file
     * (in pixels) but you can use a constant buffer (specified in
     * ground units) instead.
     * Returns MS_SUCCESS if shapes were found or MS_FAILURE if nothing
     * was found or if some other error happened (note that the error
     * message in case nothing was found can be avoided in PHP using
     * the '@' control operator).
     *
     * @param pointObj $point
     * @param int $mode
     * @param float $buffer
     * @return int
     */
    final public function queryByPoint(pointObj $point, $mode, $buffer) {}

    /**
     * Query all selected layers in map using a rectangle specified in
     * georeferenced map coordinates (i.e. not pixels).  The query is
     * performed on all the shapes that are part of a :ref:`CLASS` that
     * contains a :ref:`TEMPLATE` value or that match any class in a
     * layer that contains a :ref:`LAYER` :ref:`TEMPLATE <template>`
     * value.  Returns MS_SUCCESS if shapes were found or MS_FAILURE if
     * nothing was found or if some other error happened (note that the
     * error message in case nothing was found can be avoided in PHP
     * using the '@' control operator).
     *
     * @param rectObj $rect
     * @return int
     */
    final public function queryByRect(rectObj $rect) {}

    /**
     * Query all selected layers in map based on a single shape, the
     * shape has to be a polygon at this point.
     * Returns MS_SUCCESS if shapes were found or MS_FAILURE if nothing
     * was found or if some other error happened (note that the error
     * message in case nothing was found can be avoided in PHP using
     * the '@' control operator).
     *
     * @param shapeObj $shape
     * @return int
     */
    final public function queryByShape(shapeObj $shape) {}

    /**
     * Remove a layer from the mapObj. The argument is the index of the
     * layer to be removed. Returns the removed layerObj on success, else
     * null.
     *
     * @param int $nIndex
     * @return layerObj
     */
    final public function removeLayer($nIndex) {}

    /**
     * Remove a metadata entry for the map (stored in the WEB object in the map
     * file).  Returns MS_SUCCESS/MS_FAILURE.
     *
     * @param string $name
     * @return int
     */
    final public function removeMetaData($name) {}

    /**
     * Save current map object state to a file. Returns -1 on error.
     * Use absolute path. If a relative path is used, then it will be
     * relative to the mapfile location.
     *
     * @param string $filename
     * @return int
     */
    final public function save($filename) {}

    /**
     * Available only if WMS support is enabled.  Save current map object
     * state in :ref:`WMS Map Context <map_context>` format.  Only WMS
     * layers are saved in the WMS Map Context XML file.  Returns
     * MS_SUCCESS/MS_FAILURE.
     *
     * @param string $filename
     * @return int
     */
    final public function saveMapContext($filename) {}

    /**
     * Save the current query in a file. Results determines the save format -
     * MS_TRUE (or 1/true) saves the query results (tile index and shape index),
     * MS_FALSE (or 0/false) the query parameters (and the query will be re-run
     * in loadquery). Returns MS_SUCCESS or MS_FAILURE. Either save format can be
     * used with loadquery. See RFC 65 and ticket #3647 for details of different
     * save formats.
     *
     * @param string $filename
     * @param int $results
     * @return int
     */
    final public function saveQuery($filename, $results) {}

    /**
     * Scale the map extent using the zoomfactor and ensure the extent
     * within the minscaledenom and maxscaledenom domain.  If
     * minscaledenom and/or maxscaledenom is 0 then the parameter is not
     * taken into account.  Returns MS_SUCCESS or MS_FAILURE.
     *
     * @param float $zoomfactor
     * @param float $minscaledenom
     * @param float $maxscaledenom
     * @return int
     */
    final public function scaleExtent($zoomfactor, $minscaledenom, $maxscaledenom) {}

    /**
     * Selects the output format to be used in the map.
     * Returns MS_SUCCESS/MS_FAILURE.
     * .. note::
     * the type used should correspond to one of the output formats
     * declared in the map file.  The type argument passed is compared
     * with the mimetype parameter in the output format structure and
     * then to the name parameter in the structure.
     *
     * @param string $type
     * @return int
     */
    final public function selectOutputFormat($type) {}

    /**
     * Appends outputformat object in the map object.
     * Returns the new numoutputformats value.
     *
     * @param outputFormatObj $outputFormat
     * @return int
     */
    final public function appendOutputFormat(outputFormatObj $outputFormat) {}

    /**
     * Remove outputformat from the map.
     * Returns MS_SUCCESS/MS_FAILURE.
     *
     * @param string $name
     * @return int
     */
    final public function removeOutputFormat($name) {}

    /**
     * Returns the outputformat at index position.
     *
     * @param int $index
     * @return outputFormatObj
     */
    final public function getOutputFormat($index) {}

    /**
     * Set map object property to new value.
     *
     * @param string $property_name
     * @param  $new_value
     * @return int
     */
    final public function set($property_name, $new_value) {}

    /**
     * Set the map center to the given map point.
     * Returns MS_SUCCESS or MS_FAILURE.
     *
     * @param pointObj $center
     * @return int
     */
    final public function setCenter(pointObj $center) {}

    /**
     * Sets a config parameter using the key and the value passed
     *
     * @param string $key
     * @param string $value
     * @return int
     */
    final public function setConfigOption($key, $value) {}

    /**
     * Set the map extents using the georef extents passed in argument.
     * Returns MS_SUCCESS or MS_FAILURE on error.
     *
     * @param float $minx
     * @param float $miny
     * @param float $maxx
     * @param float $maxy
     * @return void
     */
    final public function setExtent($minx, $miny, $maxx, $maxy) {}

    /**
     * Load and set a new :ref:`fontset`.
     * boolean  setLayersDrawingOrder(array layeryindex)
     * Set the layer's order array. The argument passed must be a valid
     * array with all the layer's index.
     * Returns MS_SUCCESS or MS_FAILURE on error.
     *
     * @param string $fileName
     * @return int
     */
    final public function setFontSet($fileName) {}

    /**
     * Set a metadata entry for the map (stored in the WEB object in the map
     * file).  Returns MS_SUCCESS/MS_FAILURE.
     *
     * @param string $name
     * @param string $value
     * @return int
     */
    final public function setMetaData($name, $value) {}

    /**
     * Set map projection and coordinate system. Returns MS_SUCCESS or
     * MS_FAILURE on error.
     * Parameters are given as a single string of comma-delimited PROJ.4
     * parameters.  The argument : bSetUnitsAndExtents is used to
     * automatically update the map units and extents based on the new
     * projection. Possible values are MS_TRUE and MS_FALSE. By default it is
     * set at MS_FALSE.
     *
     * @param string $proj_params
     * @param bool $bSetUnitsAndExtents
     * @return int
     */
    final public function setProjection($proj_params, $bSetUnitsAndExtents) {}

    /**
     * Set map rotation angle. The map view rectangle (specified in
     * EXTENTS) will be rotated by the indicated angle in the counter-
     * clockwise direction. Note that this implies the rendered map
     * will be rotated by the angle in the clockwise direction.
     * Returns MS_SUCCESS or MS_FAILURE.
     *
     * @param float $rotation_angle
     * @return int
     */
    final public function setRotation($rotation_angle) {}

    /**
     * Set the map width and height. This method updates the internal
     * geotransform and other data structures required for map rotation
     * so it should be used instead of setting the width and height members
     * directly.
     * Returns MS_SUCCESS or MS_FAILURE.
     *
     * @param int $width
     * @param int $height
     * @return int
     */
    final public function setSize($width, $height) {}

    /**
     * Load and set a symbol file dynamically.
     *
     * @param string $fileName
     * @return int
     */
    final public function setSymbolSet($fileName) {}

    /**
     * Same as setProjection(), but takes an OGC WKT projection
     * definition string as input. Returns MS_SUCCESS or MS_FAILURE on error.
     * .. note::
     * setWKTProjection requires GDAL support
     *
     * @param string $proj_params
     * @param bool $bSetUnitsAndExtents
     * @return int
     */
    final public function setWKTProjection($proj_params, $bSetUnitsAndExtents) {}

    /**
     * Zoom to a given XY position. Returns MS_SUCCESS or MS_FAILURE on error.
     * Parameters are
     * - Zoom factor : positive values do zoom in, negative values
     * zoom out. Factor of 1 will recenter.
     * - Pixel position (pointObj) : x, y coordinates of the click,
     * with (0,0) at the top-left
     * - Width : width in pixel of the current image.
     * - Height : Height in pixel of the current image.
     * - Georef extent (rectObj) : current georef extents.
     * - MaxGeoref extent (rectObj) : (optional) maximum georef extents.
     * If provided then it will be impossible to zoom/pan outside of
     * those extents.
     *
     * @param int $nZoomFactor
     * @param pointObj $oPixelPos
     * @param int $nImageWidth
     * @param int $nImageHeight
     * @param rectObj $oGeorefExt
     * @return int
     */
    final public function zoomPoint($nZoomFactor, pointObj $oPixelPos, $nImageWidth, $nImageHeight, rectObj $oGeorefExt) {}

    /**
     * Set the map extents to a given extents. Returns MS_SUCCESS or
     * MS_FAILURE on error.
     * Parameters are :
     * - oPixelExt (rect object) : Pixel Extents
     * - Width : width in pixel of the current image.
     * - Height : Height in pixel of the current image.
     * - Georef extent (rectObj) : current georef extents.
     *
     * @param rectObj $oPixelExt
     * @param int $nImageWidth
     * @param int $nImageHeight
     * @param rectObj $oGeorefExt
     * @return int
     */
    final public function zoomRectangle(rectObj $oPixelExt, $nImageWidth, $nImageHeight, rectObj $oGeorefExt) {}

    /**
     * Zoom in or out to a given XY position so that the map is
     * displayed at specified scale. Returns MS_SUCCESS or MS_FAILURE on error.
     * Parameters are :
     * - ScaleDenom : Scale denominator of the scale at which the map
     * should be displayed.
     * - Pixel position (pointObj) : x, y coordinates of the click,
     * with (0,0) at the top-left
     * - Width : width in pixel of the current image.
     * - Height : Height in pixel of the current image.
     * - Georef extent (rectObj) : current georef extents.
     * - MaxGeoref extent (rectObj) : (optional) maximum georef extents.
     * If provided then it will be impossible to zoom/pan outside of
     * those extents.
     *
     * @param float $nScaleDenom
     * @param pointObj $oPixelPos
     * @param int $nImageWidth
     * @param int $nImageHeight
     * @param rectObj $oGeorefExt
     * @param rectObj $oMaxGeorefExt
     * @return int
     */
    final public function zoomScale($nScaleDenom, pointObj $oPixelPos, $nImageWidth, $nImageHeight, rectObj $oGeorefExt, rectObj $oMaxGeorefExt) {}
}

/**
 * Instance of outputformatObj is always embedded inside the `mapObj`_.
 * It is uses a read only.
 * No constructor available (coming soon, see ticket 979)
 */
final class outputformatObj
{
    /**
     * @var string
     */
    public $driver;

    /**
     * @var string
     */
    public $extension;

    /**
     * MS_IMAGEMODE_* value.
     *
     * @var int
     */
    public $imagemode;

    /**
     * @var string
     */
    public $mimetype;

    /**
     * @var string
     */
    public $name;

    /**
     * @var int
     */
    public $renderer;

    /**
     * @var int
     */
    public $transparent;

    /**
     * Returns the associated value for the format option property passed
     * as argument. Returns an empty string if property not found.
     *
     * @param string $property_name
     * @return string
     */
    final public function getOption($property_name) {}

    /**
     * Set object property to a new value.
     *
     * @param string $property_name
     * @param  $new_value
     * @return int
     */
    final public function set($property_name, $new_value) {}

    /**
     * Add or Modify the format option list. return true on success.
     * .. code-block:: php
     * $oMap->outputformat->setOption("OUTPUT_TYPE", "RASTER");
     *
     * @param string $property_name
     * @param string $new_value
     * @return void
     */
    final public function setOption($property_name, $new_value) {}

    /**
     * Checks some internal consistency issues, Returns MS_SUCCESS or
     * MS_FAILURE. Some problems are fixed up internally. May produce debug
     * output if issues encountered.
     *
     * @return int
     */
    final public function validate() {}
}

final class OwsrequestObj
{
    /**
     * (read-only)
     *
     * @var int
     */
    public $numparams;

    /**
     * (read-only): MS_GET_REQUEST or MS_POST_REQUEST
     *
     * @var int
     */
    public $type;

    /**
     * request = ms_newOwsrequestObj();
     * Create a new ows request object.
     */
    final public function __construct() {}

    /**
     * Add a request parameter, even if the parameter key was previousely set.
     * This is useful when multiple parameters with the same key are required.
     * For example :
     * .. code-block:: php
     * $request->addparameter('SIZE', 'x(100)');
     * $request->addparameter('SIZE', 'y(100)');
     *
     * @param string $name
     * @param string $value
     * @return int
     */
    final public function addParameter($name, $value) {}

    /**
     * Return the name of the parameter at *index* in the request's array
     * of parameter names.
     *
     * @param int $index
     * @return string
     */
    final public function getName($index) {}

    /**
     * Return the value of the parameter at *index* in the request's array
     * of parameter values.
     *
     * @param int $index
     * @return string
     */
    final public function getValue($index) {}

    /**
     * Return the value associated with the parameter *name*.
     *
     * @param string $name
     * @return string
     */
    final public function getValueByName($name) {}

    /**
     * Initializes the OWSRequest object from the cgi environment variables
     * REQUEST_METHOD, QUERY_STRING and HTTP_COOKIE.  Returns the number of
     * name/value pairs collected.
     *
     * @return int
     */
    final public function loadParams() {}

    /**
     * Set a request parameter.  For example :
     * .. code-block:: php
     * $request->setparameter('REQUEST', 'GetMap');
     *
     * @param string $name
     * @param string $value
     * @return int
     */
    final public function setParameter($name, $value) {}
}

final class pointObj
{
    /**
     * @var float
     */
    public $x;

    /**
     * @var float
     */
    public $y;

    /**
     * used for 3d shape files. set to 0 for other types
     *
     * @var float
     */
    public $z;

    /**
     * used only for measured shape files - set to 0 for other types
     *
     * @var float
     */
    public $m;

    final public function __construct() {}

    /**
     * Old style constructor
     *
     * @return pointObj
     */
    final public function ms_newPointObj() {}

    /**
     * Calculates distance between a point ad a lined defined by the
     * two points passed in argument.
     *
     * @param pointObj $p1
     * @param pointObj $p2
     * @return float
     */
    final public function distanceToLine(pointObj $p1, pointObj $p2) {}

    /**
     * Calculates distance between two points.
     *
     * @param pointObj $poPoint
     * @return float
     */
    final public function distanceToPoint(pointObj $poPoint) {}

    /**
     * Calculates the minimum distance between a point and a shape.
     *
     * @param shapeObj $shape
     * @return float
     */
    final public function distanceToShape(shapeObj $shape) {}

    /**
     * Draws the individual point using layer.  The class_index is used
     * to classify the point based on the classes defined for the layer.
     * The text string is used to annotate the point. (Optional)
     * Returns MS_SUCCESS/MS_FAILURE.
     *
     * @param mapObj $map
     * @param layerObj $layer
     * @param imageObj $img
     * @param int $class_index
     * @param string $text
     * @return int
     */
    final public function draw(mapObj $map, layerObj $layer, imageObj $img, $class_index, $text) {}

    /**
     * Project the point from "in" projection (1st argument) to "out"
     * projection (2nd argument).  Returns MS_SUCCESS/MS_FAILURE.
     *
     * @param projectionObj $in
     * @param projectionObj $out
     * @return int
     */
    final public function project(projectionObj $in, projectionObj $out) {}

    /**
     * Set X,Y coordinate values.
     * .. note::
     * the 3rd parameter m is used for measured shape files only.
     * It is not mandatory.
     *
     * @param float $x
     * @param float $y
     * @param float $m
     * @return int
     */
    final public function setXY($x, $y, $m) {}

    /**
     * Set X,Y,Z coordinate values.
     * .. note::
     * the 4th parameter m is used for measured shape files only.
     * It is not mandatory.
     *
     * @param float $x
     * @param float $y
     * @param float $z
     * @param float $m
     * @return int
     */
    final public function setXYZ($x, $y, $z, $m) {}
}

final class projectionObj
{
    /**
     * Creates a projection object based on the projection string passed
     * as argument.
     * $projInObj = ms_newprojectionobj("proj=latlong")
     * will create a geographic projection class.
     * The following example will convert a lat/long point to an LCC
     * projection:
     * $projInObj = ms_newprojectionobj("proj=latlong");
     * $projOutObj = ms_newprojectionobj("proj=lcc,ellps=GRS80,lat_0=49,".
     * "lon_0=-95,lat_1=49,lat_2=77");
     * $poPoint = ms_newpointobj();
     * $poPoint->setXY(-92.0, 62.0);
     * $poPoint->project($projInObj, $projOutObj);
     *
     * @param string $projectionString
     */
    final public function __construct($projectionString) {}

    /**
     * Old style constructor
     *
     * @param string $projectionString
     * @return ProjectionObj
     */
    final public function ms_newProjectionObj($projectionString) {}

    /**
     * Returns the units of a projection object. Returns -1 on error.
     *
     * @return int
     */
    final public function getUnits() {}
}

/**
 * Instances of querymapObj are always are always embedded inside the
 * `mapObj`_.
 */
final class querymapObj
{
    /**
     * @var colorObj
     */
    public $color;

    /**
     * @var int
     */
    public $height;

    /**
     * @var int
     */
    public $width;

    /**
     * MS_NORMAL, MS_HILITE, MS_SELECTED
     *
     * @var int
     */
    public $style;

    /**
     * Saves the object to a string.  Provides the inverse option for
     * updateFromString.
     *
     * @return string
     */
    final public function convertToString() {}

    /**
     * Free the object properties and break the internal references.
     * Note that you have to unset the php variable to free totally the resources.
     *
     * @return void
     */
    final public function free() {}

    /**
     * Set object property to a new value.
     *
     * @param string $property_name
     * @param  $new_value
     * @return int
     */
    final public function set($property_name, $new_value) {}

    /**
     * Update a queryMap object from a string snippet. Returns
     * MS_SUCCESS/MS_FAILURE.
     *
     * @param string $snippet
     * @return int
     */
    final public function updateFromString($snippet) {}
}

/**
 * rectObj are sometimes embedded inside other objects.  New ones can
 * also be created with:
 */
final class rectObj
{
    /**
     * @var float
     */
    public $minx;

    /**
     * @var float
     */
    public $miny;

    /**
     * @var float
     */
    public $maxx;

    /**
     * @var float
     */
    public $maxy;

    /**
     * .. note:: the members (minx, miny, maxx ,maxy) are initialized to -1;
     */
    final public function __construct() {}

    /**
     * Old style constructor
     *
     * @return RectObj
     */
    final public function ms_newRectObj() {}

    /**
     * Draws the individual rectangle using layer.  The class_index is used
     * to classify the rectangle based on the classes defined for the layer.
     * The text string is used to annotate the rectangle. (Optional)
     * Returns MS_SUCCESS/MS_FAILURE.
     *
     * @param mapObj $map
     * @param layerObj $layer
     * @param imageObj $img
     * @param int $class_index
     * @param string $text
     * @return int
     */
    final public function draw(mapObj $map, layerObj $layer, imageObj $img, $class_index, $text) {}

    /**
     * Adjust extents of the rectangle to fit the width/height specified.
     *
     * @param int $width
     * @param int $height
     * @return float
     */
    final public function fit($width, $height) {}

    /**
     * Project the rectangle from "in" projection (1st argument) to "out"
     * projection (2nd argument).  Returns MS_SUCCESS/MS_FAILURE.
     *
     * @param projectionObj $in
     * @param projectionObj $out
     * @return int
     */
    final public function project(projectionObj $in, projectionObj $out) {}

    /**
     * Set object property to a new value.
     *
     * @param string $property_name
     * @param  $new_value
     * @return int
     */
    final public function set($property_name, $new_value) {}

    /**
     * Set the rectangle extents.
     *
     * @param float $minx
     * @param float $miny
     * @param float $maxx
     * @param float $maxy
     * @return void
     */
    final public function setextent($minx, $miny, $maxx, $maxy) {}
}

/**
 * Instances of referenceMapObj are always embedded inside the `mapObj`_.
 */
final class referenceMapObj
{
    /**
     * @var ColorObj
     */
    public $color;

    /**
     * @var int
     */
    public $height;

    /**
     * @var rectObj
     */
    public $extent;

    /**
     * @var string
     */
    public $image;

    /**
     * @var int
     */
    public $marker;

    /**
     * @var string
     */
    public $markername;

    /**
     * @var int
     */
    public $markersize;

    /**
     * @var int
     */
    public $maxboxsize;

    /**
     * @var int
     */
    public $minboxsize;

    /**
     * @var ColorObj
     */
    public $outlinecolor;

    /**
     * @var int
     */
    public $status;

    /**
     * @var int
     */
    public $width;

    /**
     * Saves the object to a string.  Provides the inverse option for
     * updateFromString.
     *
     * @return string
     */
    final public function convertToString() {}

    /**
     * Free the object properties and break the internal references.
     * Note that you have to unset the php variable to free totally the
     * resources.
     *
     * @return void
     */
    final public function free() {}

    /**
     * Set object property to a new value.
     *
     * @param string $property_name
     * @param  $new_value
     * @return int
     */
    final public function set($property_name, $new_value) {}

    /**
     * Update a referenceMap object from a string snippet.
     * Returns MS_SUCCESS/MS_FAILURE.
     *
     * @param string $snippet
     * @return int
     */
    final public function updateFromString($snippet) {}
}

final class resultObj
{
    /**
     * read-only
     *
     * @var int
     */
    public $classindex;

    /**
     * read-only
     *
     * @var int
     */
    public $resultindex;

    /**
     * read-only
     *
     * @var int
     */
    public $shapeindex;

    /**
     * read-only
     *
     * @var int
     */
    public $tileindex;

    /**
     * or using the `layerObj`_'s getResult() method.
     *
     * @param int $shapeindex
     */
    final public function __construct($shapeindex) {}
}

/**
 * Instances of scalebarObj are always embedded inside the `mapObj`_.
 */
final class scalebarObj
{
    /**
     * @var int
     */
    public $align;

    /**
     * @var colorObj
     */
    public $backgroundcolor;

    /**
     * @var colorObj
     */
    public $color;

    /**
     * @var int
     */
    public $height;

    /**
     * @var colorObj
     */
    public $imagecolor;

    /**
     * @var int
     */
    public $intervals;

    /**
     * @var labelObj
     */
    public $label;

    /**
     * @var colorObj
     */
    public $outlinecolor;

    /**
     * for embedded scalebars, MS_UL, MS_UC, ...
     *
     * @var int
     */
    public $position;

    /**
     * @var int
     */
    public $postlabelcache;

    /**
     * MS_ON, MS_OFF, MS_EMBED
     *
     * @var int
     */
    public $status;

    /**
     * @var int
     */
    public $style;

    /**
     * @var int
     */
    public $units;

    /**
     * @var int
     */
    public $width;

    /**
     * Saves the object to a string.  Provides the inverse option for
     * updateFromString.
     *
     * @return string
     */
    final public function convertToString() {}

    /**
     * Free the object properties and break the internal references.
     * Note that you have to unset the php variable to free totally the
     * resources.
     *
     * @return void
     */
    final public function free() {}

    /**
     * Set object property to a new value.
     *
     * @param string $property_name
     * @param  $new_value
     * @return int
     */
    final public function set($property_name, $new_value) {}

    /**
     * Sets the imagecolor property (baclground) of the object.
     * Returns MS_SUCCESS or MS_FAILURE on error.
     *
     * @param int $red
     * @param int $green
     * @param int $blue
     * @return int
     */
    final public function setImageColor($red, $green, $blue) {}

    /**
     * Update a scalebar from a string snippet. Returns MS_SUCCESS/MS_FAILURE.
     *
     * @param string $snippet
     * @return int
     */
    final public function updateFromString($snippet) {}
}

final class shapefileObj
{
    /**
     * read-only
     *
     * @var rectObj
     */
    public $bounds;

    /**
     * read-only
     *
     * @var int
     */
    public $numshapes;

    /**
     * read-only
     *
     * @var string
     */
    public $source;

    /**
     * read-only
     *
     * @var int
     */
    public $type;

    /**
     * Opens a shapefile and returns a new object to deal with it. Filename
     * should be passed with no extension.  To create a new file (or
     * overwrite an existing one), type should be one of MS_SHP_POINT,
     * MS_SHP_ARC, MS_SHP_POLYGON or MS_SHP_MULTIPOINT. Pass type as -1 to
     * open an existing file for read-only access, and type=-2 to open an
     * existing file for update (append).
     *
     * @param string $filename
     * @param int $type
     */
    final public function __construct($filename, $type) {}

    /**
     * Old style constructor
     *
     * @param string $filename
     * @param int $type
     * @return shapefileObj
     */
    final public function ms_newShapefileObj($filename, $type) {}

    /**
     * Appends a point to an open shapefile.
     *
     * @param pointObj $point
     * @return int
     */
    final public function addPoint(pointObj $point) {}

    /**
     * Appends a shape to an open shapefile.
     *
     * @param shapeObj $shape
     * @return int
     */
    final public function addShape(shapeObj $shape) {}

    /**
     * Free the object properties and break the internal references.
     * Note that you have to unset the php variable to free totally the
     * resources.
     * .. note::
     * The shape file is closed (and changes committed) when
     * the object is destroyed. You can explicitly close and save
     * the changes by calling $shapefile->free();
     * unset($shapefile), which will also free the php object.
     *
     * @return void
     */
    final public function free() {}

    /**
     * Retrieve a shape's bounding box by index.
     *
     * @param int $i
     * @return rectObj
     */
    final public function getExtent($i) {}

    /**
     * Retrieve point by index.
     *
     * @param int $i
     * @return shapeObj
     */
    final public function getPoint($i) {}

    /**
     * Retrieve shape by index.
     *
     * @param int $i
     * @return shapeObj
     */
    final public function getShape($i) {}

    /**
     * Retrieve shape by index.
     *
     * @param mapObj $map
     * @param int $i
     * @return shapeObj
     */
    final public function getTransformed(mapObj $map, $i) {}
}

final class shapeObj
{
    /**
     * read-only
     *
     * @var rectObj
     */
    public $bounds;

    /**
     * @var int
     */
    public $classindex;

    /**
     * @var int
     */
    public $index;

    /**
     * read-only
     *
     * @var int
     */
    public $numlines;

    /**
     * read-only
     *
     * @var int
     */
    public $numvalues;

    /**
     * read-only
     *
     * @var int
     */
    public $tileindex;

    /**
     * @var string
     */
    public $text;

    /**
     * read-only
     *
     * @var int
     */
    public $type;

    /**
     * read-only
     *
     * @var array
     */
    public $values;

    /**
     * 'type' is one of MS_SHAPE_POINT, MS_SHAPE_LINE, MS_SHAPE_POLYGON or
     * MS_SHAPE_NULL
     * Creates new shape object from WKT string.
     *
     * @param int $type
     */
    final public function __construct($type) {}

    /**
     * Old style constructor
     *
     * @param string $wkt
     * @return ShapeObj
     */
    final public function ms_shapeObjFromWkt($wkt) {}

    /**
     * Add a line (i.e. a part) to the shape.
     *
     * @param lineObj $line
     * @return int
     */
    final public function add(lineObj $line) {}

    /**
     * Returns the boundary of the shape.
     * Only available if php/mapscript is built with GEOS library.
     * shapeObj buffer(width)
     * Returns a new buffered shapeObj based on the supplied distance (given
     * in the coordinates of the existing shapeObj).
     * Only available if php/mapscript is built with GEOS library.
     *
     * @return shapeObj
     */
    final public function boundary() {}

    /**
     * Returns true if shape2 passed as argument is entirely within the shape.
     * Else return false.
     * Only available if php/mapscript is built with GEOS
     * library.
     *
     * @param shapeObj $shape2
     * @return int
     */
    final public function containsShape(shapeObj $shape2) {}

    /**
     * Returns a shape object representing the convex hull of shape.
     * Only available if php/mapscript is built with GEOS
     * library.
     *
     * @return shapeObj
     */
    final public function convexhull() {}

    /**
     * Returns MS_TRUE if the point is inside the shape, MS_FALSE otherwise.
     *
     * @param pointObj $point
     * @return bool
     */
    final public function contains(pointObj $point) {}

    /**
     * Returns true if the shape passed as argument crosses the shape.
     * Else return false.
     * Only available if php/mapscript is built with GEOS library.
     *
     * @param shapeObj $shape
     * @return int
     */
    final public function crosses(shapeObj $shape) {}

    /**
     * Returns a shape object representing the difference of the
     * shape object with the one passed as parameter.
     * Only available if php/mapscript is built with GEOS library.
     *
     * @param shapeObj $shape
     * @return shapeObj
     */
    final public function difference(shapeObj $shape) {}

    /**
     * Returns true if the shape passed as argument is disjoint to the
     * shape. Else return false.
     * Only available if php/mapscript is built with GEOS library.
     *
     * @param shapeObj $shape
     * @return int
     */
    final public function disjoint(shapeObj $shape) {}

    /**
     * Draws the individual shape using layer.
     * Returns MS_SUCCESS/MS_FAILURE.
     *
     * @param mapObj $map
     * @param layerObj $layer
     * @param imageObj $img
     * @return int
     */
    final public function draw(mapObj $map, layerObj $layer, imageObj $img) {}

    /**
     * Returns true if the shape passed as argument is equal to the
     * shape (geometry only). Else return false.
     * Only available if php/mapscript is built with GEOS library.
     *
     * @param shapeObj $shape
     * @return int
     */
    final public function equals(shapeObj $shape) {}

    /**
     * Free the object properties and break the internal references.
     * Note that you have to unset the php variable to free totally the resources.
     *
     * @return void
     */
    final public function free() {}

    /**
     * Returns the area of the shape (if applicable).
     * Only available if php/mapscript is built with GEOS library.
     *
     * @return float
     */
    final public function getArea() {}

    /**
     * Returns a point object representing the centroid of the shape.
     * Only available if php/mapscript is built with GEOS library.
     *
     * @return pointObj
     */
    final public function getCentroid() {}

    /**
     * Returns a point object with coordinates suitable for labelling
     * the shape.
     *
     * @return pointObj
     */
    final public function getLabelPoint() {}

    /**
     * Returns the length (or perimeter) of the shape.
     * Only available if php/mapscript is built with GEOS library.
     * pointObj  getMeasureUsingPoint(pointObj point)
     * Apply only on Measured shape files. Given an XY Location, find the
     * nearest point on the shape object. Return a point object
     * of this point with the m value set.
     *
     * @return float
     */
    final public function getLength() {}

    /**
     * Apply only on Measured shape files. Given a measure m, retun the
     * corresponding XY location on the shapeobject.
     *
     * @param float $m
     * @return pointObj
     */
    final public function getPointUsingMeasure($m) {}

    /**
     * Returns the value for a given field name.
     *
     * @param layerObj $layer
     * @param string $filedname
     * @return string
     */
    final public function getValue(layerObj $layer, $filedname) {}

    /**
     * Returns a shape object representing the intersection of the shape
     * object with the one passed as parameter.
     * Only available if php/mapscript is built with GEOS library.
     *
     * @param shapeObj $shape
     * @return shapeObj
     */
    final public function intersection(shapeObj $shape) {}

    /**
     * Returns MS_TRUE if the two shapes intersect, MS_FALSE otherwise.
     *
     * @param shapeObj $shape
     * @return bool
     */
    final public function intersects(shapeObj $shape) {}

    /**
     * Returns a reference to line number i.
     *
     * @param int $i
     * @return lineObj
     */
    final public function line($i) {}

    /**
     * Returns true if the shape passed as argument overlaps the shape.
     * Else returns false.
     * Only available if php/mapscript is built with GEOS library.
     *
     * @param shapeObj $shape
     * @return int
     */
    final public function overlaps(shapeObj $shape) {}

    /**
     * Project the shape from "in" projection (1st argument) to "out"
     * projection (2nd argument).  Returns MS_SUCCESS/MS_FAILURE.
     *
     * @param projectionObj $in
     * @param projectionObj $out
     * @return int
     */
    final public function project(projectionObj $in, projectionObj $out) {}

    /**
     * Set object property to a new value.
     *
     * @param string $property_name
     * @param  $new_value
     * @return int
     */
    final public function set($property_name, $new_value) {}

    /**
     * Updates the bounds property of the shape.
     * Must be called to calculate new bounding box after new parts have been
     * added.
     *
     * @return int
     */
    final public function setBounds() {}

    /**
     * Given a tolerance, returns a simplified shape object or NULL on
     * error.  Only available if php/mapscript is built with GEOS library
     * (>=3.0).
     *
     * @param float $tolerance
     * @return shapeObj|null
     */
    final public function simplify($tolerance) {}

    /**
     * Returns the computed symmetric difference of the supplied and
     * existing shape.
     * Only available if php/mapscript is built with GEOS library.
     *
     * @param shapeObj $shape
     * @return shapeObj
     */
    final public function symdifference(shapeObj $shape) {}

    /**
     * Given a tolerance, returns a simplified shape object or NULL on
     * error.  Only available if php/mapscript is built with GEOS library
     * (>=3.0).
     *
     * @param float $tolerance
     * @return shapeObj|null
     */
    final public function topologyPreservingSimplify($tolerance) {}

    /**
     * Returns true if the shape passed as argument touches the shape.
     * Else return false.
     * Only available if php/mapscript is built with GEOS library.
     *
     * @param shapeObj $shape
     * @return int
     */
    final public function touches(shapeObj $shape) {}

    /**
     * Returns WKT representation of the shape's geometry.
     *
     * @return string
     */
    final public function toWkt() {}

    /**
     * Returns a shape object representing the union of the shape object
     * with the one passed as parameter.
     * Only available if php/mapscript is built with GEOS
     * library
     *
     * @param shapeObj $shape
     * @return shapeObj
     */
    final public function union(shapeObj $shape) {}

    /**
     * Returns true if the shape is entirely within the shape2 passed as
     * argument.
     * Else returns false.
     * Only available if php/mapscript is built with GEOS library.
     *
     * @param shapeObj $shape2
     * @return int
     */
    final public function within(shapeObj $shape2) {}
}

/**
 * Instances of styleObj are always embedded inside a `classObj`_ or `labelObj`_.
 */
final class styleObj
{
    /**
     * @var float
     */
    public $angle;

    /**
     * @var int
     */
    public $antialias;

    /**
     * @var colorObj
     */
    public $backgroundcolor;

    /**
     * @var colorObj
     */
    public $color;

    /**
     * @var float
     */
    public $maxsize;

    /**
     * @var float
     */
    public $maxvalue;

    /**
     * @var float
     */
    public $maxwidth;

    /**
     * @var float
     */
    public $minsize;

    /**
     * @var float
     */
    public $minvalue;

    /**
     * @var float
     */
    public $minwidth;

    /**
     * @var int
     */
    public $offsetx;

    /**
     * @var int
     */
    public $offsety;

    /**
     * only supported for the AGG driver
     *
     * @var int
     */
    public $opacity;

    /**
     * @var colorObj
     */
    public $outlinecolor;

    /**
     * @var string
     */
    public $rangeitem;

    /**
     * @var float
     */
    public $size;

    /**
     * @var int
     */
    public $symbol;

    /**
     * @var string
     */
    public $symbolname;

    /**
     * @var float
     */
    public $width;

    /**
     * The second argument 'style' is optional. If given, the new style
     * created will be a copy of the style passed as argument.
     *
     * @param labelObj $label
     * @param styleObj $style
     */
    final public function __construct(labelObj $label, styleObj $style) {}

    /**
     * Old style constructor
     *
     * @param classObj $class
     * @param styleObj $style
     * @return styleObj
     */
    final public function ms_newStyleObj(classObj $class, styleObj $style) {}

    /**
     * Saves the object to a string.  Provides the inverse option for
     * updateFromString.
     *
     * @return string
     */
    final public function convertToString() {}

    /**
     * Free the object properties and break the internal references.
     * Note that you have to unset the php variable to free totally the
     * resources.
     *
     * @return void
     */
    final public function free() {}

    /**
     * Get the attribute binding for a specfiled style property. Returns
     * NULL if there is no binding for this property.
     * .. code-block:: php
     * $oStyle->setbinding(MS_STYLE_BINDING_COLOR, "FIELD_NAME_COLOR");
     * echo $oStyle->getbinding(MS_STYLE_BINDING_COLOR); // FIELD_NAME_COLOR
     *
     * @param mixed $stylebinding
     * @return string
     */
    final public function getBinding($stylebinding) {}

    /**
     * @return string
     */
    final public function getGeomTransform() {}

    /**
     * Remove the attribute binding for a specfiled style property.
     * Added in MapServer 5.0.
     * .. code-block:: php
     * $oStyle->removebinding(MS_STYLE_BINDING_COLOR);
     *
     * @param mixed $stylebinding
     * @return int
     */
    final public function removeBinding($stylebinding) {}

    /**
     * Set object property to a new value.
     *
     * @param string $property_name
     * @param  $new_value
     * @return int
     */
    final public function set($property_name, $new_value) {}

    /**
     * Set the attribute binding for a specfiled style property.
     * Added in MapServer 5.0.
     * .. code-block:: php
     * $oStyle->setbinding(MS_STYLE_BINDING_COLOR, "FIELD_NAME_COLOR");
     * This would bind the color parameter with the data (ie will extract
     * the value of the color from the field called "FIELD_NAME_COLOR"
     *
     * @param mixed $stylebinding
     * @param string $value
     * @return int
     */
    final public function setBinding($stylebinding, $value) {}

    /**
     * @param string $value
     * @return int
     */
    final public function setGeomTransform($value) {}

    /**
     * Update a style from a string snippet. Returns MS_SUCCESS/MS_FAILURE.
     *
     * @param string $snippet
     * @return int
     */
    final public function updateFromString($snippet) {}
}

final class symbolObj
{
    /**
     * @var int
     */
    public $antialias;

    /**
     * @var string
     */
    public $character;

    /**
     * @var int
     */
    public $filled;

    /**
     * @var string
     */
    public $font;

    /**
     * read-only
     *
     * @var string
     */
    public $imagepath;

    /**
     * If set to TRUE, the symbol will be saved
     * inside the mapfile.
     *
     * @var int
     */
    public $inmapfile;

    /**
     * read-only
     *
     * @var int
     */
    public $patternlength;

    /**
     * @var int
     */
    public $position;

    /**
     * @var string
     */
    public $name;

    /**
     * read-only
     *
     * @var int
     */
    public $numpoints;

    /**
     * @var float
     */
    public $sizex;

    /**
     * @var float
     */
    public $sizey;

    /**
     * @var int
     */
    public $transparent;

    /**
     * @var int
     */
    public $transparentcolor;

    /**
     * Creates a new symbol with default values in the symbolist.
     * .. note::
     * Using the new constructor, the symbol is automatically returned. The
     * If a symbol with the same name exists, it (or its id) will be returned.
     * $nId = ms_newSymbolObj($map, "symbol-test");
     * $oSymbol = $map->getSymbolObjectById($nId);
     *
     * @param mapObj $map
     * @param string $symbolname
     */
    final public function __construct(mapObj $map, $symbolname) {}

    /**
     * Old style constructor
     *
     * @param mapObj $map
     * @param string $symbolname
     * @return int
     */
    final public function ms_newSymbolObj(mapObj $map, $symbolname) {}

    /**
     * Free the object properties and break the internal references.
     * Note that you have to unset the php variable to free totally the
     * resources.
     *
     * @return void
     */
    final public function free() {}

    /**
     * Returns an array containing the pattern. If there is no pattern, it
     * returns an empty array.
     *
     * @return array
     */
    final public function getPatternArray() {}

    /**
     * Returns an array containing the points of the symbol. Refer to
     * setpoints to see how the array should be interpreted. If there are no
     * points, it returns an empty array.
     *
     * @return array
     */
    final public function getPointsArray() {}

    /**
     * Set object property to a new value.
     *
     * @param string $property_name
     * @param  $new_value
     * @return int
     */
    final public function set($property_name, $new_value) {}

    /**
     * Loads a pixmap symbol specified by the filename.
     * The file should be of either  Gif or Png format.
     *
     * @param string $filename
     * @return int
     */
    final public function setImagePath($filename) {}

    /**
     * Set the pattern of the symbol (used for dash patterns).
     * Returns MS_SUCCESS/MS_FAILURE.
     *
     * @param array $int
     * @return int
     */
    final public function setPattern(array $int) {}

    /**
     * Set the points of the symbol. Note that the values passed is an
     * array containing the x and y values of the points. Returns
     * MS_SUCCESS/MS_FAILURE.
     * Example:
     * .. code-block:: php
     * $array[0] = 1 # x value of the first point
     * $array[1] = 0 # y values of the first point
     * $array[2] = 1 # x value of the 2nd point
     * ....
     *
     * @param array $double
     * @return int
     */
    final public function setPoints(array $double) {}
}

/**
 * Instances of webObj are always are always embedded inside the `mapObj`_.
 */
final class webObj
{
    /**
     * @var string
     */
    public $browseformat;

    /**
     * read-only
     *
     * @var string
     */
    public $empty;

    /**
     * read-only
     *
     * @var string
     */
    public $error;

    /**
     * read-only
     *
     * @var rectObj
     */
    public $extent;

    /**
     * @var string
     */
    public $footer;

    /**
     * @var string
     */
    public $header;

    /**
     * @var string
     */
    public $imagepath;

    /**
     * @var string
     */
    public $imageurl;

    /**
     * @var string
     */
    public $legendformat;

    /**
     * @var string
     */
    public $log;

    /**
     * @var float
     */
    public $maxscaledenom;

    /**
     * @var string
     */
    public $maxtemplate;

    /**
     * @var hashTableObj
     */
    public $metadata;

    /**
     * @var float
     */
    public $minscaledenom;

    /**
     * @var string
     */
    public $mintemplate;

    /**
     * @var string
     */
    public $queryformat;

    /**
     * @var string
     */
    public $template;

    /**
     * @var string
     */
    public $temppath;

    /**
     * Saves the object to a string.  Provides the inverse option for
     * updateFromString.
     *
     * @return string
     */
    final public function convertToString() {}

    /**
     * Free the object properties and break the internal references.
     * Note that you have to unset the php variable to free totally the
     * resources.
     *
     * @return void
     */
    final public function free() {}

    /**
     * Set object property to a new value.
     *
     * @param string $property_name
     * @param  $new_value
     * @return int
     */
    final public function set($property_name, $new_value) {}

    /**
     * Update a web object from a string snippet. Returns
     * MS_SUCCESS/MS_FAILURE.
     *
     * @param string $snippet
     * @return int
     */
    final public function updateFromString($snippet) {}
}
