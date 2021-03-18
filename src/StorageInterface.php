<?php

namespace LeakyBucketRateLimiter;

interface StorageInterface {

    /**
     * Reads a value from storage
     *
     * @param $key string Key to read.
     */
    public function get(string $key);

    /**
     * Writes a key-value pair into storage
     *
     * @param $key string Key
     * @param $value mixed Value
     */
    public function set(string $key, $value): void;

}

?>
