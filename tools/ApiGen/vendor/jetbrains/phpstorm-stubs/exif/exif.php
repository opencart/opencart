<?php

// Start of exif v.1.4 $Id$
use JetBrains\PhpStorm\Deprecated;

/**
 * Reads the EXIF headers from JPEG or TIFF
 * @link https://php.net/manual/en/function.exif-read-data.php
 * @param string $file <p>
 * The location of the image file. This cannot be an URL.
 * Since 7.2.0 this can either be a path to the file (stream wrappers are also supported as usual)
 * or a stream resource.
 * </p>
 * @param string|null $required_sections [optional] <p>
 * Is a comma separated list of sections that need to be present in file
 * to produce a result array. If none of the requested
 * sections could be found the return value is <b>FALSE</b>.
 * <tr valign="top">
 * <td>FILE</td>
 * <td>FileName, FileSize, FileDateTime, SectionsFound</td>
 * </tr>
 * <tr valign="top">
 * <td>COMPUTED</td>
 * <td>
 * html, Width, Height, IsColor, and more if available. Height and
 * Width are computed the same way <b>getimagesize</b>
 * does so their values must not be part of any header returned.
 * Also, html is a height/width text string to be used inside normal
 * HTML.
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>ANY_TAG</td>
 * <td>Any information that has a Tag e.g. IFD0, EXIF, ...</td>
 * </tr>
 * <tr valign="top">
 * <td>IFD0</td>
 * <td>
 * All tagged data of IFD0. In normal imagefiles this contains
 * image size and so forth.
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>THUMBNAIL</td>
 * <td>
 * A file is supposed to contain a thumbnail if it has a second IFD.
 * All tagged information about the embedded thumbnail is stored in
 * this section.
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>COMMENT</td>
 * <td>Comment headers of JPEG images.</td>
 * </tr>
 * <tr valign="top">
 * <td>EXIF</td>
 * <td>
 * The EXIF section is a sub section of IFD0. It contains
 * more detailed information about an image. Most of these entries
 * are digital camera related.
 * </td>
 * </tr>
 * </p>
 * @param bool $as_arrays [optional] <p>
 * Specifies whether or not each section becomes an array. The
 * <i>sections</i> COMPUTED,
 * THUMBNAIL, and COMMENT
 * always become arrays as they may contain values whose names conflict
 * with other sections.
 * </p>
 * @param bool $read_thumbnail [optional] <p>
 * When set to <b>TRUE</b> the thumbnail itself is read. Otherwise, only the
 * tagged data is read.
 * </p>
 * @return array|false It returns an associative array where the array indexes are
 * the header names and the array values are the values associated with
 * those headers. If no data can be returned,
 * <b>exif_read_data</b> will return <b>FALSE</b>.
 */
function exif_read_data($file, ?string $required_sections, bool $as_arrays = false, bool $read_thumbnail = false): array|false {}

/**
 * Alias of <b>exif_read_data</b>
 * @link https://php.net/manual/en/function.read-exif-data.php
 * @param $filename
 * @param $sections [optional]
 * @param $arrays [optional]
 * @param $thumbnail [optional]
 * @removed 8.0
 */
#[Deprecated(replacement: "exif_read_data(%parametersList%)", since: "7.2")]
function read_exif_data($filename, $sections = null, $arrays = false, $thumbnail = false) {}

/**
 * Get the header name for an index
 * @link https://php.net/manual/en/function.exif-tagname.php
 * @param int $index <p>
 * The Tag ID for which a Tag Name will be looked up.
 * </p>
 * @return string|false the header name, or <b>FALSE</b> if <i>index</i> is
 * not a defined EXIF tag id.
 */
function exif_tagname(int $index): string|false {}

/**
 * Retrieve the embedded thumbnail of a TIFF or JPEG image
 * @link https://php.net/manual/en/function.exif-thumbnail.php
 * @param string|resource $file <p>
 * The location of the image file. This cannot be an URL.
 * Since 7.2.0 this can either be a path to the file (stream wrappers are also supported as usual)
 * or a stream resource.
 * </p>
 * @param int &$width [optional] <p>
 * The return width of the returned thumbnail.
 * </p>
 * @param int &$height [optional] <p>
 * The returned height of the returned thumbnail.
 * </p>
 * @param int &$image_type [optional] <p>
 * The returned image type of the returned thumbnail. This is either
 * TIFF or JPEG.
 * </p>
 * @return string|false the embedded thumbnail, or <b>FALSE</b> if the image contains no
 * thumbnail.
 */
function exif_thumbnail($file, &$width, &$height, &$image_type): string|false {}

/**
 * Determine the type of an image
 * @link https://php.net/manual/en/function.exif-imagetype.php
 * @param string $filename The image being checked.
 * @return int|false When a correct signature is found, the appropriate constant value will be
 * returned otherwise the return value is <b>FALSE</b>. The return value is the
 * same value that <b>getimagesize</b> returns in index 2 but
 * <b>exif_imagetype</b> is much faster.
 * </p>
 * <p>
 * <b>exif_imagetype</b> will emit an <b>E_NOTICE</b>
 * and return <b>FALSE</b> if it is unable to read enough bytes from the file to
 * determine the image type.
 */
function exif_imagetype(string $filename): int|false {}

define('EXIF_USE_MBSTRING', 1);

// End of exif v.1.4 $Id$
