<?php

namespace App\Builder;

interface ContainerInterface
{
    public function save();

    public function load();

    public function unsetAll();

    public function isExist(string $key);

    public function set(string $key, $value);

    public function get(string $key, $default = null);

    public static function reset(string $id);
}