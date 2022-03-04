<?php
/**
 * Copyright 2019 Klarna AB
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * File containing the UserAgent class.
 */

namespace Klarna\Rest\Transport;

/**
 * HTTP user agent.
 */
class UserAgent implements UserAgentInterface
{
    /**
     * Name of the SDK
     */
    const NAME = 'Klarna.kco_rest_php';

    /**
     * Version of the SDK.
     */
    const VERSION = '4.2.3';

    /**
     * Components of the user agent.
     *
     * @var array
     */
    protected $fields = [];


    /**
     * Sets the specified field.
     *
     * @param string $key     Component key, e.g. 'Language'
     * @param string $name    Component name, e.g. 'PHP'
     * @param string $version Version identifier, e.g. '5.4.10'
     * @param array  $options Additional information
     *
     * @return self
     */
    public function setField($key, $name, $version = '', array $options = [])
    {
        $field = [
            'name' => $name
        ];

        if (!empty($version)) {
            $field['version'] = $version;
        }

        if (!empty($options)) {
            $field['options'] = $options;
        }

        $this->fields[$key] = $field;

        return $this;
    }

    /**
     * Serialises the user agent.
     *
     * @return string
     */
    public function __toString()
    {
        $parts = [];

        foreach ($this->fields as $key => $value) {
            $component = "{$key}/{$value['name']}";
            if (!empty($value['version'])) {
                $component .= "_{$value['version']}";
            }

            $parts[] = $component;

            if (empty($value['options'])) {
                continue;
            }

            $opts = implode('; ', $value['options']);
            $parts[] = "({$opts})";
        }

        return implode(' ', $parts);
    }

    /**
     * Creates the default user agent.
     *
     * @return self
     */
    public static function createDefault($options = [])
    {
        $agent = new static();

        if (extension_loaded('curl')) {
            $options[] = 'curl/' . curl_version()['version'];
        }

        return $agent
            ->setField('Library', static::NAME, static::VERSION, $options)
            ->setField('OS', php_uname('s'), php_uname('r'))
            ->setField('Language', 'PHP', phpversion());
    }
}
