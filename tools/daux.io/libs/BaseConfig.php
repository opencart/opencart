<?php namespace Todaymade\Daux;

class BaseConfig extends \ArrayObject
{
    /**
     * Merge an array into the object.
     *
     * @param array $newValues
     * @param bool $override
     */
    public function merge($newValues, $override = true)
    {
        foreach ($newValues as $key => $value) {
            // If the key doesn't exist yet,
            // we can simply set it.
            if (!array_key_exists($key, (array) $this)) {
                $this[$key] = $value;

                continue;
            }

            // We already know this value exists
            // so if we're in conservative mode
            // we can skip this key
            if ($override === false) {
                continue;
            }

            // Merge the values only if
            // both values are arrays
            if (is_array($this[$key]) && is_array($value)) {
                $this[$key] = array_replace_recursive($this[$key], $value);
            } else {
                $this[$key] = $value;
            }
        }
    }

    public function hasValue($key)
    {
        return array_key_exists($key, (array) $this);
    }

    public function getValue($key, $default = null)
    {
        if ($this->hasValue($key)) {
            return $this[$key];
        }

        return $default;
    }

    public function isTruthy($key)
    {
        return $this->hasValue($key) && (bool) $this->getValue($key);
    }

    public function setValue($key, $value)
    {
        $this[$key] = $value;
    }
}
