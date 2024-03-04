<?php
/**
 * Helper autocomplete for php solr extension.
 *
 * @author Pierre-Julien Mazenot <pj.mazenot@gmail.com>
 * @link   https://github.com/pjmazenot/phpsolr-phpdoc
 */

/**
 * (PECL solr &gt;= 2.1.0)<br/>
 * Version not present on php.net documentation, determined here by using PECL solr changelog:
 * https://pecl.php.net/package-changelog.php?package=solr&release=2.1.0 <br/>
 * Class SolrDisMaxQuery<br/>
 * @link https://php.net/manual/en/class.solrdismaxquery.php
 */
class SolrDisMaxQuery extends SolrQuery implements Serializable
{
    /**
     * (PECL solr &gt;= 2.1.0)<br/>
     * Adds a Phrase Bigram Field (pf2 parameter)
     * @link https://php.net/manual/en/solrdismaxquery.addbigramphrasefield.php
     * @param string $field <p>
     * Field name
     * </p>
     * @param string $boost [optional] <p>
     * Boost value. Boosts documents with matching terms.
     * </p>
     * @param string $slop [optional] <p>
     * Field Slop
     * </p>
     * @return SolrDisMaxQuery
     */
    public function addBigramPhraseField($field, $boost, $slop) {}

    /**
     * (PECL solr &gt;= 2.1.0)<br/>
     * Adds a boost query field with value and optional boost (bq parameter)
     * @link https://php.net/manual/en/solrdismaxquery.addboostquery.php
     * @param string $field <p>
     * Field name
     * </p>
     * @param string $value
     * @param string $boost [optional] <p>
     * Boost value. Boosts documents with matching terms.
     * </p>
     * @return SolrDisMaxQuery
     */
    public function addBoostQuery($field, $value, $boost) {}

    /**
     * (PECL solr &gt;= 2.1.0)<br/>
     * Adds a Phrase Field (pf parameter)
     * @link https://php.net/manual/en/solrdismaxquery.addphrasefield.php
     * @param string $field <p>
     * Field name
     * </p>
     * @param string $boost [optional] <p>
     * Boost value. Boosts documents with matching terms.
     * </p>
     * @param string $slop [optional] <p>
     * Field Slop
     * </p>
     * @return SolrDisMaxQuery
     */
    public function addPhraseField($field, $boost, $slop) {}

    /**
     * (PECL solr &gt;= 2.1.0)<br/>
     * Add a query field with optional boost (qf parameter)
     * @link https://php.net/manual/en/solrdismaxquery.addqueryfield.php
     * @param string $field <p>
     * Field name
     * </p>
     * @param string $boost [optional] <p>
     * Boost value. Boosts documents with matching terms.
     * </p>
     * @return SolrDisMaxQuery
     */
    public function addQueryField($field, $boost) {}

    /**
     * (PECL solr &gt;= 2.1.0)<br/>
     * Adds a Trigram Phrase Field (pf3 parameter)
     * @link https://php.net/manual/en/solrdismaxquery.addtrigramphrasefield.php
     * @param string $field <p>
     * Field name
     * </p>
     * @param string $boost [optional] <p>
     * Boost value. Boosts documents with matching terms.
     * </p>
     * @param string $slop [optional] <p>
     * Field Slop
     * </p>
     * @return SolrDisMaxQuery
     */
    public function addTrigramPhraseField($field, $boost, $slop) {}

    /**
     * (PECL solr &gt;= 2.1.0)<br/>
     * Adds a field to User Fields Parameter (uf)
     * @link https://php.net/manual/en/solrdismaxquery.adduserfield.php
     * @param string $field <p>
     * Field name
     * </p>
     * @return SolrDisMaxQuery
     */
    public function addUserField($field) {}

    /**
     * (PECL solr &gt;= 2.1.0)<br/>
     * Removes phrase bigram field (pf2 parameter)
     * @link https://php.net/manual/en/solrdismaxquery.removebigramphrasefield.php
     * @param string $field <p>
     * Field name
     * </p>
     * @return SolrDisMaxQuery
     */
    public function removeBigramPhraseField($field) {}

    /**
     * (PECL solr &gt;= 2.1.0)<br/>
     * Removes a boost query partial by field name (bq)
     * @link https://php.net/manual/en/solrdismaxquery.removeboostquery.php
     * @param string $field <p>
     * Field name
     * </p>
     * @return SolrDisMaxQuery
     */
    public function removeBoostQuery($field) {}

    /**
     * (PECL solr &gt;= 2.1.0)<br/>
     * Removes a Phrase Field (pf parameter)
     * @link https://php.net/manual/en/solrdismaxquery.removephrasefield.php
     * @param string $field <p>
     * Field name
     * </p>
     * @return SolrDisMaxQuery
     */
    public function removePhraseField($field) {}

    /**
     * (PECL solr &gt;= 2.1.0)<br/>
     * Removes a Query Field (qf parameter)
     * @link https://php.net/manual/en/solrdismaxquery.removequeryfield.php
     * @param string $field <p>
     * Field name
     * </p>
     * @return SolrDisMaxQuery
     */
    public function removeQueryField($field) {}

    /**
     * (PECL solr &gt;= 2.1.0)<br/>
     * Removes a Trigram Phrase Field (pf3 parameter)
     * @link https://php.net/manual/en/solrdismaxquery.removetrigramphrasefield.php
     * @param string $field <p>
     * Field name
     * </p>
     * @return SolrDisMaxQuery
     */
    public function removeTrigramPhraseField($field) {}

    /**
     * (PECL solr &gt;= 2.1.0)<br/>
     * Removes a field from The User Fields Parameter (uf)
     * <div>
     * <b>Warning</b><br/>
     * This function is currently not documented; only its argument list is available.
     * </div>
     * @link https://php.net/manual/en/solrdismaxquery.removeuserfield.php
     * @param string $field <p>
     * Field name
     * </p>
     * @return SolrDisMaxQuery
     */
    public function removeUserField($field) {}

    /**
     * (PECL solr &gt;= 2.1.0)<br/>
     * Sets Bigram Phrase Fields and their boosts (and slops) using pf2 parameter
     * @link https://php.net/manual/en/solrdismaxquery.setbigramphrasefields.php
     * @param string $fields <p>
     * Fields boosts (slops)
     * </p>
     * @return SolrDisMaxQuery
     */
    public function setBigramPhraseFields($fields) {}

    /**
     * (PECL solr &gt;= 2.1.0)<br/>
     * Sets Bigram Phrase Slop (ps2 parameter)
     * @link https://php.net/manual/en/solrdismaxquery.setbigramphraseslop.php
     * @param string $slop <p>
     * A default slop for Bigram phrase fields.
     * </p>
     * @return SolrDisMaxQuery
     */
    public function setBigramPhraseSlop($slop) {}

    /**
     * (PECL solr &gt;= 2.1.0)<br/>
     * Sets a Boost Function (bf parameter).
     * @link https://php.net/manual/en/solrdismaxquery.setboostfunction.php
     * @param string $function <p>
     * Functions (with optional boosts) that will be included in the user's query to influence the score. Any function
     * supported natively by Solr can be used, along with a boost value. e.g.:<br/>
     * recip(rord(myfield),1,2,3)^1.5
     * </p>
     * @return SolrDisMaxQuery
     */
    public function setBoostFunction($function) {}

    /**
     * (PECL solr &gt;= 2.1.0)<br/>
     * Directly Sets Boost Query Parameter (bq)
     * @link https://php.net/manual/en/solrdismaxquery.setboostquery.php
     * @param string $q
     * @return SolrDisMaxQuery
     */
    public function setBoostQuery($q) {}

    /**
     * (PECL solr &gt;= 2.1.0)<br/>
     * Set Minimum "Should" Match (mm)
     * @link https://php.net/manual/en/solrdismaxquery.setminimummatch.php
     * @param string $value <p>
     * Minimum match value/expression<br/>
     * Set Minimum "Should" Match parameter (mm). If the default query operator is AND then mm=100%, if the default
     * query operator (q.op) is OR, then mm=0%.
     * </p>
     * @return SolrDisMaxQuery
     */
    public function setMinimumMatch($value) {}

    /**
     * (PECL solr &gt;= 2.1.0)<br/>
     * Sets Phrase Fields and their boosts (and slops) using pf2 parameter
     * @link https://php.net/manual/en/solrdismaxquery.setphrasefields.php
     * @param string $fields <p>
     * Fields, boosts [, slops]
     * </p>
     * @return SolrDisMaxQuery
     */
    public function setPhraseFields($fields) {}

    /**
     * (PECL solr &gt;= 2.1.0)<br/>
     * Sets the default slop on phrase queries (ps parameter)
     * @link https://php.net/manual/en/solrdismaxquery.setphraseslop.php
     * @param string $slop <p>
     * Sets the default amount of slop on phrase queries built with "pf", "pf2" and/or "pf3" fields (affects boosting).
     * "ps" parameter
     * </p>
     * @return SolrDisMaxQuery
     */
    public function setPhraseSlop($slop) {}

    /**
     * (PECL solr &gt;= 2.1.0)<br/>
     * Set Query Alternate (q.alt parameter)
     * @link https://php.net/manual/en/solrdismaxquery.setqueryalt.php
     * @param string $q <p>
     * Query String
     * </p>
     * @return SolrDisMaxQuery
     */
    public function setQueryAlt($q) {}

    /**
     * (PECL solr &gt;= 2.1.0)<br/>
     * Specifies the amount of slop permitted on phrase queries explicitly included in the user's query string (qf
     * parameter)
     * @link https://php.net/manual/en/solrdismaxquery.setqueryphraseslop.php
     * @param string $slop <p>
     * Amount of slop<br/>
     * The Query Phrase Slop is the amount of slop permitted on phrase queries explicitly included in the user's query
     * string with the qf parameter.<br/>
     * <br/>
     * slop refers to the number of positions one token needs to be moved in relation to another token in order to match
     * a phrase specified in a query.
     * </p>
     * @return SolrDisMaxQuery
     */
    public function setQueryPhraseSlop($slop) {}

    /**
     * (PECL solr &gt;= 2.1.0)<br/>
     * Sets Tie Breaker parameter (tie parameter)
     * @link https://php.net/manual/en/solrdismaxquery.settiebreaker.php
     * @param string $tieBreaker <p>
     * The tie parameter specifies a float value (which should be something much less than 1) to use as tiebreaker in
     * DisMax queries.
     * </p>
     * @return SolrDisMaxQuery
     */
    public function setTieBreaker($tieBreaker) {}

    /**
     * (PECL solr &gt;= 2.1.0)<br/>
     * Directly Sets Trigram Phrase Fields (pf3 parameter)
     * @link https://php.net/manual/en/solrdismaxquery.settrigramphrasefields.php
     * @param string $fields <p>
     * Trigram Phrase Fields
     * </p>
     * @return SolrDisMaxQuery
     */
    public function setTrigramPhraseFields($fields) {}

    /**
     * (PECL solr &gt;= 2.1.0)<br/>
     * Sets Trigram Phrase Slop (ps3 parameter)
     * @link https://php.net/manual/en/solrdismaxquery.settrigramphraseslop.php
     * @param string $slop <p>
     * Phrase slop
     * </p>
     * @return SolrDisMaxQuery
     */
    public function setTrigramPhraseSlop($slop) {}

    /**
     * (PECL solr &gt;= 2.1.0)<br/>
     * Sets User Fields parameter (uf)
     * @link https://php.net/manual/en/solrdismaxquery.setuserfields.php
     * @param string $fields <p>
     * Fields names separated by space<br/>
     * This parameter supports wildcards.
     * </p>
     * @return SolrDisMaxQuery
     */
    public function setUserFields($fields) {}

    /**
     * (PECL solr &gt;= 2.1.0)<br/>
     * Switch QueryParser to be DisMax Query Parser
     * @link https://php.net/manual/en/solrdismaxquery.usedismaxqueryparser.php
     * @return SolrDisMaxQuery
     */
    public function useDisMaxQueryParser() {}

    /**
     * (PECL solr &gt;= 2.1.0)<br/>
     * Switch QueryParser to be EDisMax<br/>
     * By default the query builder uses edismax, if it was switched using
     * SolrDisMaxQuery::useDisMaxQueryParser(), it can be switched back using this method.
     * @link https://php.net/manual/en/solrdismaxquery.useedismaxqueryparser.php
     * @return SolrDisMaxQuery
     */
    public function useEDisMaxQueryParser() {}
}
