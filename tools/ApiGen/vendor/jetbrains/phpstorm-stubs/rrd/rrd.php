<?php
/**
 * Stubs for PECL/rrd extension
 * Description taken from official documenation
 * @link     https://php.net/manual/en/book.rrd.php
 * @author   Olegs Capligins <shader@dautkom.lv>
 */

// start of PECL/rrd v1.0

/**
 * Gets latest error message
 * @link https://php.net/manual/en/function.rrd-error.php
 * @return string Latest error message.
 * @since PECL rrd >= 0.9.0
 */
function rrd_error() {}

/**
 * Creates rrd database file
 * @link https://php.net/manual/en/function.rrd-create.php
 * @param string $filename <p>
 * Filename for newly created rrd file.
 * </p>
 * @param array $options <p>
 * Options for rrd create - list of strings. See man page of rrd create for whole list of options.
 * </p>
 * @return bool TRUE on success or FALSE on failure.
 * @since PECL rrd >= 0.9.0
 */
function rrd_create($filename, $options) {}

/**
 * Gets data for graph output from RRD database file as array. This function has same result as rrd_graph(), but fetched data are returned as array, no image file is created.
 * @link https://php.net/manual/en/function.rrd-fetch.php
 * @param string $file <p>
 * RRD database file name.
 * </p>
 * @param array $options <p>
 * Array of options for resolution specification.
 * </p>
 * @return array Array with information about retrieved graph data.
 * @since PECL rrd >= 0.9.0
 */
function rrd_fetch($file, $options) {}

/**
 * Gets the timestamp of the first sample from from the specified RRA of the RRD file.
 * @link https://php.net/manual/en/function.rrd-first.php
 * @param string $file <p>
 * RRD database file name.
 * </p>
 * @param int $raaindex <p>
 * The index number of the RRA that is to be examined. Default value is 0.
 * </p>
 * @return int|false Integer as unix timestamp, FALSE if some error occurs.
 * @since PECL rrd >= 0.9.0
 */
function rrd_first($file, $raaindex = 0) {}

/**
 * Creates image from a data.
 * @link https://php.net/manual/en/function.rrd-graph.php
 * @param string $file <p>
 * The filename to output the graph to. This will generally end in either .png, .svg or .eps, depending on the format you want to output.
 * </p>
 * @param array $options <p>
 * Options for generating image. See man page of rrd graph for all possible options. All options (data definitions, variable definitions, etc.) are allowed.
 * </p>
 * @return array|false If image is created successfully an array with information about generated image is returned, FALSE when error occurs.
 * @since PECL rrd >= 0.9.0
 */
function rrd_graph($file, $options) {}

/**
 * Returns information about particular RRD database file.
 * @link https://php.net/manual/en/function.rrd-info.php
 * @param string $file <p>
 * RRD database file name.
 * </p>
 * @return array|false Array with information about requsted RRD file, FALSE when error occurs.
 * @since PECL rrd >= 0.9.0
 */
function rrd_info($file) {}

/**
 * Returns the UNIX timestamp of the most recent update of the RRD database.
 * @link https://php.net/manual/en/function.rrd-last.php
 * @param string $file <p>
 * RRD database file name.
 * </p>
 * @return int Integer as unix timestamp of the most recent data from the RRD database.
 * @since PECL rrd >= 0.9.0
 */
function rrd_last($file) {}

/**
 * Gets array of the UNIX timestamp and the values stored for each date in the most recent update of the RRD database file.
 * @link https://php.net/manual/en/function.rrd-lastupdate.php
 * @param string $file <p>
 * RRD database file name.
 * </p>
 * @return array|false Array of information about last update, FALSE when error occurs.
 * @since PECL rrd >= 0.9.0
 */
function rrd_lastupdate($file) {}

/**
 * Restores the RRD file from the XML dump.
 * @link https://php.net/manual/en/function.rrd-restore.php
 * @param string $xml_file <p>
 * XML filename with the dump of the original RRD database file.
 * </p>
 * @param string $rrd_file <p>
 * Restored RRD database file name.
 * </p>
 * @param array $options <p>
 * Array of options for restoring. See man page for rrd restore.
 * </p>
 * @return bool Returns TRUE on success, FALSE otherwise.
 * @since PECL rrd >= 0.9.0
 */
function rrd_restore($xml_file, $rrd_file, $options = []) {}

/**
 * Change some options in the RRD dabase header file. E.g. renames the source for the data etc.
 * @link https://php.net/manual/en/function.rrd-tune.php
 * @param string $file <p>
 * RRD database file name.
 * </p>
 * @param array $options <p>
 * Options with RRD database file properties which will be changed. See rrd tune man page for details.
 * </p>
 * @return bool Returns TRUE on success, FALSE otherwise.
 * @since PECL rrd >= 0.9.0
 */
function rrd_tune($file, $options) {}

/**
 * Updates the RRD database file. The input data is time interpolated according to the properties of the RRD database file.
 * @link https://php.net/manual/en/function.rrd-update.php
 * @param string $file <p>
 * RRD database file name. This database will be updated.
 * </p>
 * @param array $options <p>
 * Options for updating the RRD database. This is list of strings. See man page of rrd update for whole list of options.
 * </p>
 * @return bool Updates the RRD database file. The input data is time interpolated according to the properties of the RRD database file.
 * @since PECL rrd >= 0.9.0
 */
function rrd_update($file, $options) {}

/**
 * Returns information about underlying rrdtool library.
 * @link https://php.net/manual/en/function.rrd-version.php
 * @return string String with rrdtool version number e.g. "1.4.3".
 * @since PECL rrd >= 1.0.0
 */
function rrd_version() {}

/**
 * Exports the information about RRD database file. This data can be converted to XML file via user space PHP script and then restored back as RRD database file.
 * @link https://php.net/manual/en/function.rrd-xport.php
 * @param array $options <p>
 * Array of options for the export, see rrd xport man page.
 * </p>
 * @return array|false Array with information about RRD database file, FALSE when error occurs.
 * @since PECL rrd >= 0.9.0
 */
function rrd_xport($options) {}

/**
 * Close any outstanding connection to rrd caching daemon <p>
 * This function is automatically called when the whole PHP process is terminated. It depends on used SAPI. For example, it's called automatically at the end of command line script.</p><p>
 * It's up user whether he wants to call this function at the end of every request or otherwise.</p>
 * @link https://php.net/manual/en/function.rrdc-disconnect.php
 * @return void
 * @since PECL rrd >= 1.1.2
 */
function rrd_disconnect() {}

/**
 * Close any outstanding connection to rrd caching daemon.
 * This function is automatically called when the whole PHP process is terminated. It depends on used SAPI.
 * For example, it's called automatically at the end of command line script.
 * It's up user whether he wants to call this function at the end of every request or otherwise.
 */
function rrdc_disconnect() {}

/**
 * Class for creation of RRD database file.
 * @link https://php.net/manual/en/class.rrdcreator.php
 * @since PECL rrd >= 0.9.0
 */
class RRDCreator
{
    /**
     * Adds RRA - archive of data values for each data source. <p>
     * Archive consists of a number of data values or statistics for each of the defined data-sources (DS). Data sources are defined by method RRDCreator::addDataSource(). You need call this method for each requested archive.
     * </p>
     * @link https://php.net/manual/en/rrdcreator.addarchive.php
     * @see RRDCreator::addDataSource()
     * @param string $description <p>
     * Class for creation of RRD database file.
     * </p>
     * @return void
     * @since PECL rrd >= 0.9.0
     */
    public function addArchive($description) {}

    /**
     * Adds data source definition for RRD database.<p>
     * RRD can accept input from several data sources (DS), e.g incoming and outgoing traffic. This method adds data source by description. You need call this method for each data source.
     * </p>
     * @link https://php.net/manual/en/rrdcreator.adddatasource.php
     * @param string $description <p>
     * Definition of data source - DS. This has same format as DS definition in rrd create command. See man page of rrd create for more details.
     * </p>
     * @return void
     * @since PECL rrd >= 0.9.0
     */
    public function addDataSource($description) {}

    /**
     * Creates new RRDCreator instance.
     * @link https://php.net/manual/en/rrdcreator.construct.php
     * @param string $path <p>
     * Path for newly created RRD database file.
     * </p>
     * @param string $startTime <p>
     * Time for the first value in RRD database. Parameter supports all formats which are supported by rrd create call.
     * </p>
     * @param int $step <p>
     * Base interval in seconds with which data will be fed into the RRD database.
     * </p>
     * @since PECL rrd >= 0.9.0
     */
    public function __construct($path, $startTime = '', $step = 0) {}

    /**
     * Saves the RRD database into file, which name is defined by RRDCreator::__construct()
     * @link https://php.net/manual/en/rrdcreator.save.php
     * @see RRDCreator::__construct()
     * @return bool TRUE on success or FALSE on failure.
     * @since PECL rrd >= 0.9.0
     */
    public function save() {}
}

/**
 * Class for exporting data from RRD database to image file.
 * @link https://php.net/manual/en/class.rrdgraph.php
 * @since PECL rrd >= 0.9.0
 */
class RRDGraph
{
    /**
     * Creates new RRDGraph instance. This instance is responsible for rendering the result of RRD database query into image.
     * @link https://php.net/manual/en/rrdgraph.construct.php
     * @param string $path <p>
     * Full path for the newly created image.
     * </p>
     * @since PECL rrd >= 0.9.0
     */
    public function __construct($path) {}

    /**
     * Saves the result of RRD database query into image defined by RRDGraph::__construct().
     * @link https://php.net/manual/en/rrdgraph.save.php
     * @return array|false Array with information about generated image is returned, FALSE if error occurs.
     * @since PECL rrd >= 0.9.0
     */
    public function save() {}

    /**
     * Saves the RRD database query into image and returns the verbose information about generated graph. <p>
     * If "-" is used as image filename, image data are also returned in result array.
     * </p>
     * @link https://php.net/manual/en/rrdgraph.saveverbose.php
     * @return array|false Array with detailed information about generated image is returned, optionally with image data, FALSE if error occurs.
     * @since PECL rrd >= 0.9.0
     */
    public function saveVerbose() {}

    /**
     * Sets the options for rrd graph export
     * @link https://php.net/manual/en/rrdgraph.setoptions.php
     * @param array $options <p>
     * List of options for the image generation from the RRD database file. It can be list of strings or list of strings with keys for better readability. Read the rrd graph man pages for list of available options.
     * </p>
     * @return void
     * @since PECL rrd >= 0.9.0
     */
    public function setOptions($options) {}
}

/**
 * Class for updating RDD database file.
 * @link https://php.net/manual/en/class.rrdupdater.php
 * @since PECL rrd >= 0.9.0
 */
class RRDUpdater
{
    /**
     * Creates new RRDUpdater instance. This instance is responsible for updating the RRD database file.
     * RRDUpdater constructor.
     * @link https://php.net/manual/en/rrdupdater.construct.php
     * @param string $path <p>
     * Filesystem path for RRD database file, which will be updated.
     * </p>
     * @since PECL rrd >= 0.9.0
     */
    public function __construct($path) {}

    /**
     * Update the RRD file defined via RRDUpdater::__construct(). The file is updated with a specific values.
     * @link https://php.net/manual/en/rrdupdater.update.php
     * @param array $values <p>
     * Data for update. Key is data source name.
     * </p>
     * @param string $time <p>
     * Time value for updating the RRD with a particulat data. Default value is current time.
     * </p>
     * @return bool TRUE on success or FALSE on failure.
     * @throws \Exception on error
     * @since PECL rrd >= 0.9.0
     */
    public function update($values, $time = '') {}
}

// end of PECL/rrd v1.0
