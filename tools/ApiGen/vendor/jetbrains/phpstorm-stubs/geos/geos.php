<?php

/**
 * @see https://github.com/libgeos/php-geos/blob/master/tests/000_General.phpt
 */
define('GEOSBUF_CAP_ROUND', 1);

define('GEOSBUF_CAP_FLAT', 2);

define('GEOSBUF_CAP_SQUARE', 3);

define('GEOSBUF_JOIN_ROUND', 1);

define('GEOSBUF_JOIN_MITRE', 2);

define('GEOSBUF_JOIN_BEVEL', 3);

define('GEOS_POINT', 0);

define('GEOS_LINESTRING', 1);

define('GEOS_LINEARRING', 2);

define('GEOS_POLYGON', 3);

define('GEOS_MULTIPOINT', 4);

define('GEOS_MULTILINESTRING', 5);

define('GEOS_MULTIPOLYGON', 6);

define('GEOS_GEOMETRYCOLLECTION', 7);

define('GEOSVALID_ALLOW_SELFTOUCHING_RING_FORMING_HOLE', 1);

define('GEOSRELATE_BNR_MOD2', 1);

define('GEOSRELATE_BNR_OGC', 1);

define('GEOSRELATE_BNR_ENDPOINT', 2);

define('GEOSRELATE_BNR_MULTIVALENT_ENDPOINT', 3);

define('GEOSRELATE_BNR_MONOVALENT_ENDPOINT', 4);

/**
 * @return string
 */
function GEOSVersion(): string {}

/**
 * @param GEOSGeometry $geom1
 * @param GEOSGeometry $geom2
 * @return GEOSGeometry
 * @throws Exception
 */
function GEOSSharedPaths(GEOSGeometry $geom1, GEOSGeometry $geom2): GEOSGeometry {}

/**
 * @param GEOSGeometry $geom
 * @return array
 * @throws Exception
 */
function GEOSLineMerge(GEOSGeometry $geom): array {}

/**
 * @param string $matrix
 * @param string $pattern
 * @return bool
 * @throws Exception
 */
function GEOSRelateMatch(string $matrix, string $pattern): bool {}

/**
 * @param GEOSGeometry $geom
 * @return array
 *    - 'rings'
 *        Type: array of GEOSGeometry
 *        Rings that can be formed by the costituent
 *        linework of geometry.
 *    - 'cut_edges' (optional)
 *        Type: array of GEOSGeometry
 *        Edges which are connected at both ends but
 *        which do not form part of polygon.
 *    - 'dangles'
 *        Type: array of GEOSGeometry
 *        Edges which have one or both ends which are
 *        not incident on another edge endpoint
 *    - 'invalid_rings'
 *        Type: array of GEOSGeometry
 *        Edges which form rings which are invalid
 *        (e.g. the component lines contain a self-intersection)
 * @throws Exception
 */
function GEOSPolygonize(GEOSGeometry $geom): array {}

/**
 * Class GEOSWKTReader
 * @see https://github.com/libgeos/php-geos/blob/master/tests/003_WKTReader.phpt
 */
class GEOSWKTReader
{
    /**
     * GEOSWKTReader constructor.
     */
    public function __construct() {}

    /**
     * @param string $wkt
     * @return GEOSGeometry
     * @throws Exception
     */
    public function read(string $wkt): GEOSGeometry {}
}

/**
 * Class GEOSWKTWriter
 * @see https://github.com/libgeos/php-geos/blob/master/tests/002_WKTWriter.phpt
 */
class GEOSWKTWriter
{
    /**
     * GEOSWKTWriter constructor.
     */
    public function __construct() {}

    /**
     * @param GEOSGeometry $geom
     * @return string
     * @throws Exception
     */
    public function write(GEOSGeometry $geom): string {}

    /**
     * @param bool $trim
     */
    public function setTrim(bool $trim): void {}

    /**
     * @param int $precision
     */
    public function setRoundingPrecision(int $precision): void {}

    /**
     * @param int $dimension
     * @throws Exception
     */
    public function setOutputDimension(int $dimension): void {}

    /**
     * @return int
     */
    public function getOutputDimension(): int {}

    /**
     * @param bool $old3d
     */
    public function setOld3D(bool $old3d): void {}
}

/**
 * Class GEOSGeometry
 * @see https://github.com/libgeos/php-geos/blob/master/tests/001_Geometry.phpt
 */
class GEOSGeometry
{
    /**
     * GEOSGeometry constructor.
     */
    public function __construct() {}

    /**
     * @return string
     * @throws Exception
     */
    public function __toString(): string {}

    /**
     * @param GEOSGeometry $geom
     * @return GEOSGeometry
     * @throws Exception
     */
    public function project(GEOSGeometry $geom): GEOSGeometry {}

    /**
     * @param float $distance
     * @param bool $normalized
     * @return GEOSGeometry
     * @throws Exception
     */
    public function interpolate(float $distance, bool $normalized = false): GEOSGeometry {}

    /**
     * @param float $distance
     * @param array $styleArray
     *    Keys supported:
     *    'quad_segs'
     *         Type: int
     *         Number of segments used to approximate
     *         a quarter circle (defaults to 8).
     *    'endcap'
     *         Type: long
     *         Endcap style (defaults to GEOSBUF_CAP_ROUND)
     *    'join'
     *         Type: long
     *         Join style (defaults to GEOSBUF_JOIN_ROUND)
     *    'mitre_limit'
     *         Type: double
     *         mitre ratio limit (only affects joins with GEOSBUF_JOIN_MITRE style)
     *         'miter_limit' is also accepted as a synonym for 'mitre_limit'.
     *    'single_sided'
     *         Type: bool
     *         If true buffer lines only on one side, so that the input line
     *         will be a portion of the boundary of the returned polygon.
     *         Only applies to lineal input. Defaults to false.
     * @return GEOSGeometry
     * @throws Exception
     */
    public function buffer(float $distance, array $styleArray = [
        'quad_segs' => 8,
        'endcap' => GEOSBUF_CAP_ROUND,
        'join' => GEOSBUF_JOIN_ROUND,
        'mitre_limit' => 5.0,
        'single_sided' => false
    ]): GEOSGeometry {}

    /**
     * @param float $distance
     * @param array $styleArray
     *    Keys supported:
     *    'quad_segs'
     *         Type: int
     *         Number of segments used to approximate
     *         a quarter circle (defaults to 8).
     *    'join'
     *         Type: long
     *         Join style (defaults to GEOSBUF_JOIN_ROUND)
     *    'mitre_limit'
     *         Type: double
     *         mitre ratio limit (only affects joins with GEOSBUF_JOIN_MITRE style)
     *         'miter_limit' is also accepted as a synonym for 'mitre_limit'.
     * @return GEOSGeometry
     * @throws Exception
     */
    public function offsetCurve(float $distance, array $styleArray = [
        'quad_segs' => 8,
        'join' => GEOSBUF_JOIN_ROUND,
        'mitre_limit' => 5.0
    ]): GEOSGeometry {}

    /**
     * @return GEOSGeometry
     * @throws Exception
     */
    public function envelope(): GEOSGeometry {}

    /**
     * @param GEOSGeometry $geom
     * @return GEOSGeometry
     * @throws Exception
     */
    public function intersection(GEOSGeometry $geom): GEOSGeometry {}

    /**
     * @return GEOSGeometry
     * @throws Exception
     */
    public function convexHull(): GEOSGeometry {}

    /**
     * @param GEOSGeometry $geom
     * @return GEOSGeometry
     * @throws Exception
     */
    public function difference(GEOSGeometry $geom): GEOSGeometry {}

    /**
     * @param GEOSGeometry $geom
     * @return GEOSGeometry
     * @throws Exception
     */
    public function symDifference(GEOSGeometry $geom): GEOSGeometry {}

    /**
     * @return GEOSGeometry
     * @throws Exception
     */
    public function boundary(): GEOSGeometry {}

    /**
     * @param GEOSGeometry|null $geom
     * @return GEOSGeometry
     * @throws Exception
     */
    public function union(GEOSGeometry $geom = null): GEOSGeometry {}

    /**
     * @return GEOSGeometry
     * @throws Exception
     */
    public function pointOnSurface(): GEOSGeometry {}

    /**
     * @return GEOSGeometry
     * @throws Exception
     */
    public function centroid(): GEOSGeometry {}

    /**
     * @param GEOSGeometry $geom
     * @param string|null $pattern
     * @return bool|string
     * @throws Exception
     */
    public function relate(GEOSGeometry $geom, string $pattern = null) {}

    /**
     * @param GEOSGeometry $geom
     * @param int $rule
     * @return string
     * @throws Exception
     */
    public function relateBoundaryNodeRule(GEOSGeometry $geom, int $rule = GEOSRELATE_BNR_OGC): string {}

    /**
     * @param float $tolerance
     * @param bool $preserveTopology
     * @return GEOSGeometry
     * @throws Exception
     */
    public function simplify(float $tolerance, bool $preserveTopology = false): GEOSGeometry {}

    /**
     * @return GEOSGeometry
     * @throws Exception
     */
    public function normalize(): GEOSGeometry {}

    /**
     * @param float $gridSize
     * @param int $flags
     * @return GEOSGeometry
     * @throws Exception
     */
    public function setPrecision(float $gridSize, int $flags = 0): GEOSGeometry {}

    /**
     * @return float
     */
    public function getPrecision(): float {}

    /**
     * @return GEOSGeometry
     * @throws Exception
     */
    public function extractUniquePoints(): GEOSGeometry {}

    /**
     * @param GEOSGeometry $geom
     * @return bool
     * @throws Exception
     */
    public function disjoint(GEOSGeometry $geom): bool {}

    /**
     * @param GEOSGeometry $geom
     * @return bool
     * @throws Exception
     */
    public function touches(GEOSGeometry $geom): bool {}

    /**
     * @param GEOSGeometry $geom
     * @return bool
     * @throws Exception
     */
    public function intersects(GEOSGeometry $geom): bool {}

    /**
     * @param GEOSGeometry $geom
     * @return bool
     * @throws Exception
     */
    public function crosses(GEOSGeometry $geom): bool {}

    /**
     * @param GEOSGeometry $geom
     * @return bool
     * @throws Exception
     */
    public function within(GEOSGeometry $geom): bool {}

    /**
     * @param GEOSGeometry $geom
     * @return bool
     * @throws Exception
     */
    public function contains(GEOSGeometry $geom): bool {}

    /**
     * @param GEOSGeometry $geom
     * @return bool
     * @throws Exception
     */
    public function overlaps(GEOSGeometry $geom): bool {}

    /**
     * @param GEOSGeometry $geom
     * @return bool
     * @throws Exception
     */
    public function covers(GEOSGeometry $geom): bool {}

    /**
     * @param GEOSGeometry $geom
     * @return bool
     * @throws Exception
     */
    public function coveredBy(GEOSGeometry $geom): bool {}

    /**
     * @param GEOSGeometry $geom
     * @return bool
     * @throws Exception
     */
    public function equals(GEOSGeometry $geom): bool {}

    /**
     * @param GEOSGeometry $geom
     * @param float $tolerance
     * @return bool
     * @throws Exception
     */
    public function equalsExact(GEOSGeometry $geom, float $tolerance = 0): bool {}

    /**
     * @return bool
     * @throws Exception
     */
    public function isEmpty(): bool {}

    /**
     * @return array
     * @throws Exception
     */
    public function checkValidity(): array {}

    /**
     * @return bool
     * @throws Exception
     */
    public function isSimple(): bool {}

    /**
     * @return bool
     * @throws Exception
     */
    public function isRing(): bool {}

    /**
     * @return bool
     * @throws Exception
     */
    public function hasZ(): bool {}

    /**
     * @return bool
     * @throws Exception
     */
    public function isClosed(): bool {}

    /**
     * @return string
     * @throws Exception
     */
    public function typeName(): string {}

    /**
     * @return int
     * @throws Exception
     */
    public function typeId(): int {}

    /**
     * @return int
     */
    public function getSRID(): int {}

    /**
     * @param int $srid
     * @throws Exception
     */
    public function setSRID(int $srid): void {}

    /**
     * @return int
     * @throws Exception
     */
    public function numGeometries(): int {}

    /**
     * @param int $n
     * @return GEOSGeometry
     * @throws Exception
     */
    public function geometryN(int $n): GEOSGeometry {}

    /**
     * @return int
     * @throws Exception
     */
    public function numInteriorRings(): int {}

    /**
     * @return int
     * @throws Exception
     */
    public function numPoints(): int {}

    /**
     * @return float
     * @throws Exception
     */
    public function getX(): float {}

    /**
     * @return float
     * @throws Exception
     */
    public function getY(): float {}

    /**
     * @param int $n
     * @return GEOSGeometry
     * @throws Exception
     */
    public function interiorRingN(int $n): GEOSGeometry {}

    /**
     * @return GEOSGeometry
     * @throws Exception
     */
    public function exteriorRing(): GEOSGeometry {}

    /**
     * @return int
     * @throws Exception
     */
    public function numCoordinates(): int {}

    /**
     * @return int
     * @throws Exception
     */
    public function dimension(): int {}

    /**
     * @return int
     * @throws Exception
     */
    public function coordinateDimension(): int {}

    /**
     * @param int $n
     * @return GEOSGeometry
     * @throws Exception
     */
    public function pointN(int $n): GEOSGeometry {}

    /**
     * @return GEOSGeometry
     * @throws Exception
     */
    public function startPoint(): GEOSGeometry {}

    /**
     * @return GEOSGeometry
     * @throws Exception
     */
    public function endPoint(): GEOSGeometry {}

    /**
     * @return float
     * @throws Exception
     */
    public function area(): float {}

    /**
     * @return float
     * @throws Exception
     */
    public function length(): float {}

    /**
     * @param GEOSGeometry $geom
     * @return float
     * @throws Exception
     */
    public function distance(GEOSGeometry $geom): float {}

    /**
     * @param GEOSGeometry $geom
     * @return float
     * @throws Exception
     */
    public function hausdorffDistance(GEOSGeometry $geom): float {}

    /**
     * @param GEOSGeometry $geom
     * @param float $tolerance
     * @return GEOSGeometry
     */
    public function snapTo(GEOSGeometry $geom, float $tolerance): GEOSGeometry {}

    /**
     * @return GEOSGeometry
     * @throws Exception
     */
    public function node(): GEOSGeometry {}

    /**
     * @param float $tolerance Snapping tolerance to use for improved robustness
     * @param bool $onlyEdges if true, will return a MULTILINESTRING,
     *     otherwise (the default) it will return a GEOMETRYCOLLECTION containing triangular POLYGONs.
     * @return GEOSGeometry
     * @throws Exception
     */
    public function delaunayTriangulation(float $tolerance = 0.0, bool $onlyEdges = false): GEOSGeometry {}

    /**
     * @param float $tolerance Snapping tolerance to use for improved robustness
     * @param bool $onlyEdges If true will return a MULTILINESTRING,
     *     otherwise (the default) it will return a GEOMETRYCOLLECTION containing POLYGONs.
     * @param GEOSGeometry|null $extent Clip returned diagram by the extent of the given geometry
     * @return GEOSGeometry
     * @throws Exception
     */
    public function voronoiDiagram(float $tolerance = 0.0, bool $onlyEdges = false, GEOSGeometry $extent = null): GEOSGeometry {}

    /**
     * @param float $xmin
     * @param float $ymin
     * @param float $xmax
     * @param float $ymax
     * @return GEOSGeometry
     * @throws Exception
     */
    public function clipByRect(float $xmin, float $ymin, float $xmax, float $ymax): GEOSGeometry {}
}

/**
 * Class GEOSWKBWriter
 * @see https://github.com/libgeos/php-geos/blob/master/tests/004_WKBWriter.phpt
 */
class GEOSWKBWriter
{
    /**
     * GEOSWKBWriter constructor.
     */
    public function __construct() {}

    /**
     * @return int
     */
    public function getOutputDimension(): int {}

    /**
     * @param int $dimension
     * @throws Exception
     */
    public function setOutputDimension(int $dimension): void {}

    /**
     * @return int
     */
    public function getByteOrder(): int {}

    /**
     * @param int $byteOrder
     * @throws Exception
     */
    public function setByteOrder(int $byteOrder): void {}

    /**
     * @return int
     */
    public function getIncludeSRID(): int {}

    /**
     * @param int $srid
     * @throws Exception
     */
    public function setIncludeSRID(int $srid): void {}

    /**
     * @param GEOSGeometry $geom
     * @return string
     * @throws Exception
     */
    public function write(GEOSGeometry $geom): string {}

    /**
     * @param GEOSGeometry $geom
     * @return string
     * @throws Exception
     */
    public function writeHEX(GEOSGeometry $geom): string {}
}

/**
 * Class GEOSWKBReader
 * @see https://github.com/libgeos/php-geos/blob/master/tests/005_WKBReader.phpt
 */
class GEOSWKBReader
{
    /**
     * GEOSWKBReader constructor.
     */
    public function __construct() {}

    /**
     * @param string $wkb
     * @return GEOSGeometry
     * @throws Exception
     */
    public function read(string $wkb): GEOSGeometry {}

    /**
     * @param string $wkb
     * @return GEOSGeometry
     * @throws Exception
     */
    public function readHEX(string $wkb): GEOSGeometry {}
}
