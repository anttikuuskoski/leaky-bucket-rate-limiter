<?php

include_once("src/StorageInterface.php");

use LeakyBucketRateLimiter\StorageInterface;

class FakeStorage implements StorageInterface {
    public function get(string $key) {
      return (empty($this->{$key}) ? null : $this->{$key});
    }
    public function set(string $key, $value) : void {
      $this->{$key} = $value;
    }
}

?>
