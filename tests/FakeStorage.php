<?php

class FakeStorage {
    public function get($key) {
      return (empty($this->{$key}) ? null : $globalStash->{$key});
    }
    public function set($key, $value) {
      $this->{$key} = $value;
    }
}

?>
