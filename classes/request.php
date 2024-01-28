<?php
class Request
{
    private $values = [];

    public function __construct($method)
    {
        $this->values = $method;
    }

    /**
     * Returns the value from the request true or false if it exists in the values
     * @param string $index
     * @return bool
     */
    function has(string $index): bool
    {
        return (isset($this->values[$index]));
    }

    /**
     * Returns the value from the request true or false if it exists in the values
     * @param string $index
     * @return string
     */
    function get(string $index, $default = ''): string
    {
        if ($this->has($index)) {
            return $this->values[$index];
        }
        return $default;
    }

    /**
     * Returns a value at index id or false
     * @param string $index
     * @param string $default
     * @return int 
     */
    function getInt(string $index, string $default = ''): int
    {
        return (int)$this->get($index, $default);
    }

    /**
     * Returns the get function and converts to an integer
     * @param string $index
     * @param string $default
     * @return float
     */
    function getFloat(string $index, string $default = ''): float
    {
        $getFloat = (float)$this->get($index, $default);
        return $getFloat;
    }

    /**
     * Returns the get function and converts to a floating point number
     * @param string $index
     * @param string $default
     * @return bool
     */
    function getBool(string $index, string $default = ''): bool
    {
        return filter_var($this->get($index, $default), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
    }


    /**
     * Zwraca funkcję get i konwertuje ją na wartość logiczną prawda lub fałsz
     * @param string $index
     * @param string $default
     * @return bool 
     */
    function set($index, $values)
    {
        $this->values[$index] = $values;
        return true;
    }
}
