<?php

/**
 * Blackfire extension stubs.
 * @link https://blackfire.io
 */
final class BlackfireProbe
{
    /**
     * Returns a global singleton and enables it by default.
     *
     * @return \BlackfireProbe
     */
    public static function getMainInstance() {}

    /**
     * Tells whether the probe is currently profiling or not.
     *
     * @return bool
     */
    public static function isEnabled() {}

    /**
     * Adds a marker for the Timeline View.
     *
     * @param string $markerName
     */
    public static function addMarker($markerName = '') {}

    /**
     * BlackfireProbe constructor. SHOULD NOT BE USED DIRECTLY!
     * USE getMainInstance() INSTEAD!
     *
     * @param string $query An URL-encoded string that configures the probe. Part of the string is signed.
     *                      Typically comes from X-Blackfire-Query HTTP header or BLACKRFIRE_QUERY environment variable.
     * @param string|null $envId An id that is given to the agent for signature impersonation.
     * @param string|null $envToken The token associated to $envId.
     * @param string|null $agentSocket The URL where profiles will be written (directory, socket or TCP destination).
     */
    public function __construct($query, $envId = null, $envToken = null, $agentSocket = null) {}

    /**
     * Tells if the probe is cryptographically verified,
     * i.e. if the signature in X-Blackfire-Query HTTP header or
     * BLACKFIRE_QUERY environment variable is valid.
     *
     * @return bool
     */
    public function isVerified() {}

    /**
     * @param string $configuration
     */
    public function setConfiguration($configuration) {}

    /**
     * Gets the response message/status/line.
     *
     * This lines gives details about the status of the probe. That can be:
     * - an error: `Blackfire-Error: $errNumber $urlEncodedErrorMessage`
     * - or not: `Blackfire-Response: $rfc1738EncodedMessage`
     *
     * @return string The response line
     */
    public function getResponseLine() {}

    /**
     * Enables manual instrumentation. Starts collecting profiling data.
     *
     * @return bool False if enabling failed.
     */
    public function enable() {}

    /**
     * Discards collected data and disables instrumentation.
     *
     * Does not close the profile payload, allowing to re-enable the probe and aggregate data in the same profile.
     *
     * @return bool False if the probe was not enabled.
     */
    public function discard() {}

    /**
     * Disables instrumentation.
     *
     * Does not close the profile payload, allowing to re-enable the probe
     * and to aggregate data in the same profile.
     *
     * @return bool False if the probe was not enabled.
     */
    public function disable() {}

    /**
     * Stops the profiling and forces the collected data to be sent to Blackfire.
     *
     * @return bool False if the probe was not enabled.
     */
    public function close() {}

    /**
     * Creates a sub-query string to create a new profile linked to the current one.
     * Generated query must be set in the X-Blackire-Query HTTP header or in the BLACKFIRE_QUERY environment variable.
     *
     * @return string|null The sub-query or null if the current profile is not the first sample or profiling is disabled.
     */
    public function createSubProfileQuery() {}

    /**
     * Sets a custom transaction name for Blackfire Monitoring.
     *
     * @param string $transactionName Name to register for the transaction (e.g. 'user_model:show')
     *
     * @return void
     */
    public static function setTransactionName(string $transactionName) {}

    /**
     * Disables Blackfire Monitoring instrumentation for a transaction.
     *
     * @return void
     */
    public static function ignoreTransaction() {}

    /**
     * Manually starts a transaction. Useful for CLI/Consumer monitoring.
     *
     * @return void
     */
    public static function startTransaction() {}

    /**
     * Manually stops a transaction. Useful for CLI/Consumer monitoring.
     *
     * @return void
     */
    public static function stopTransaction() {}
}
