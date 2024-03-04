<?php

use JetBrains\PhpStorm\Pure;

/**
 * @link https://php.net/manual/en/class.gmagick.php
 */
class Gmagick
{
    public const COLOR_BLACK = 0;
    public const COLOR_BLUE = 0;
    public const COLOR_CYAN = 0;
    public const COLOR_GREEN = 0;
    public const COLOR_RED = 0;
    public const COLOR_YELLOW = 0;
    public const COLOR_MAGENTA = 0;
    public const COLOR_OPACITY = 0;
    public const COLOR_ALPHA = 0;
    public const COLOR_FUZZ = 0;
    public const GMAGICK_EXTNUM = 0;
    public const COMPOSITE_DEFAULT = 0;
    public const COMPOSITE_UNDEFINED = 0;
    public const COMPOSITE_NO = 0;
    public const COMPOSITE_ADD = 0;
    public const COMPOSITE_ATOP = 0;
    public const COMPOSITE_BUMPMAP = 0;
    public const COMPOSITE_CLEAR = 0;
    public const COMPOSITE_COLORIZE = 0;
    public const COMPOSITE_COPYBLACK = 0;
    public const COMPOSITE_COPYBLUE = 0;
    public const COMPOSITE_COPY = 0;
    public const COMPOSITE_COPYCYAN = 0;
    public const COMPOSITE_COPYGREEN = 0;
    public const COMPOSITE_COPYMAGENTA = 0;
    public const COMPOSITE_COPYOPACITY = 0;
    public const COMPOSITE_COPYRED = 0;
    public const COMPOSITE_COPYYELLOW = 0;
    public const COMPOSITE_DARKEN = 0;
    public const COMPOSITE_DIFFERENCE = 0;
    public const COMPOSITE_DISPLACE = 0;
    public const COMPOSITE_DISSOLVE = 0;
    public const COMPOSITE_HUE = 0;
    public const COMPOSITE_IN = 0;
    public const COMPOSITE_LIGHTEN = 0;
    public const COMPOSITE_LUMINIZE = 0;
    public const COMPOSITE_MINUS = 0;
    public const COMPOSITE_MODULATE = 0;
    public const COMPOSITE_MULTIPLY = 0;
    public const COMPOSITE_OUT = 0;
    public const COMPOSITE_OVER = 0;
    public const COMPOSITE_OVERLAY = 0;
    public const COMPOSITE_PLUS = 0;
    public const COMPOSITE_REPLACE = 0;
    public const COMPOSITE_SATURATE = 0;
    public const COMPOSITE_SCREEN = 0;
    public const COMPOSITE_SUBTRACT = 0;
    public const COMPOSITE_THRESHOLD = 0;
    public const COMPOSITE_XOR = 0;
    public const COMPOSITE_DIVIDE = 0;
    public const COMPOSITE_HARDLIGHT = 0;
    public const COMPOSITE_EXCLUSION = 0;
    public const COMPOSITE_COLORDODGE = 0;
    public const COMPOSITE_COLORBURN = 0;
    public const COMPOSITE_SOFTLIGHT = 0;
    public const COMPOSITE_LINEARBURN = 0;
    public const COMPOSITE_LINEARDODGE = 0;
    public const COMPOSITE_LINEARLIGHT = 0;
    public const COMPOSITE_VIVIDLIGHT = 0;
    public const COMPOSITE_PINLIGHT = 0;
    public const COMPOSITE_HARDMIX = 0;
    public const MONTAGEMODE_FRAME = 0;
    public const MONTAGEMODE_UNFRAME = 0;
    public const MONTAGEMODE_CONCATENATE = 0;
    public const STYLE_NORMAL = 0;
    public const STYLE_ITALIC = 0;
    public const STYLE_OBLIQUE = 0;
    public const STYLE_ANY = 0;
    public const FILTER_UNDEFINED = 0;
    public const FILTER_POINT = 0;
    public const FILTER_BOX = 0;
    public const FILTER_TRIANGLE = 0;
    public const FILTER_HERMITE = 0;
    public const FILTER_HANNING = 0;
    public const FILTER_HAMMING = 0;
    public const FILTER_BLACKMAN = 0;
    public const FILTER_GAUSSIAN = 0;
    public const FILTER_QUADRATIC = 0;
    public const FILTER_CUBIC = 0;
    public const FILTER_CATROM = 0;
    public const FILTER_MITCHELL = 0;
    public const FILTER_LANCZOS = 0;
    public const FILTER_BESSEL = 0;
    public const FILTER_SINC = 0;
    public const IMGTYPE_UNDEFINED = 0;
    public const IMGTYPE_BILEVEL = 0;
    public const IMGTYPE_GRAYSCALE = 0;
    public const IMGTYPE_GRAYSCALEMATTE = 0;
    public const IMGTYPE_PALETTE = 0;
    public const IMGTYPE_PALETTEMATTE = 0;
    public const IMGTYPE_TRUECOLOR = 0;
    public const IMGTYPE_TRUECOLORMATTE = 0;
    public const IMGTYPE_COLORSEPARATION = 0;
    public const IMGTYPE_COLORSEPARATIONMATTE = 0;
    public const IMGTYPE_OPTIMIZE = 0;
    public const RESOLUTION_UNDEFINED = 0;
    public const RESOLUTION_PIXELSPERINCH = 0;
    public const RESOLUTION_PIXELSPERCENTIMETER = 0;
    public const COMPRESSION_UNDEFINED = 0;
    public const COMPRESSION_NO = 0;
    public const COMPRESSION_BZIP = 0;
    public const COMPRESSION_FAX = 0;
    public const COMPRESSION_GROUP4 = 0;
    public const COMPRESSION_JPEG = 0;
    public const COMPRESSION_LOSSLESSJPEG = 0;
    public const COMPRESSION_LZW = 0;
    public const COMPRESSION_RLE = 0;
    public const COMPRESSION_ZIP = 0;
    public const COMPRESSION_GROUP3 = 0;
    public const COMPRESSION_LZMA = 0;
    public const COMPRESSION_JPEG2000 = 0;
    public const COMPRESSION_JBIG1 = 0;
    public const COMPRESSION_JBIG2 = 0;
    public const INTERLACE_NONE = 0;
    public const INTERLACE_LINE = 0;
    public const INTERLACE_PLANE = 0;
    public const INTERLACE_PARTITION = 0;
    public const PAINT_POINT = 0;
    public const PAINT_REPLACE = 0;
    public const PAINT_FLOODFILL = 0;
    public const PAINT_FILLTOBORDER = 0;
    public const PAINT_RESET = 0;
    public const GRAVITY_NORTHWEST = 0;
    public const GRAVITY_NORTH = 0;
    public const GRAVITY_NORTHEAST = 0;
    public const GRAVITY_WEST = 0;
    public const GRAVITY_CENTER = 0;
    public const GRAVITY_EAST = 0;
    public const GRAVITY_SOUTHWEST = 0;
    public const GRAVITY_SOUTH = 0;
    public const GRAVITY_SOUTHEAST = 0;
    public const STRETCH_NORMAL = 0;
    public const STRETCH_ULTRACONDENSED = 0;
    public const STRETCH_CONDENSED = 0;
    public const STRETCH_SEMICONDENSED = 0;
    public const STRETCH_SEMIEXPANDED = 0;
    public const STRETCH_EXPANDED = 0;
    public const STRETCH_EXTRAEXPANDED = 0;
    public const STRETCH_ULTRAEXPANDED = 0;
    public const STRETCH_ANY = 0;
    public const STRETCH_EXTRACONDENSED = 0;
    public const ALIGN_UNDEFINED = 0;
    public const ALIGN_LEFT = 0;
    public const ALIGN_CENTER = 0;
    public const ALIGN_RIGHT = 0;
    public const DECORATION_NO = 0;
    public const DECORATION_UNDERLINE = 0;
    public const DECORATION_OVERLINE = 0;
    public const DECORATION_LINETROUGH = 0;
    public const NOISE_UNIFORM = 0;
    public const NOISE_GAUSSIAN = 0;
    public const NOISE_MULTIPLICATIVEGAUSSIAN = 0;
    public const NOISE_IMPULSE = 0;
    public const NOISE_LAPLACIAN = 0;
    public const NOISE_POISSON = 0;
    public const NOISE_RANDOM = 0;
    public const CHANNEL_UNDEFINED = 0;
    public const CHANNEL_RED = 0;
    public const CHANNEL_GRAY = 0;
    public const CHANNEL_CYAN = 0;
    public const CHANNEL_GREEN = 0;
    public const CHANNEL_MAGENTA = 0;
    public const CHANNEL_BLUE = 0;
    public const CHANNEL_YELLOW = 0;
    public const CHANNEL_OPACITY = 0;
    public const CHANNEL_MATTE = 0;
    public const CHANNEL_BLACK = 0;
    public const CHANNEL_INDEX = 0;
    public const CHANNEL_ALL = 0;
    public const CHANNEL_DEFAULT = 0;
    public const METRIC_UNDEFINED = 0;
    public const METRIC_MEANABSOLUTEERROR = 0;
    public const METRIC_MEANSQUAREERROR = 0;
    public const METRIC_PEAKABSOLUTEERROR = 0;
    public const METRIC_PEAKSIGNALTONOISERATIO = 0;
    public const METRIC_ROOTMEANSQUAREDERROR = 0;
    public const PIXEL_CHAR = 0;
    public const PIXEL_DOUBLE = 0;
    public const PIXEL_FLOAT = 0;
    public const PIXEL_INTEGER = 0;
    public const PIXEL_LONG = 0;
    public const PIXEL_SHORT = 0;
    public const COLORSPACE_UNDEFINED = 0;
    public const COLORSPACE_RGB = 0;
    public const COLORSPACE_GRAY = 0;
    public const COLORSPACE_TRANSPARENT = 0;
    public const COLORSPACE_OHTA = 0;
    public const COLORSPACE_LAB = 0;
    public const COLORSPACE_XYZ = 0;
    public const COLORSPACE_YCBCR = 0;
    public const COLORSPACE_YCC = 0;
    public const COLORSPACE_YIQ = 0;
    public const COLORSPACE_YPBPR = 0;
    public const COLORSPACE_YUV = 0;
    public const COLORSPACE_CMYK = 0;
    public const COLORSPACE_SRGB = 0;
    public const COLORSPACE_HSL = 0;
    public const COLORSPACE_HWB = 0;
    public const COLORSPACE_REC601LUMA = 0;
    public const COLORSPACE_REC709LUMA = 0;
    public const COLORSPACE_CINEONLOGRGB = 0;
    public const COLORSPACE_REC601YCBCR = 0;
    public const COLORSPACE_REC709YCBCR = 0;
    public const VIRTUALPIXELMETHOD_UNDEFINED = 0;
    public const VIRTUALPIXELMETHOD_CONSTANT = 0;
    public const VIRTUALPIXELMETHOD_EDGE = 0;
    public const VIRTUALPIXELMETHOD_MIRROR = 0;
    public const VIRTUALPIXELMETHOD_TILE = 0;
    public const PREVIEW_UNDEFINED = 0;
    public const PREVIEW_ROTATE = 0;
    public const PREVIEW_SHEAR = 0;
    public const PREVIEW_ROLL = 0;
    public const PREVIEW_HUE = 0;
    public const PREVIEW_SATURATION = 0;
    public const PREVIEW_BRIGHTNESS = 0;
    public const PREVIEW_GAMMA = 0;
    public const PREVIEW_SPIFF = 0;
    public const PREVIEW_DULL = 0;
    public const PREVIEW_GRAYSCALE = 0;
    public const PREVIEW_QUANTIZE = 0;
    public const PREVIEW_DESPECKLE = 0;
    public const PREVIEW_REDUCENOISE = 0;
    public const PREVIEW_ADDNOISE = 0;
    public const PREVIEW_SHARPEN = 0;
    public const PREVIEW_BLUR = 0;
    public const PREVIEW_THRESHOLD = 0;
    public const PREVIEW_EDGEDETECT = 0;
    public const PREVIEW_SPREAD = 0;
    public const PREVIEW_SOLARIZE = 0;
    public const PREVIEW_SHADE = 0;
    public const PREVIEW_RAISE = 0;
    public const PREVIEW_SEGMENT = 0;
    public const PREVIEW_SWIRL = 0;
    public const PREVIEW_IMPLODE = 0;
    public const PREVIEW_WAVE = 0;
    public const PREVIEW_OILPAINT = 0;
    public const PREVIEW_CHARCOALDRAWING = 0;
    public const PREVIEW_JPEG = 0;
    public const RENDERINGINTENT_UNDEFINED = 0;
    public const RENDERINGINTENT_SATURATION = 0;
    public const RENDERINGINTENT_PERCEPTUAL = 0;
    public const RENDERINGINTENT_ABSOLUTE = 0;
    public const RENDERINGINTENT_RELATIVE = 0;
    public const INTERLACE_UNDEFINED = 0;
    public const INTERLACE_NO = 0;
    public const FILLRULE_UNDEFINED = 0;
    public const FILLRULE_EVENODD = 0;
    public const FILLRULE_NONZERO = 0;
    public const PATHUNITS_USERSPACE = 0;
    public const PATHUNITS_USERSPACEONUSE = 0;
    public const PATHUNITS_OBJECTBOUNDINGBOX = 0;
    public const LINECAP_UNDEFINED = 0;
    public const LINECAP_BUTT = 0;
    public const LINECAP_ROUND = 0;
    public const LINECAP_SQUARE = 0;
    public const LINEJOIN_UNDEFINED = 0;
    public const LINEJOIN_MITER = 0;
    public const LINEJOIN_ROUND = 0;
    public const LINEJOIN_BEVEL = 0;
    public const RESOURCETYPE_UNDEFINED = 0;
    public const RESOURCETYPE_AREA = 0;
    public const RESOURCETYPE_DISK = 0;
    public const RESOURCETYPE_FILE = 0;
    public const RESOURCETYPE_MAP = 0;
    public const RESOURCETYPE_MEMORY = 0;
    public const RESOURCETYPE_PIXELS = 0;
    public const RESOURCETYPE_THREADS = 0;
    public const RESOURCETYPE_WIDTH = 0;
    public const RESOURCETYPE_HEIGHT = 0;
    public const DISPOSE_UNDEFINED = 0;
    public const DISPOSE_NONE = 0;
    public const DISPOSE_BACKGROUND = 0;
    public const DISPOSE_PREVIOUS = 0;
    public const ORIENTATION_UNDEFINED = 0;
    public const ORIENTATION_TOPLEFT = 0;
    public const ORIENTATION_TOPRIGHT = 0;
    public const ORIENTATION_BOTTOMRIGHT = 0;
    public const ORIENTATION_BOTTOMLEFT = 0;
    public const ORIENTATION_LEFTTOP = 0;
    public const ORIENTATION_RIGHTTOP = 0;
    public const ORIENTATION_RIGHTBOTTOM = 0;
    public const ORIENTATION_LEFTBOTTOM = 0;
    public const QUANTUM_DEPTH = 0;
    public const QUANTUM = 0;
    public const VERSION_LIB = 0;
    public const VERSION_NUM = 0;
    public const VERSION_TXT = '';

    /**
     * The Gmagick constructor.
     *
     * @link https://php.net/manual/en/gmagick.construct.php
     *
     * @param string $filename [optional] The path to an image to load or array of paths.
     */
    public function __construct($filename = null) {}

    /**
     * Adds new image to Gmagick object from the current position of the source object.
     * After the operation iterator position is moved at the end of the list.
     *
     * @link https://php.net/manual/en/gmagick.addimage.php
     *
     * @param Gmagick $Gmagick The source Gmagick object.
     *
     * @return Gmagick The Gmagick object with image added.
     *
     * @throws GmagickException On error.
     */
    public function addimage($Gmagick) {}

    /**
     * Adds random noise to the image.
     *
     * @link https://php.net/manual/en/gmagick.addnoiseimage.php
     *
     * @param int $NOISE The type of the noise. One of the Gmagick::NOISE_* constants.
     *
     * @return Gmagick The Gmagick object with noise added.
     *
     * @throws GmagickException On error.
     */
    public function addnoiseimage($NOISE) {}

    /**
     * Annotates an image with text.
     *
     * @link https://php.net/manual/en/gmagick.annotateimage.php
     *
     * @param GmagickDraw $GmagickDraw The GmagickDraw object that contains settings for drawing the text.
     * @param float       $x           Horizontal offset in pixels to the left of text.
     * @param float       $y           Vertical offset in pixels to the baseline of text.
     * @param float       $angle       The angle at which to write the text.
     * @param string      $text        The string to draw.
     *
     * @return Gmagick The Gmagick object with annotation made.
     *
     * @throws GmagickException On error.
     */
    public function annotateimage($GmagickDraw, $x, $y, $angle, $text) {}

    /**
     * Adds blur filter to image.
     *
     * @link https://php.net/manual/en/gmagick.blurimage.php
     *
     * @param float $radius  Blur radius.
     * @param float $sigma   Standard deviation
     * @param int   $channel [optional]
     *
     * @return Gmagick The blurred Gmagick object.
     *
     * @throws GmagickException On error.
     */
    public function blurimage($radius, $sigma, $channel = null) {}

    /**
     * Surrounds the image with a border of the color defined by the bordercolor GmagickPixel object or a color string.
     *
     * @link https://php.net/manual/en/gmagick.borderimage.php
     *
     * @param GmagickPixel $color  GmagickPixel object or a string containing the border color.
     * @param int          $width  Border width.
     * @param int          $height Border height.
     *
     * @return Gmagick The Gmagick object with border defined.
     *
     * @throws GmagickException On error.
     */
    public function borderimage($color, $width, $height) {}

    /**
     * Simulates a charcoal drawing.
     *
     * @link https://php.net/manual/en/gmagick.charcoalimage.php
     *
     * @param float $radius The radius of the Gaussian, in pixels, not counting the center pixel.
     * @param float $sigma  The standard deviation of the Gaussian, in pixels.
     *
     * @return Gmagick The Gmagick object with charcoal simulation.
     *
     * @throws GmagickException On error.
     */
    public function charcoalimage($radius, $sigma) {}

    /**
     * Removes a region of an image and collapses the image to occupy the removed portion.
     *
     * @link https://php.net/manual/en/gmagick.chopimage.php
     *
     * @param int $width  Width of the chopped area.
     * @param int $height Height of the chopped area.
     * @param int $x      X origo of the chopped area.
     * @param int $y      Y origo of the chopped area.
     *
     * @return Gmagick The chopped Gmagick object.
     *
     * @throws GmagickException On error.
     */
    public function chopimage($width, $height, $x, $y) {}

    /**
     * Clears all resources associated to Gmagick object.
     *
     * @link https://php.net/manual/en/gmagick.clear.php
     *
     * @return Gmagick The cleared Gmagick object.
     *
     * @throws GmagickException On error.
     */
    public function clear() {}

    /**
     * Adds a comment to your image.
     *
     * @link https://php.net/manual/en/gmagick.commentimage.php
     *
     * @param string $comment The comment to add.
     *
     * @return Gmagick The Gmagick object with comment added.
     *
     * @throws GmagickException On error.
     */
    public function commentimage($comment) {}

    /**
     * Composite one image onto another at the specified offset.
     *
     * @link https://php.net/manual/en/gmagick.compositeimage.php
     *
     * @param Gmagick $source  Gmagick object which holds the composite image.
     * @param int     $COMPOSE Composite operator.
     * @param int     $x       The column offset of the composited image.
     * @param int     $y       The row offset of the composited image.
     *
     * @return Gmagick The Gmagick object with compositions.
     *
     * @throws GmagickException On error.
     */
    public function compositeimage($source, $COMPOSE, $x, $y) {}

    /**
     * Extracts a region of the image.
     *
     * @link https://php.net/manual/en/gmagick.cropimage.php
     *
     * @param int $width  The width of the crop.
     * @param int $height The height of the crop.
     * @param int $x      The X coordinate of the cropped region's top left corner.
     * @param int $y      The Y coordinate of the cropped region's top left corner.
     *
     * @return Gmagick The cropped Gmagick object.
     *
     * @throws GmagickException On error.
     */
    public function cropimage($width, $height, $x, $y) {}

    /**
     * Creates a fixed size thumbnail by first scaling the image down and cropping a specified area from the center.
     *
     * @link https://php.net/manual/en/gmagick.cropthumbnailimage.php
     *
     * @param int $width  The width of the thumbnail.
     * @param int $height The Height of the thumbnail.
     *
     * @return Gmagick The cropped Gmagick object.
     *
     * @throws GmagickException On error.
     */
    public function cropthumbnailimage($width, $height) {}

    /**
     * Returns reference to the current gmagick object with image pointer at the correct sequence.
     *
     * @link https://php.net/manual/en/gmagick.current.php
     *
     * @return Gmagick Returns self on success.
     *
     * @throws GmagickException On error.
     */
    public function current() {}

    /**
     * Displaces an image's colormap by a given number of positions.
     * If you cycle the colormap a number of times you can produce a psychedelic effect.
     *
     * @link https://php.net/manual/en/gmagick.cyclecolormapimage.php
     *
     * @param int $displace The amount to displace the colormap.
     *
     * @return Gmagick Returns self on success.
     *
     * @throws GmagickException On error.
     */
    public function cyclecolormapimage($displace) {}

    /**
     * Compares each image with the next in a sequence.
     * Returns the maximum bounding region of any pixel differences it discovers.
     *
     * @link https://php.net/manual/en/gmagick.deconstructimages.php
     *
     * @return Gmagick Returns a new Gmagick object on success.
     *
     * @throws GmagickException On error.
     */
    public function deconstructimages() {}

    /**
     * Reduces the speckle noise in an image while preserving the edges of the original image.
     *
     * @link https://php.net/manual/en/gmagick.despeckleimage.php
     *
     * @return Gmagick The despeckled Gmagick object on success.
     *
     * @throws GmagickException On error.
     */
    public function despeckleimage() {}

    /**
     * Destroys the Gmagick object and frees all resources associated with it.
     *
     * @link https://php.net/manual/en/gmagick.destroy.php
     *
     * @return bool Returns TRUE on success.
     *
     * @throws GmagickException On error.
     */
    public function destroy() {}

    /**
     * Renders the GmagickDraw object on the current image.
     *
     * @link https://php.net/manual/en/gmagick.drawimage.php
     *
     * @param GmagickDraw $GmagickDraw The drawing operations to render on the image.
     *
     * @return Gmagick The drawn Gmagick object.
     *
     * @throws GmagickException On error.
     */
    public function drawimage($GmagickDraw) {}

    /**
     * Enhance edges within the image with a convolution filter of the given radius.
     * Use radius 0 and it will be auto-selected.
     *
     * @link https://php.net/manual/en/gmagick.edgeimage.php
     *
     * @param float $radius The radius of the operation.
     *
     * @return Gmagick The Gmagick object with edges enhanced.
     *
     * @throws GmagickException On error.
     */
    public function edgeimage($radius) {}

    /**
     * Returns a grayscale image with a three-dimensional effect.
     * We convolve the image with a Gaussian operator of the given radius and standard deviation (sigma).
     * For reasonable results, radius should be larger than sigma.
     * Use a radius of 0 and it will choose a suitable radius for you.
     *
     * @link https://php.net/manual/en/gmagick.embossimage.php
     *
     * @param float $radius The radius of the effect.
     * @param float $sigma  The sigma of the effect.
     *
     * @return Gmagick The embossed Gmagick object.
     *
     * @throws GmagickException On error.
     */
    public function embossimage($radius, $sigma) {}

    /**
     * Applies a digital filter that improves the quality of a noisy image.
     *
     * @link https://php.net/manual/en/gmagick.enhanceimage.php
     *
     * @return Gmagick The enhanced Gmagick object.
     *
     * @throws GmagickException On error.
     */
    public function enhanceimage() {}

    /**
     * Equalizes the image histogram.
     *
     * @link https://php.net/manual/en/gmagick.equalizeimage.php
     *
     * @return Gmagick The equalized Gmagick object.
     *
     * @throws GmagickException On error.
     */
    public function equalizeimage() {}

    /**
     * Creates a vertical mirror image by reflecting the pixels around the central x-axis.
     *
     * @link https://php.net/manual/en/gmagick.flipimage.php
     *
     * @return Gmagick The flipped Gmagick object.
     *
     * @throws GmagickException On error.
     */
    public function flipimage() {}

    /**
     * Creates a horizontal mirror image by reflecting the pixels around the central y-axis.
     *
     * @link https://php.net/manual/en/gmagick.flopimage.php
     *
     * @return Gmagick The flopped Gmagick object.
     *
     * @throws GmagickException On error.
     */
    public function flopimage() {}

    /**
     * Adds a simulated three-dimensional border around the image.
     * The width and height specify the border width of the vertical and horizontal sides of the frame.
     * The inner and outer bevels indicate the width of the inner and outer shadows of the frame.
     *
     * @link https://php.net/manual/en/gmagick.frameimage.php
     *
     * @param GmagickPixel $color       GmagickPixel object or a float representing the matte color.
     * @param int          $width       The width of the border.
     * @param int          $height      The height of the border.
     * @param int          $inner_bevel The inner bevel width.
     * @param int          $outer_bevel The outer bevel width.
     *
     * @return Gmagick The framed Gmagick object.
     *
     * @throws GmagickException On error.
     */
    public function frameimage($color, $width, $height, $inner_bevel, $outer_bevel) {}

    /**
     * Gamma-corrects an image.
     * The same image viewed on different devices will have perceptual differences in the way the image's intensities
     * are represented on the screen. Specify individual gamma levels for the red, green, and blue channels,
     * or adjust all three with the gamma parameter. Values typically range from 0.8 to 2.3.
     *
     * @link https://php.net/manual/en/gmagick.gammaimage.php
     *
     * @param float $gamma The amount of gamma-correction.
     *
     * @return Gmagick The gamma corrected Gmagick object.
     *
     * @throws GmagickException On error.
     */
    public function gammaimage($gamma) {}

    /**
     * Returns the GraphicsMagick API copyright as a string.
     *
     * @link https://php.net/manual/en/gmagick.getcopyright.php
     *
     * @return string Returns a string containing the copyright notice of GraphicsMagick and Magickwand C API.
     *
     * @throws GmagickException On error.
     */
    #[Pure]
    public function getcopyright() {}

    /**
     * Returns the filename associated with an image sequence.
     *
     * @link https://php.net/manual/en/gmagick.getfilename.php
     *
     * @return string Returns a string on success.
     *
     * @throws GmagickException On error.
     */
    #[Pure]
    public function getfilename() {}

    /**
     * Returns the image background color.
     *
     * @link https://php.net/manual/en/gmagick.getimagebackgroundcolor.php
     *
     * @return GmagickPixel Returns a GmagickPixel set to the background color of the image.
     *
     * @throws GmagickException On error.
     */
    #[Pure]
    public function getimagebackgroundcolor() {}

    /**
     * Returns the chromaticity blue primary point for the image.
     *
     * @link https://php.net/manual/en/gmagick.getimageblueprimary.php
     *
     * @return array Array consisting of "x" and "y" coordinates of point.
     *
     * @throws GmagickException On error.
     */
    #[Pure]
    public function getimageblueprimary() {}

    /**
     * Returns the image border color.
     *
     * @link https://php.net/manual/en/gmagick.getimagebordercolor.php
     *
     * @return GmagickPixel GmagickPixel object representing the color of the border.
     *
     * @throws GmagickException On error.
     */
    #[Pure]
    public function getimagebordercolor() {}

    /**
     * Gets the depth for a particular image channel.
     *
     * @link https://php.net/manual/en/gmagick.getimagechanneldepth.php
     *
     * @param int $channel_type
     *
     * @return int Depth of image channel.
     *
     * @throws GmagickException On error.
     */
    #[Pure]
    public function getimagechanneldepth($channel_type) {}

    /**
     * Returns the color of the specified colormap index.
     *
     * @link https://php.net/manual/en/gmagick.getimagecolors.php
     *
     * @return int The number of colors in image.
     *
     * @throws GmagickException On error
     */
    #[Pure]
    public function getimagecolors() {}

    /**
     * Gets the image colorspace.
     *
     * @link https://php.net/manual/en/gmagick.getimagecolorspace.php
     *
     * @return int Colorspace
     *
     * @throws GmagickException On error.
     */
    #[Pure]
    public function getimagecolorspace() {}

    /**
     * Returns the composite operator associated with the image.
     *
     * @link https://php.net/manual/en/gmagick.getimagecompose.php
     *
     * @return int Returns the composite operator associated with the image.
     *
     * @throws GmagickException On error.
     */
    #[Pure]
    public function getimagecompose() {}

    /**
     * Gets the image delay.
     *
     * @link https://php.net/manual/en/gmagick.getimagedelay.php
     *
     * @return int Returns the composite operator associated with the image.
     *
     * @throws GmagickException On error.
     */
    #[Pure]
    public function getimagedelay() {}

    /**
     * Gets the depth of the image.
     *
     * @link https://php.net/manual/en/gmagick.getimagedepth.php
     *
     * @return int Image depth.
     *
     * @throws GmagickException On error.
     */
    #[Pure]
    public function getimagedepth() {}

    /**
     * Gets the image disposal method.
     *
     * @link https://php.net/manual/en/gmagick.getimagedispose.php
     *
     * @return int Returns the dispose method on success.
     *
     * @throws GmagickException On error.
     */
    #[Pure]
    public function getimagedispose() {}

    /**
     * Gets the extrema for the image.
     *
     * @link https://php.net/manual/en/gmagick.getimageextrema.php
     *
     * @return array Returns an associative array with the keys "min" and "max".
     *
     * @throws GmagickException On error.
     */
    #[Pure]
    public function getimageextrema() {}

    /**
     * Returns the filename of a particular image in a sequence.
     *
     * @link https://php.net/manual/en/gmagick.getimagefilename.php
     *
     * @return string Returns a string with the filename of the image
     *
     * @throws GmagickException On error.
     */
    #[Pure]
    public function getimagefilename() {}

    /**
     * Returns the format of a particular image in a sequence.
     *
     * @link https://php.net/manual/en/gmagick.getimageformat.php
     *
     * @return string Returns a string containing the image format on success.
     *
     * @throws GmagickException On error.
     */
    #[Pure]
    public function getimageformat() {}

    /**
     * Gets the image gamma.
     *
     * @link https://php.net/manual/en/gmagick.getimagegamma.php
     *
     * @return float Returns the image gamma on success.
     *
     * @throws GmagickException On error.
     */
    #[Pure]
    public function getimagegamma() {}

    /**
     * Returns the chromaticy green primary point.
     *
     * @link https://php.net/manual/en/gmagick.getimagegreenprimary.php
     *
     * @return array Returns an array with the keys "x" and "y" on success.
     *
     * @throws GmagickException On error.
     */
    #[Pure]
    public function getimagegreenprimary() {}

    /**
     * Returns the image height.
     *
     * @link https://php.net/manual/en/gmagick.getimageheight.php
     *
     * @return int Returns the image height in pixels.
     *
     * @throws GmagickException On error.
     */
    #[Pure]
    public function getimageheight() {}

    /**
     * Gets the image histogram.
     *
     * @link https://php.net/manual/en/gmagick.getimagehistogram.php
     *
     * @return array Returns the image histogram as an array of GmagickPixel objects.
     *
     * @throws GmagickException On error.
     */
    #[Pure]
    public function getimagehistogram() {}

    /**
     * Returns the index of the current active image within the Gmagick object.
     *
     * @link https://php.net/manual/en/gmagick.getimageindex.php
     *
     * @return int Index of current active image.
     *
     * @throws GmagickException On error.
     */
    #[Pure]
    public function getimageindex() {}

    /**
     * Gets the image interlace scheme.
     *
     * @link https://php.net/manual/en/gmagick.getimageinterlacescheme.php
     *
     * @return int Returns the interlace scheme as an integer on success.
     *
     * @throws GmagickException On error.
     */
    #[Pure]
    public function getimageinterlacescheme() {}

    /**
     * Gets the image iterations.
     *
     * @link https://php.net/manual/en/gmagick.getimageiterations.php
     *
     * @return int Returns the image iterations as an integer.
     *
     * @throws GmagickException On error.
     */
    #[Pure]
    public function getimageiterations() {}

    /**
     * Checks if the image has a matte channel.
     *
     * @link https://php.net/manual/en/gmagick.getimagematte.php
     *
     * @return bool Returns TRUE if the image has a matte channel, otherwise FALSE.
     *
     * @throws GmagickException On error.
     */
    #[Pure]
    public function getimagematte() {}

    /**
     * Returns the image matte color.
     *
     * @link https://php.net/manual/en/gmagick.getimagemattecolor.php
     *
     * @return GmagickPixel Returns GmagickPixel object on success.
     *
     * @throws GmagickException On error.
     */
    #[Pure]
    public function getimagemattecolor() {}

    /**
     * Returns the named image profile.
     *
     * @link https://php.net/manual/en/gmagick.getimageprofile.php
     *
     * @param string $name
     *
     * @return string Returns a string containing the image profile.
     *
     * @throws GmagickException On error.
     */
    #[Pure]
    public function getimageprofile($name) {}

    /**
     * Returns the chromaticity red primary point.
     *
     * @link https://php.net/manual/en/gmagick.getimageredprimary.php
     *
     * @return array Returns the chromaticity red primary point as an array with the keys "x" and "y".
     *
     * @throws GmagickException On error.
     */
    #[Pure]
    public function getimageredprimary() {}

    /**
     * Gets the image rendering intent.
     *
     * @link https://php.net/manual/en/gmagick.getimagerenderingintent.php
     *
     * @return int Extracts a region of the image and returns it as a a new wand.
     *
     * @throws GmagickException On error.
     */
    #[Pure]
    public function getimagerenderingintent() {}

    /**
     * Gets the image X and Y resolution.
     *
     * @link https://php.net/manual/en/gmagick.getimageresolution.php
     *
     * @return array Returns the resolution as an array.
     *
     * @throws GmagickException On error.
     */
    #[Pure]
    public function getimageresolution() {}

    /**
     * Gets the image scene.
     *
     * @link https://php.net/manual/en/gmagick.getimagescene.php
     *
     * @return int Returns the image scene.
     *
     * @throws GmagickException On error.
     */
    #[Pure]
    public function getimagescene() {}

    /**
     * Generates an SHA-256 message digest for the image pixel stream.
     *
     * @link https://php.net/manual/en/gmagick.getimagesignature.php
     *
     * @return string Returns a string containing the SHA-256 hash of the file.
     *
     * @throws GmagickException On error.
     */
    #[Pure]
    public function getimagesignature() {}

    /**
     * Gets the potential image type.
     *
     * @link https://php.net/manual/en/gmagick.getimagetype.php
     *
     * @return int Returns the potential image type.
     *
     * @throws GmagickException On error.
     */
    #[Pure]
    public function getimagetype() {}

    /**
     * Gets the image units of resolution.
     *
     * @link https://php.net/manual/en/gmagick.getimageunits.php
     *
     * @return int Returns the image units of resolution.
     */
    #[Pure]
    public function getimageunits() {}

    /**
     * Returns the chromaticity white point.
     *
     * @link https://php.net/manual/en/gmagick.getimagewhitepoint.php
     *
     * @return array Returns the chromaticity white point as an associative array with the keys "x" and "y".
     *
     * @throws GmagickException On error.
     */
    #[Pure]
    public function getimagewhitepoint() {}

    /**
     * Returns the width of the image.
     *
     * @link https://php.net/manual/en/gmagick.getimagewidth.php
     *
     * @return int Returns the image width.
     *
     * @throws GmagickException On error.
     */
    #[Pure]
    public function getimagewidth() {}

    /**
     * Returns the GraphicsMagick package name.
     *
     * @link https://php.net/manual/en/gmagick.getpackagename.php
     *
     * @return string Returns the GraphicsMagick package name as a string.
     *
     * @throws GmagickException On error.
     */
    #[Pure]
    public function getpackagename() {}

    /**
     * Returns the Gmagick quantum depth.
     *
     * @link https://php.net/manual/en/gmagick.getquantumdepth.php
     *
     * @return array Returns the Gmagick quantum depth.
     *
     * @throws GmagickException On error.
     */
    #[Pure]
    public function getquantumdepth() {}

    /**
     * Returns the GraphicsMagick release date as a string.
     *
     * @link https://php.net/manual/en/gmagick.getreleasedate.php
     *
     * @return string Returns the GraphicsMagick release date as a string.
     *
     * @throws GmagickException On error.
     */
    #[Pure]
    public function getreleasedate() {}

    /**
     * Gets the horizontal and vertical sampling factor.
     *
     * @link https://php.net/manual/en/gmagick.getsamplingfactors.php
     *
     * @return array Returns an associative array with the horizontal and vertical sampling factors of the image.
     *
     * @throws GmagickException On error.
     */
    #[Pure]
    public function getsamplingfactors() {}

    /**
     * Returns the size associated with the Gmagick object.
     *
     * @link https://php.net/manual/en/gmagick.getsize.php
     *
     * @return array Returns the size associated with the Gmagick object as an array with the keys "columns" and "rows".
     *
     * @throws GmagickException On error.
     */
    #[Pure]
    public function getsize() {}

    /**
     * Returns the GraphicsMagick API version.
     *
     * @link https://php.net/manual/en/gmagick.getversion.php
     *
     * @return array Returns the GraphicsMagick API version as a string and as a number.
     *
     * @throws GmagickException On error.
     */
    #[Pure]
    public function getversion() {}

    /**
     * Checks if the object has more images.
     *
     * @link https://php.net/manual/en/gmagick.hasnextimage.php
     *
     * @return bool Returns TRUE if the object has more images when traversing the list in the forward direction, returns FALSE if there are none.
     *
     * @throws GmagickException On error.
     */
    public function hasnextimage() {}

    /**
     * Checks if the object has a previous image.
     *
     * @link https://php.net/manual/en/gmagick.haspreviousimage.php
     *
     * @return bool Returns TRUE if the object has more images when traversing the list in the reverse direction, returns FALSE if there are none.
     *
     * @throws GmagickException On error.
     */
    public function haspreviousimage() {}

    /**
     * Creates a new image that is a copy of an existing one with the image pixels "imploded" by the specified percentage.
     *
     * @link https://php.net/manual/en/gmagick.implodeimage.php
     *
     * @param float $radius The radius of the implode.
     *
     * @return mixed Returns imploded Gmagick object.
     *
     * @throws GmagickException On error.
     */
    public function implodeimage($radius) {}

    /**
     * Adds a label to an image.
     *
     * @link https://php.net/manual/en/gmagick.labelimage.php
     *
     * @param string $label The label to add.
     *
     * @return mixed Gmagick with label.
     *
     * @throws GmagickException On error.
     */
    public function labelimage($label) {}

    /**
     * Adjusts the levels of an image.
     *
     * Adjusts the levels of an image by scaling the colors falling between specified white and black points to the
     * full available quantum range. The parameters provided represent the black, mid, and white points. The black
     * point specifies the darkest color in the image. Colors darker than the black point are set to zero. Mid point
     * specifies a gamma correction to apply to the image. White point specifies the lightest color in the image.
     * Colors brighter than the white point are set to the maximum quantum value.
     *
     * @link https://php.net/manual/en/gmagick.levelimage.php
     *
     * @param float $blackPoint The image black point.
     * @param float $gamma      The gamma value.
     * @param float $whitePoint The image white point.
     * @param int   $channel    Provide any channel constant that is valid for your channel mode.
     *                          To apply to more than one channel, combine channeltype constants using bitwise operators.
     *                          Refer to this list of channel constants.
     *
     * @return mixed Gmagick object with image levelled.
     *
     * @throws GmagickException On error.
     */
    public function levelimage($blackPoint, $gamma, $whitePoint, $channel = false) {}

    /**
     * Scales an image proportionally 2x.
     *
     * @link https://php.net/manual/en/gmagick.magnifyimage.php
     *
     * @return mixed Magnified Gmagick object.
     *
     * @throws GmagickException On error.
     */
    public function magnifyimage() {}

    /**
     * Replaces the colors of an image with the closest color from a reference image.
     *
     * @link https://php.net/manual/en/gmagick.mapimage.php
     *
     * @param gmagick $gmagick The reference image.
     * @param bool    $dither  Set this integer value to something other than zero to dither the mapped image.
     *
     * @return Gmagick Gmagick object.
     *
     * @throws GmagickException On error.
     */
    public function mapimage($gmagick, $dither) {}

    /**
     * Applies a digital filter that improves the quality of a noisy image.
     * Each pixel is replaced by the median in a set of neighboring pixels as defined by radius.
     *
     * @link https://php.net/manual/en/gmagick.medianfilterimage.php
     *
     * @param float $radius The radius of the pixel neighborhood.
     *
     * @return void Gmagick object with median filter applied.
     *
     * @throws GmagickException On error.
     */
    public function medianfilterimage($radius) {}

    /**
     * Scales an image proportionally to half its size.
     *
     * @link https://php.net/manual/en/gmagick.minifyimage.php
     *
     * @return Gmagick The Gmagick object on success.
     *
     * @throws GmagickException On error.
     */
    public function minifyimage() {}

    /**
     * Controls the brightness, saturation, and hue.
     *
     * @link https://php.net/manual/en/gmagick.modulateimage.php
     *
     * Lets you control the brightness, saturation, and hue of an image.
     * Hue is the percentage of absolute rotation from the current position.
     * For example 50 results in a counter-clockwise rotation of 90 degrees,
     * 150 results in a clockwise rotation of 90 degrees, with 0 and 200 both resulting in a rotation of 180 degrees.
     *
     * @param float $brightness The percent change in brightness (-100 thru +100).
     * @param float $saturation The percent change in saturation (-100 thru +100).
     * @param float $hue        The percent change in hue (-100 thru +100).
     *
     * @return Gmagick The Gmagick object on success.
     *
     * @throws GmagickException On error.
     */
    public function modulateimage($brightness, $saturation, $hue) {}

    /**
     * Simulates motion blur.
     *
     * We convolve the image with a Gaussian operator of the given radius and standard deviation (sigma).
     * For reasonable results, radius should be larger than sigma.
     * Use a radius of 0 and MotionBlurImage() selects a suitable radius for you.
     * Angle gives the angle of the blurring motion.
     *
     * @link https://php.net/manual/en/gmagick.motionblurimage.php
     *
     * @param float $radius The radius of the Gaussian, in pixels, not counting the center pixel.
     * @param float $sigma  The standard deviation of the Gaussian, in pixels.
     * @param float $angle  Apply the effect along this angle.
     *
     * @return Gmagick The Gmagick object on success.
     *
     * @throws GmagickException On error.
     */
    public function motionblurimage($radius, $sigma, $angle) {}

    /**
     * Creates a new image.
     *
     * @link https://php.net/manual/en/gmagick.newimage.php
     *
     * @param int    $width      Width of the new image
     * @param int    $height     Height of the new image.
     * @param string $background The background color used for this image.
     * @param string $format     [optional] Image format.
     *
     * @return Gmagick The Gmagick object on success.
     *
     * @throws GmagickException On error.
     */
    public function newimage($width, $height, $background, $format = null) {}

    /**
     * Moves to the next image.
     *
     * Associates the next image in the image list with an Gmagick object.
     *
     * @link https://php.net/manual/en/gmagick.nextimage.php
     *
     * @return bool True on success, false on failure.
     */
    public function nextimage() {}

    /**
     * Enhances the contrast of a color image.
     *
     * @link https://php.net/manual/en/gmagick.normalizeimage.php
     *
     * @param int $channel [optional] Identify which channel to normalize.
     *
     * @return Gmagick The Gmagick object on success.
     *
     * @throws GmagickException On error.
     */
    public function normalizeimage($channel = null) {}

    /**
     * Simulates an oil painting.
     *
     * Applies a special effect filter that simulates an oil painting.
     * Each pixel is replaced by the most frequent color occurring in a circular region defined by radius.
     *
     * @link https://php.net/manual/en/gmagick.oilpaintimage.php
     *
     * @param float $radius The radius of the circular neighborhood.
     *
     * @return Gmagick The Gmagick object on success.
     *
     * @throws GmagickException On error.
     */
    public function oilpaintimage($radius) {}

    /**
     * Move to the previous image in the object.
     *
     * Associates the previous image in an image list with the Gmagick object.
     *
     * @link https://php.net/manual/en/gmagick.previousimage.php
     *
     * @return bool True on success, false on failure.
     *
     * @throws GmagickException On error.
     */
    public function previousimage() {}

    /**
     * Adds or removes a profile from an image.
     *
     * Adds or removes a ICC, IPTC, or generic profile from an image.
     * If the profile is NULL, it is removed from the image otherwise added.
     * Use a name of '*' and a profile of NULL to remove all profiles from the image.
     *
     * @link https://php.net/manual/en/gmagick.profileimage.php
     *
     * @param string $name    Name of profile to add or remove: ICC, IPTC, or generic profile.
     * @param string $profile The profile.
     *
     * @return Gmagick The Gmagick object on success.
     *
     * @throws GmagickException On error.
     */
    public function profileimage($name, $profile) {}

    /**
     * Analyzes the colors within a reference image.
     *
     * Analyzes the colors within a reference image and chooses a fixed number of colors to represent the image.
     * The goal of the algorithm is to minimize the color difference between the input and output image while minimizing the processing time.
     *
     * @link https://php.net/manual/en/gmagick.quantizeimage.php
     *
     * @param int  $numColors    The number of colors.
     * @param int  $colorspace   Perform color reduction in this colorspace, typically RGBColorspace.
     * @param int  $treeDepth    Normally, this integer value is zero or one.
     *                           A zero or one tells Quantize to choose a optimal tree depth of Log4(number_colors).
     *                           A tree of this depth generally allows the best representation of the reference image
     *                           with the least amount of memory and the fastest computational speed.
     *                           In some cases, such as an image with low color dispersion (a few number of colors),
     *                           a value other than Log4(number_colors) is required.
     *                           To expand the color tree completely, use a value of 8.
     * @param bool $dither       A value other than zero distributes the difference between an original image and the
     *                           corresponding color reduced algorithm to neighboring pixels along a Hilbert curve.
     * @param bool $measureError A value other than zero measures the difference between the original and quantized
     *                           images. This difference is the total quantization error. The error is computed by
     *                           summing over all pixels in an image the distance squared in RGB space between each
     *                           reference pixel value and its quantized value.
     *
     * @return Gmagick The Gmagick object on success.
     *
     * @throws GmagickException On error.
     */
    public function quantizeimage($numColors, $colorspace, $treeDepth, $dither, $measureError) {}

    /**
     * The quantizeimages purpose.
     *
     * Analyzes the colors within a sequence of images and chooses a fixed number of colors to represent the image.
     * The goal of the algorithm is to minimize the color difference between the input and output image while minimizing the processing time.
     *
     * @link https://php.net/manual/en/gmagick.quantizeimages.php
     *
     * @param int  $numColors    The number of colors.
     * @param int  $colorspace   Perform color reduction in this colorspace, typically RGBColorspace.
     * @param int  $treeDepth    Normally, this integer value is zero or one.
     *                           A zero or one tells Quantize to choose a optimal tree depth of Log4(number_colors).
     *                           A tree of this depth generally allows the best representation of the reference image
     *                           with the least amount of memory and the fastest computational speed.
     *                           In some cases, such as an image with low color dispersion (a few number of colors),
     *                           a value other than Log4(number_colors) is required.
     *                           To expand the color tree completely, use a value of 8.
     * @param bool $dither       A value other than zero distributes the difference between an original image and the
     *                           corresponding color reduced algorithm to neighboring pixels along a Hilbert curve.
     * @param bool $measureError A value other than zero measures the difference between the original and quantized
     *                           images. This difference is the total quantization error. The error is computed by
     *                           summing over all pixels in an image the distance squared in RGB space between eac
     *                           reference pixel value and its quantized value.
     *
     * @return Gmagick The Gmagick object on success.
     *
     * @throws GmagickException On error.
     */
    public function quantizeimages($numColors, $colorspace, $treeDepth, $dither, $measureError) {}

    /**
     * Returns an array representing the font metrics.
     *
     * @link https://php.net/manual/en/gmagick.queryfontmetrics.php
     *
     * @param GmagickDraw $draw
     * @param string      $text
     *
     * @return array
     *
     * @throws GmagickException On error.
     */
    public function queryfontmetrics($draw, $text) {}

    /**
     * Returns fonts supported by Gmagick.
     *
     * @link https://php.net/manual/en/gmagick.queryfonts.php
     *
     * @param string $pattern [optional]
     *
     * @return array
     *
     * @throws GmagickException On error.
     */
    public function queryfonts($pattern = '*') {}

    /**
     * Returns formats supported by Gmagick.
     *
     * @link https://php.net/manual/en/gmagick.queryformats.php
     *
     * @param string $pattern [optional]
     *
     * @return array
     *
     * @throws GmagickException On error.
     */
    public function queryformats($pattern = '*') {}

    /**
     * Radial blurs an image.
     *
     * @link https://php.net/manual/en/gmagick.radialblurimage.php
     *
     * @param float $angle   The angle of the blur in degrees.
     * @param int   $channel [optional] Related channel.
     *
     * @return Gmagick The Gmagick object on success.
     *
     * @throws GmagickException On error.
     */
    public function radialblurimage($angle, $channel = Gmagick::CHANNEL_DEFAULT) {}

    /**
     * Creates a simulated 3d button-like effect.
     *
     * Creates a simulated three-dimensional button-like effect by lightening and darkening the edges of the image.
     * Members width and height of raise_info define the width of the vertical and horizontal edge of the effect.
     *
     * @link https://php.net/manual/en/gmagick.raiseimage.php
     *
     * @param int  $width  Width of the area to raise.
     * @param int  $height Height of the area to raise.
     * @param int  $x      X coordinate.
     * @param int  $y      Y coordinate.
     * @param bool $raise  A value other than zero creates a 3-D raise effect, otherwise it has a lowered effect.
     *
     * @return Gmagick The Gmagick object on success.
     *
     * @throws GmagickException On error.
     */
    public function raiseimage($width, $height, $x, $y, $raise) {}

    /**
     * Reads image from filename.
     *
     * This is an alias for readimage().
     *
     * @link https://php.net/manual/en/gmagick.read.php
     *
     * @param string $filename The image filename.
     *
     * @return Gmagick The Gmagick object on success.
     *
     * @throws GmagickException On error.
     */
    public function read($filename) {}

    /**
     * Reads image from filename.
     *
     * @link https://php.net/manual/en/gmagick.readimage.php
     *
     * @param string $filename The image filename.
     *
     * @return Gmagick The Gmagick object on success.
     *
     * @throws GmagickException On error.
     */
    public function readimage($filename) {}

    /**
     * Reads image from a binary string.
     *
     * @link https://php.net/manual/en/gmagick.readimageblob.php
     *
     * @param string $imageContents Content of image.
     * @param string $filename      [optional] The image filename.
     *
     * @return Gmagick The Gmagick object on success.
     *
     * @throws GmagickException On error.
     */
    public function readimageblob($imageContents, $filename = null) {}

    /**
     * Reads an image or image sequence from an open file descriptor.
     *
     * @link https://php.net/manual/en/gmagick.readimagefile.php
     *
     * @param resource $fp       The file descriptor.
     * @param string   $filename [optional]
     *
     * @return Gmagick The Gmagick object on success.
     *
     * @throws GmagickException On error.
     */
    public function readimagefile($fp, $filename = null) {}

    /**
     * Smooths the contours of an image.
     *
     * Smooths the contours of an image while still preserving edge information.
     * The algorithm works by replacing each pixel with its neighbor closest in value.
     * A neighbor is defined by radius.
     * Use a radius of 0 and Gmagick::reduceNoiseImage() selects a suitable radius for you.
     *
     * @link https://php.net/manual/en/gmagick.reducenoiseimage.php
     *
     * @param float $radius The radius of the pixel neighborhood.
     *
     * @return Gmagick The Gmagick object on success.
     *
     * @throws GmagickException On error.
     */
    public function reducenoiseimage($radius) {}

    /**
     * Removes an image from the image list.
     *
     * @link https://php.net/manual/en/gmagick.removeimage.php
     *
     * @return Gmagick The Gmagick object on success.
     *
     * @throws GmagickException On error.
     */
    public function removeimage() {}

    /**
     * Removes the named image profile and returns it.
     *
     * @link https://php.net/manual/en/gmagick.removeimageprofile.php
     *
     * @param string $name Name of profile to return: ICC, IPTC, or generic profile.
     *
     * @return string The named profile.
     *
     * @throws GmagickException On error.
     */
    public function removeimageprofile($name) {}

    /**
     * Resample image to desired resolution.
     *
     * @link https://php.net/manual/en/gmagick.resampleimage.php
     *
     * @param float $xResolution The new image x resolution.
     * @param float $yResolution The new image y resolution.
     * @param int   $filter      The image filter to use.
     * @param float $blur        The blur factor where larger than 1 is blurry, smaller than 1 is sharp.
     *
     * @return Gmagick The Gmagick object on success.
     *
     * @throws GmagickException On error.
     */
    public function resampleimage($xResolution, $yResolution, $filter, $blur) {}

    /**
     * Scales an image to the desired dimensions with a filter.
     *
     * @link https://php.net/manual/en/gmagick.resizeimage.php
     *
     * @param int   $width  The number of columns in the scaled image.
     * @param int   $height The number of rows in the scaled image.
     * @param int   $filter Image filter to use.
     * @param float $blur   The blur factor where larger than 1 is blurry, lesser than 1 is sharp.
     * @param bool  $fit    [optional]
     *
     * @return Gmagick The Gmagick object on success.
     *
     * @throws GmagickException On error.
     */
    public function resizeimage($width, $height, $filter, $blur, $fit = false) {}

    /**
     * Offsets an image as defined by x and y.
     *
     * @link https://php.net/manual/en/gmagick.rollimage.php
     *
     * @param int $x The x offset.
     * @param int $y The y offset.
     *
     * @return Gmagick The Gmagick object on success.
     *
     * @throws GmagickException On error.
     */
    public function rollimage($x, $y) {}

    /**
     * Rotates an image the specified number of degrees.
     *
     * Empty triangles left over from rotating the image are filled with the background color.
     *
     * @link https://php.net/manual/en/gmagick.rotateimage.php
     *
     * @param mixed $color   The background pixel.
     * @param float $degrees The number of degrees to rotate the image.
     *
     * @return Gmagick The Gmagick object on success.
     *
     * @throws GmagickException On error.
     */
    public function rotateimage($color, $degrees) {}

    /**
     * Scales the size of an image to the given dimensions.
     *
     * The other parameter will be calculated if 0 is passed as either param.
     *
     * @link https://php.net/manual/en/gmagick.scaleimage.php
     *
     * @param int  $width  The number of columns in the scaled image.
     * @param int  $height The number of rows in the scaled image.
     * @param bool $fit    [optional]
     *
     * @return Gmagick The Gmagick object on success.
     *
     * @throws GmagickException On error.
     */
    public function scaleimage($width, $height, $fit = false) {}

    /**
     * Separates a channel from the image and returns a grayscale image.
     *
     * A channel is a particular color component of each pixel in the image.
     *
     * @link https://php.net/manual/en/gmagick.separateimagechannel.php
     *
     * @param int $channel Identify which channel to extract:
     *                     RedChannel, GreenChannel, BlueChannel, OpacityChannel,
     *                     CyanChannel, MagentaChannel, YellowChannel, BlackChannel.
     *
     * @return Gmagick The Gmagick object on success.
     *
     * @throws GmagickException On error.
     */
    public function separateimagechannel($channel) {}

    /**
     * Sets the object's default compression quality.
     *
     * @link https://php.net/manual/en/gmagick.setcompressionquality.php
     *
     * @param int $quality [optional]
     *
     * @return Gmagick The Gmagick object on success.
     *
     * @throws GmagickException On error.
     */
    public function setCompressionQuality($quality = 75) {}

    /**
     * Sets the filename before you read or write the image.
     *
     * @link https://php.net/manual/en/gmagick.setfilename.php
     *
     * @param string $filename The image filename.
     *
     * @return Gmagick The Gmagick object on success.
     *
     * @throws GmagickException On error.
     */
    public function setfilename($filename) {}

    /**
     * Sets the image background color.
     *
     * @link https://php.net/manual/en/gmagick.setimagebackgroundcolor.php
     *
     * @param GmagickPixel $color The background pixel wand.
     *
     * @return Gmagick The Gmagick object on success.
     *
     * @throws GmagickException On error.
     */
    public function setimagebackgroundcolor($color) {}

    /**
     * Sets the image chromaticity blue primary point.
     *
     * @link https://php.net/manual/en/gmagick.setimageblueprimary.php
     *
     * @param float $x The blue primary x-point.
     * @param float $y The blue primary y-point.
     *
     * @return Gmagick The Gmagick object on success.
     *
     * @throws GmagickException On error.
     */
    public function setimageblueprimary($x, $y) {}

    /**
     * Sets the image border color.
     *
     * @link https://php.net/manual/en/gmagick.setimagebordercolor.php
     *
     * @param GmagickPixel $color The border pixel wand.
     *
     * @return Gmagick The Gmagick object on success.
     *
     * @throws GmagickException On error.
     */
    public function setimagebordercolor(GmagickPixel $color) {}

    /**
     * Sets the depth of a particular image channel.
     *
     * @link https://php.net/manual/en/gmagick.setimagechanneldepth.php
     *
     * @param int $channel Identify which channel to extract: RedChannel, GreenChannel, BlueChannel,
     *                     OpacityChannel, CyanChannel, MagentaChannel, YellowChannel, BlackChannel.
     * @param int $depth   The image depth in bits.
     *
     * @return Gmagick The Gmagick object on success.
     *
     * @throws GmagickException On error.
     */
    public function setimagechanneldepth($channel, $depth) {}

    /**
     * Sets the image colorspace.
     *
     * @link https://php.net/manual/en/gmagick.setimagecolorspace.php
     *
     * @param int $colorspace The image colorspace: UndefinedColorspace, RGBColorspace, GRAYColorspace,
     *                        TransparentColorspace, OHTAColorspace, XYZColorspace, YCbCrColorspace, YCCColorspace,
     *                        YIQColorspace, YPbPrColorspace, YPbPrColorspace, YUVColorspace, CMYKColorspace,
     *                        sRGBColorspace, HSLColorspace, or HWBColorspace.
     *
     * @return Gmagick The Gmagick object on success.
     *
     * @throws GmagickException On error.
     */
    public function setimagecolorspace($colorspace) {}

    /**
     * Sets the image composite operator.
     *
     * @link https://php.net/manual/en/gmagick.setimagecompose.php
     *
     * @param int $composite The image composite operator.
     *
     * @return Gmagick The Gmagick object on success.
     *
     * @throws GmagickException On error.
     */
    public function setimagecompose($composite) {}

    /**
     * Sets the image delay.
     *
     * @link https://php.net/manual/en/gmagick.setimagedelay.php
     *
     * @param int $delay The image delay in 1/100th of a second.
     *
     * @return Gmagick The Gmagick object on success.
     *
     * @throws GmagickException On error.
     */
    public function setimagedelay($delay) {}

    /**
     * Sets the image depth.
     *
     * @link https://php.net/manual/en/gmagick.setimagedepth.php
     *
     * @param int $depth The image depth in bits: 8, 16, or 32.
     *
     * @return Gmagick The Gmagick object on success.
     *
     * @throws GmagickException On error.
     */
    public function setimagedepth($depth) {}

    /**
     * Sets the image disposal method.
     *
     * @link https://php.net/manual/en/gmagick.setimagedispose.php
     *
     * @param int $disposeType The image disposal type.
     *
     * @return Gmagick The Gmagick object on success.
     *
     * @throws GmagickException On error.
     */
    public function setimagedispose($disposeType) {}

    /**
     * Sets the filename of a particular image in a sequence.
     *
     * @link https://php.net/manual/en/gmagick.setimagefilename.php
     *
     * @param string $filename The image filename.
     *
     * @return Gmagick The Gmagick object on success.
     *
     * @throws GmagickException On error.
     */
    public function setimagefilename($filename) {}

    /**
     * Sets the format of a particular image in a sequence.
     *
     * @link https://php.net/manual/en/gmagick.setimageformat.php
     *
     * @param string $imageFormat The image format.
     *
     * @return Gmagick The Gmagick object on success.
     *
     * @throws GmagickException On error.
     */
    public function setimageformat($imageFormat) {}

    /**
     * Sets the image gamma.
     *
     * @link https://php.net/manual/en/gmagick.setimagegamma.php
     *
     * @param float $gamma The image gamma.
     *
     * @return Gmagick The Gmagick object on success.
     *
     * @throws GmagickException On error.
     */
    public function setimagegamma($gamma) {}

    /**
     * Sets the image chromaticity green primary point.
     *
     * @link https://php.net/manual/en/gmagick.setimagegreenprimary.php
     *
     * @param float $x The chromaticity green primary x-point.
     * @param float $y The chromaticity green primary y-point.
     *
     * @return Gmagick The Gmagick object on success.
     *
     * @throws GmagickException On error.
     */
    public function setimagegreenprimary($x, $y) {}

    /**
     * Sets the iterator to the position in the image list specified with the index parameter.
     *
     * @link https://php.net/manual/en/gmagick.setimageindex.php
     *
     * @param int $index The scene number.
     *
     * @return Gmagick The Gmagick object on success.
     *
     * @throws GmagickException On error.
     */
    public function setimageindex($index) {}

    /**
     * Sets the interlace scheme of the image.
     *
     * @link https://php.net/manual/en/gmagick.setimageinterlacescheme.php
     *
     * @param int $interlace The image interlace scheme: NoInterlace, LineInterlace, PlaneInterlace, PartitionInterlace.
     *
     * @return Gmagick The Gmagick object on success.
     *
     * @throws GmagickException On error.
     */
    public function setimageinterlacescheme($interlace) {}

    /**
     * Sets the image iterations.
     *
     * @link https://php.net/manual/en/gmagick.setimageiterations.php
     *
     * @param int $iterations
     *
     * @return Gmagick The Gmagick object on success.
     *
     * @throws GmagickException On error.
     */
    public function setimageiterations($iterations) {}

    /**
     * Adds a named profile to the Gmagick object.
     *
     * If a profile with the same name already exists, it is replaced.
     * This method differs from the Gmagick::profileimage() method in that it does not apply any CMS color profiles.
     *
     * @link https://php.net/manual/en/gmagick.setimageprofile.php
     *
     * @param string $name    Name of profile to add or remove: ICC, IPTC, or generic profile.
     * @param string $profile The profile.
     *
     * @return Gmagick The Gmagick object on success.
     *
     * @throws GmagickException On error.
     */
    public function setimageprofile($name, $profile) {}

    /**
     * Sets the image chromaticity red primary point.
     *
     * @link https://php.net/manual/en/gmagick.setimageredprimary.php
     *
     * @param float $x The red primary x-point.
     * @param float $y The red primary y-point.
     *
     * @return Gmagick The Gmagick object on success.
     *
     * @throws GmagickException On error.
     */
    public function setimageredprimary($x, $y) {}

    /**
     * Sets the image rendering intent.
     *
     * @link https://php.net/manual/en/gmagick.setimagerenderingintent.php
     *
     * @param int $rendering_intent The image rendering intent: UndefinedIntent, SaturationIntent,
     *                              PerceptualIntent, AbsoluteIntent, or RelativeIntent.
     *
     * @return Gmagick The Gmagick object on success.
     *
     * @throws GmagickException On error.
     */
    public function setimagerenderingintent($rendering_intent) {}

    /**
     * Sets the image resolution.
     *
     * @link https://php.net/manual/en/gmagick.setimageresolution.php
     *
     * @param float $xResolution The image x resolution.
     * @param float $yResolution The image y resolution.
     *
     * @return Gmagick The Gmagick object on success.
     *
     * @throws GmagickException On error.
     */
    public function setimageresolution($xResolution, $yResolution) {}

    /**
     * Sets the image scene.
     *
     * @link https://php.net/manual/en/gmagick.setimagescene.php
     *
     * @param int $scene The image scene number.
     *
     * @return Gmagick The Gmagick object on success.
     *
     * @throws GmagickException On error.
     */
    public function setimagescene($scene) {}

    /**
     * Sets the image type.
     *
     * @link https://php.net/manual/en/gmagick.setimagetype.php
     *
     * @param int $imgType The image type: UndefinedType, BilevelType, GrayscaleType, GrayscaleMatteType, PaletteType,
     *                     PaletteMatteType, TrueColorType, TrueColorMatteType, ColorSeparationType,
     *                     ColorSeparationMatteType, or OptimizeType.
     *
     * @return Gmagick The Gmagick object on success.
     *
     * @throws GmagickException On error.
     */
    public function setimagetype($imgType) {}

    /**
     * Sets the image units of resolution.
     *
     * @link https://php.net/manual/en/gmagick.setimageunits.php
     *
     * @param int $resolution The image units of resolution : Undefinedresolution, PixelsPerInchResolution,
     *                        or PixelsPerCentimeterResolution.
     *
     * @return Gmagick The Gmagick object on success.
     *
     * @throws GmagickException On error.
     */
    public function setimageunits($resolution) {}

    /**
     * Sets the image chromaticity white point.
     *
     * @link https://php.net/manual/en/gmagick.setimagewhitepoint.php
     *
     * @param float $x The white x-point.
     * @param float $y The white y-point.
     *
     * @return Gmagick The Gmagick object on success.
     *
     * @throws GmagickException On error.
     */
    public function setimagewhitepoint($x, $y) {}

    /**
     * Sets the image sampling factors.
     *
     * @link https://php.net/manual/en/gmagick.setsamplingfactors.php
     *
     * @param array $factors An array of doubles representing the sampling factor
     *                       for each color component (in RGB order).
     *
     * @return Gmagick The Gmagick object on success.
     *
     * @throws GmagickException On error.
     */
    public function setsamplingfactors($factors) {}

    /**
     * Sets the size of the Gmagick object.
     *
     * Set it before you read a raw image format such as RGB, GRAY, or CMYK.
     *
     * @link https://php.net/manual/en/gmagick.setsize.php
     *
     * @param int $columns The width in pixels.
     * @param int $rows    The height in pixels.
     *
     * @return Gmagick The Gmagick object on success.
     *
     * @throws GmagickException On error.
     */
    public function setsize($columns, $rows) {}

    /**
     * Slides one edge of an image along the X or Y axis, creating a parallelogram.
     *
     * An X direction shear slides an edge along the X axis, while a Y direction shear slides an edge along the Y axis.
     * The amount of the shear is controlled by a shear angle. For X direction shears, x_shear is measured relative to
     * the Y axis, and similarly, for Y direction shears y_shear is measured relative to the X axis. Empty triangles
     * left over from shearing the image are filled with the background color.
     *
     * @link https://php.net/manual/en/gmagick.shearimage.php
     *
     * @param mixed $color  The background pixel wand.
     * @param float $xShear The number of degrees to shear the image.
     * @param float $yShear The number of degrees to shear the image.
     *
     * @return Gmagick The Gmagick object on success.
     *
     * @throws GmagickException On error.
     */
    public function shearimage($color, $xShear, $yShear) {}

    /**
     * Applies a solarizing effect to the image.
     *
     * Applies a special effect to the image, similar to the effect achieved in a photo darkroom by selectively
     * exposing areas of photo sensitive paper to light. Threshold ranges from 0 to QuantumRange and is a measure of
     * the extent of the solarization.
     *
     * @link https://php.net/manual/en/gmagick.solarizeimage.php
     *
     * @param int $threshold Define the extent of the solarization.
     *
     * @return Gmagick The Gmagick object on success.
     *
     * @throws GmagickException On error.
     */
    public function solarizeimage($threshold) {}

    /**
     * Randomly displaces each pixel in a block.
     *
     * Special effects method that randomly displaces each pixel in a block defined by the radius parameter.
     *
     * @link https://php.net/manual/en/gmagick.spreadimage.php
     *
     * @param float $radius Choose a random pixel in a neighborhood of this extent.
     *
     * @return Gmagick The Gmagick object on success.
     *
     * @throws GmagickException On error.
     */
    public function spreadimage($radius) {}

    /**
     * Strips an image of all profiles and comments.
     *
     * @link https://php.net/manual/en/gmagick.stripimage.php
     *
     * @return Gmagick The Gmagick object on success.
     *
     * @throws GmagickException On error.
     */
    public function stripimage() {}

    /**
     * Swirls the pixels about the center of the image.
     *
     * Swirls the pixels about the center of the image, where degrees indicates the sweep of the arc through which
     * each pixel is moved. You get a more dramatic effect as the degrees move from 1 to 360.
     *
     * @link https://php.net/manual/en/gmagick.swirlimage.php
     *
     * @param float $degrees Define the tightness of the swirling effect.
     *
     * @return Gmagick The Gmagick object on success.
     *
     * @throws GmagickException On error.
     */
    public function swirlimage($degrees) {}

    /**
     * Changes the size of an image to the given dimensions and removes any associated profiles.
     *
     * The goal is to produce small low cost thumbnail images suited for display on the Web.
     * If TRUE is given as a third parameter then columns and rows parameters are used as maximums for each side.
     * Both sides will be scaled down until the match or are smaller than the parameter given for the side.
     *
     * @link https://php.net/manual/en/gmagick.thumbnailimage.php
     *
     * @param int  $width  Image width.
     * @param int  $height Image height.
     * @param bool $fit    [optional]
     *
     * @return Gmagick The Gmagick object on success.
     *
     * @throws GmagickException On error.
     */
    public function thumbnailimage($width, $height, $fit = false) {}

    /**
     * Remove edges that are the background color from the image.
     *
     * @link https://php.net/manual/en/gmagick.trimimage.php
     *
     * @param float $fuzz By default target must match a particular pixel color exactly. However, in many cases two
     *                    colors may differ by a small amount. The fuzz member of image defines how much tolerance is
     *                    acceptable to consider two colors as the same. This parameter represents the variation on the
     *                    quantum range.
     *
     * @return Gmagick The Gmagick object.
     *
     * @throws GmagickException On error.
     */
    public function trimimage($fuzz) {}

    /**
     * Writes an image to the specified filename.
     *
     * Writes an image to the specified filename. If the filename parameter is NULL, the image is written to the
     * filename set by Gmagick::ReadImage() or Gmagick::SetImageFilename().
     *
     * This is an alias for writeimage().
     *
     * @link https://php.net/manual/en/gmagick.write.php
     *
     * @param string $filename The image filename.
     *
     * @return Gmagick The Gmagick object.
     *
     * @throws GmagickException On error.
     */
    public function write($filename) {}

    /**
     * Writes an image to the specified filename.
     *
     * Writes an image to the specified filename. If the filename parameter is NULL, the image is written to the
     * filename set by Gmagick::ReadImage() or Gmagick::SetImageFilename().
     *
     * @link https://php.net/manual/en/gmagick.writeimage.php
     *
     * @param string $filename   The image filename.
     * @param bool   $all_frames [optional]
     *
     * @return Gmagick The Gmagick object.
     *
     * @throws GmagickException On error.
     */
    public function writeimage($filename, $all_frames = false) {}
}

/**
 * @link https://php.net/manual/en/class.gmagickdraw.php
 */
class GmagickDraw
{
    /**
     * Draws text on the image.
     *
     * @link https://php.net/manual/en/gmagickdraw.annotate.php
     *
     * @param float  $x    x ordinate to left of text.
     * @param float  $y    y ordinate to text baseline.
     * @param string $text text to draw.
     *
     * @return GmagickDraw The GmagickDraw object on success.
     */
    public function annotate($x, $y, $text) {}

    /**
     * Draws an arc falling within a specified bounding rectangle on the image.
     *
     * @link https://php.net/manual/en/gmagickdraw.arc.php
     *
     * @param float $sx starting x ordinate of bounding rectangle.
     * @param float $sy starting y ordinate of bounding rectangle.
     * @param float $ex ending x ordinate of bounding rectangle.
     * @param float $ey ending y ordinate of bounding rectangle.
     * @param float $sd starting degrees of rotation.
     * @param float $ed ending degrees of rotation.
     *
     * @return GmagickDraw The GmagickDraw object on success.
     */
    public function arc($sx, $sy, $ex, $ey, $sd, $ed) {}

    /**
     * Draws a bezier curve through a set of points on the image.
     *
     * @link https://php.net/manual/en/gmagickdraw.bezier.php
     *
     * @param array $coordinate_array Coordinates array.
     *
     * @return GmagickDraw The GmagickDraw object on success.
     */
    public function bezier(array $coordinate_array) {}

    /**
     * Draws an ellipse on the image.
     *
     * @link https://php.net/manual/en/gmagickdraw.ellipse.php
     *
     * @param float $ox    origin x ordinate.
     * @param float $oy    origin y ordinate.
     * @param float $rx    radius in x.
     * @param float $ry    radius in y.
     * @param float $start starting rotation in degrees.
     * @param float $end   ending rotation in degrees.
     *
     * @return GmagickDraw The GmagickDraw object on success.
     */
    public function ellipse($ox, $oy, $rx, $ry, $start, $end) {}

    /**
     * Returns the fill color used for drawing filled objects.
     *
     * @link https://php.net/manual/en/gmagickdraw.getfillcolor.php
     *
     * @return GmagickPixel The GmagickPixel fill color used for drawing filled objects.
     */
    #[Pure]
    public function getfillcolor() {}

    /**
     * Returns the opacity used when drawing.
     *
     * @link https://php.net/manual/en/gmagickdraw.getfillopacity.php
     *
     * @return float The opacity used when drawing using the fill color or fill texture. Fully opaque is 1.0.
     */
    #[Pure]
    public function getfillopacity() {}

    /**
     * Returns a string specifying the font used when annotating with text.
     *
     * @link https://php.net/manual/en/gmagickdraw.getfont.php
     *
     * @return string|false A string on success and false if no font is set.
     */
    #[Pure]
    public function getfont() {}

    /**
     * Returns the font pointsize used when annotating with text.
     *
     * @link https://php.net/manual/en/gmagickdraw.getfontsize.php
     *
     * @return float The font size associated with the current GmagickDraw object.
     */
    #[Pure]
    public function getfontsize() {}

    /**
     * Returns the font style used when annotating with text.
     *
     * @link https://php.net/manual/en/gmagickdraw.getfontstyle.php
     *
     * @return int The font style constant (STYLE_) associated with the GmagickDraw object or 0 if no style is set.
     */
    #[Pure]
    public function getfontstyle() {}

    /**
     * Returns the font weight used when annotating with text.
     *
     * @link https://php.net/manual/en/gmagickdraw.getfontweight.php
     *
     * @return int An int on success and 0 if no weight is set.
     */
    #[Pure]
    public function getfontweight() {}

    /**
     * Returns the color used for stroking object outlines.
     *
     * @link https://php.net/manual/en/gmagickdraw.getstrokecolor.php
     *
     * @return GmagickPixel Returns an GmagickPixel object which describes the color.
     */
    #[Pure]
    public function getstrokecolor() {}

    /**
     * Returns the opacity of stroked object outlines.
     *
     * @link https://php.net/manual/en/gmagickdraw.getstrokeopacity.php
     *
     * @return float Returns a float describing the opacity.
     */
    #[Pure]
    public function getstrokeopacity() {}

    /**
     * Returns the width of the stroke used to draw object outlines.
     *
     * @link https://php.net/manual/en/gmagickdraw.getstrokewidth.php
     *
     * @return float Returns a float describing the stroke width.
     */
    #[Pure]
    public function getstrokewidth() {}

    /**
     * Returns the decoration applied when annotating with text.
     *
     * @link https://php.net/manual/en/gmagickdraw.gettextdecoration.php
     *
     * @return int Returns one of the DECORATION_ constants and 0 if no decoration is set.
     */
    #[Pure]
    public function gettextdecoration() {}

    /**
     * Returns the code set used for text annotations.
     *
     * @link https://php.net/manual/en/gmagickdraw.gettextencoding.php
     *
     * @return string|false Returns a string specifying the code set or false if text encoding is not set.
     */
    #[Pure]
    public function gettextencoding() {}

    /**
     * Draws a line on the image using the current stroke color, stroke opacity, and stroke width.
     *
     * @link https://php.net/manual/en/gmagickdraw.line.php
     *
     * @param float $sx starting x ordinate.
     * @param float $sy starting y ordinate.
     * @param float $ex ending x ordinate.
     * @param float $ey ending y ordinate.
     *
     * @return GmagickDraw The GmagickDraw object on success.
     */
    public function line($sx, $sy, $ex, $ey) {}

    /**
     * Draws a point using the current stroke color and stroke thickness at the specified coordinates.
     *
     * @link https://php.net/manual/en/gmagickdraw.point.php
     *
     * @param float $x target x coordinate.
     * @param float $y target y coordinate.
     *
     * @return GmagickDraw The GmagickDraw object on success.
     */
    public function point($x, $y) {}

    /**
     * Draws a polygon using the current stroke, stroke width, and fill color or texture, using the specified array of coordinates.
     *
     * @link https://php.net/manual/en/gmagickdraw.polygon.php
     *
     * @param array $coordinates The array of coordinates.
     *
     * @return GmagickDraw The GmagickDraw object on success.
     */
    public function polygon(array $coordinates) {}

    /**
     * Draws a polyline using the current stroke, stroke width, and fill color or texture, using the specified array of coordinates.
     *
     * @link https://php.net/manual/en/gmagickdraw.polyline.php
     *
     * @param array $coordinate_array The array of coordinates.
     *
     * @return GmagickDraw The GmagickDraw object on success.
     */
    public function polyline(array $coordinate_array) {}

    /**
     * Draws a rectangle given two coordinates and using the current stroke, stroke width, and fill settings.
     *
     * @link https://php.net/manual/en/gmagickdraw.rectangle.php
     *
     * @param float $x1 x ordinate of first coordinate.
     * @param float $y1 y ordinate of first coordinate.
     * @param float $x2 x ordinate of second coordinate.
     * @param float $y2 y ordinate of second coordinate.
     *
     * @return GmagickDraw The GmagickDraw object on success.
     */
    public function rectangle($x1, $y1, $x2, $y2) {}

    /**
     * Applies the specified rotation to the current coordinate space.
     *
     * @link https://php.net/manual/en/gmagickdraw.rotate.php
     *
     * @param float $degrees degrees of rotation.
     *
     * @return GmagickDraw The GmagickDraw object on success.
     */
    public function rotate($degrees) {}

    /**
     * Draws a rounded rectangle given two coordinates, x and y corner radiuses and using the current stroke, stroke width, and fill settings.
     *
     * @link https://php.net/manual/en/gmagickdraw.roundrectangle.php
     *
     * @param float $x1 x ordinate of first coordinate.
     * @param float $y1 y ordinate of first coordinate.
     * @param float $x2 x ordinate of second coordinate.
     * @param float $y2 y ordinate of second coordinate.
     * @param float $rx radius of corner in horizontal direction.
     * @param float $ry radius of corner in vertical direction.
     *
     * @return GmagickDraw The GmagickDraw object on success.
     */
    public function roundrectangle($x1, $y1, $x2, $y2, $rx, $ry) {}

    /**
     * Adjusts the scaling factor to apply in the horizontal and vertical directions to the current coordinate space.
     *
     * @link https://php.net/manual/en/gmagickdraw.scale.php
     *
     * @param float $x horizontal scale factor.
     * @param float $y vertical scale factor.
     *
     * @return GmagickDraw The GmagickDraw object on success.
     */
    public function scale($x, $y) {}

    /**
     * Sets the fill color to be used for drawing filled objects.
     *
     * @link https://php.net/manual/en/gmagickdraw.setfillcolor.php
     *
     * @param GmagickPixel|string $color GmagickPixel indicating color to use for filling.
     *
     * @return GmagickDraw The GmagickDraw object on success.
     */
    public function setfillcolor($color) {}

    /**
     * Sets the opacity to use when drawing using the fill color or fill texture. Setting it to 1.0 will make fill full opaque.
     *
     * @link https://php.net/manual/en/gmagickdraw.setfillopacity.php
     *
     * @param float $fill_opacity The fill opacity.
     *
     * @return GmagickDraw The GmagickDraw object on success.
     */
    public function setfillopacity($fill_opacity) {}

    /**
     * Sets the fully-specified font to use when annotating with text.
     *
     * @link https://php.net/manual/en/gmagickdraw.setfont.php
     *
     * @param string $font The font name.
     *
     * @return GmagickDraw The GmagickDraw object on success.
     */
    public function setfont($font) {}

    /**
     * Sets the font pointsize to use when annotating with text.
     *
     * @link https://php.net/manual/en/gmagickdraw.setfontsize.php
     *
     * @param float $pointsize The text pointsize.
     *
     * @return GmagickDraw The GmagickDraw object on success.
     */
    public function setfontsize($pointsize) {}

    /**
     * Sets the font style to use when annotating with text.
     *
     * The AnyStyle enumeration acts as a wild-card "don't care" option.
     *
     * @link https://php.net/manual/en/gmagickdraw.setfontstyle.php
     *
     * @param int $style The font style (NormalStyle, ItalicStyle, ObliqueStyle, AnyStyle).
     *
     * @return GmagickDraw The GmagickDraw object on success.
     */
    public function setfontstyle($style) {}

    /**
     * Sets the font weight to use when annotating with text.
     *
     * @link https://php.net/manual/en/gmagickdraw.setfontweight.php
     *
     * @param int $weight The font weight (valid range 100-900).
     *
     * @return GmagickDraw The GmagickDraw object on success.
     */
    public function setfontweight($weight) {}

    /**
     * Sets the color used for stroking object outlines.
     *
     * @link https://php.net/manual/en/gmagickdraw.setstrokecolor.php
     *
     * @param GmagickPixel|string $color GmagickPixel representing the color for the stroke.
     *
     * @return GmagickDraw The GmagickDraw object on success.
     */
    public function setstrokecolor($color) {}

    /**
     * Specifies the opacity of stroked object outlines.
     *
     * @link https://php.net/manual/en/gmagickdraw.setstrokeopacity.php
     *
     * @param float $stroke_opacity Stroke opacity. The value 1.0 is opaque.
     *
     * @return GmagickDraw The GmagickDraw object on success.
     */
    public function setstrokeopacity($stroke_opacity) {}

    /**
     * Sets the width of the stroke used to draw object outlines.
     *
     * @link https://php.net/manual/en/gmagickdraw.setstrokewidth.php
     *
     * @param float $width The stroke width.
     *
     * @return GmagickDraw The GmagickDraw object on success.
     */
    public function setstrokewidth($width) {}

    /**
     * Specifies a decoration to be applied when annotating with text.
     *
     * @link https://php.net/manual/en/gmagickdraw.settextdecoration.php
     *
     * @param int $decoration The text decoration.
     *                        One of NoDecoration, UnderlineDecoration, OverlineDecoration, or LineThroughDecoration.
     *
     * @return GmagickDraw The GmagickDraw object on success.
     */
    public function settextdecoration($decoration) {}

    /**
     * Specifies the code set to use for text annotations.
     *
     * The only character encoding which may be specified at this time is "UTF-8" for representing Unicode as a
     * sequence of bytes. Specify an empty string to set text encoding to the system's default. Successful text
     * annotation using Unicode may require fonts designed to support Unicode.
     *
     * @link https://php.net/manual/en/gmagickdraw.settextencoding.php
     *
     * @param string $encoding The text encoding.
     *
     * @return GmagickDraw The GmagickDraw object on success.
     */
    public function settextencoding($encoding) {}
}

class GmagickException extends \Exception {}

/**
 * @link https://php.net/manual/en/class.gmagickpixel.php
 */
class GmagickPixel
{
    /**
     * The GmagickPixel constructor.
     *
     * If a color is specified, the object is constructed and then initialised with that color before being returned.
     *
     * @link https://php.net/manual/en/gmagickpixel.construct.php
     *
     * @param string $color [optional] The optional color string to use as the initial value of this object.
     */
    public function __construct($color = null) {}

    /**
     * Returns the color described by the GmagickPixel object.
     *
     * If the color has an opacity channel set, this is provided as a fourth value in the list.
     *
     * @link https://php.net/manual/en/gmagickpixel.getcolor.php
     *
     * @param bool $as_array        [optional] True to indicate return of array instead of string.
     * @param bool $normalize_array [optional] Normalize the color values.
     *
     * @return mixed A string or array of channel values, each normalized if TRUE is given as param.
     *
     * @throws GmagickPixelException On error.
     */
    #[Pure]
    public function getcolor($as_array = null, $normalize_array = null) {}

    /**
     * Returns the color count associated with this color.
     *
     * @link https://php.net/manual/en/gmagickpixel.getcolorcount.php
     *
     * @return int The color count as an integer.
     *
     * @throws GmagickPixelException On failure.
     */
    #[Pure]
    public function getcolorcount() {}

    /**
     * Gets the normalized value of the provided color channel.
     *
     * @link https://php.net/manual/en/gmagickpixel.getcolorvalue.php
     *
     * @param int $color The channel to check, specified as one of the Gmagick channel constants.
     *
     * @return float The value of the color channel specified, as a floating-point number between 0 and 1.
     *
     * @throws GmagickPixelException On error.
     */
    #[Pure]
    public function getcolorvalue($color) {}

    /**
     * Sets the color.
     *
     * Sets the color described by the GmagickPixel object,
     * with a string (e.g. "blue", "#0000ff", "rgb(0,0,255)", "cmyk(100,100,100,10)", etc.).
     *
     * @link https://php.net/manual/en/gmagickpixel.setcolor.php
     *
     * @param string $color The color definition to use in order to initialise the GmagickPixel object.
     *
     * @return GmagickPixel The GmagickPixel object on success.
     */
    public function setcolor($color) {}

    /**
     * Sets the normalized value of one of the channels.
     *
     * Sets the value of the specified channel of this object to the provided value, which should be between 0 and 1.
     * This function can be used to provide an opacity channel to a GmagickPixel object.
     *
     * @link https://php.net/manual/en/gmagickpixel.setcolorvalue.php
     *
     * @param int   $color One of the Gmagick channel color constants.
     * @param float $value The value to set this channel to, ranging from 0 to 1.
     *
     * @return GmagickPixel The GmagickPixel object on success.
     */
    public function setcolorvalue($color, $value) {}
}

class GmagickPixelException extends \Exception {}
