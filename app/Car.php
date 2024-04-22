<?php

namespace App;

use Illuminate\Support\Traits\Dumpable;

class Car
{
    use Dumpable;

    private $make;
    private $model;
    private $year;

    public function __construct($make, $model, $year)
    {
        $this->make = $make;
        $this->model = $model;
        $this->year = $year;
    }

    public function dump(...$args) {
        dump($this, ...$args);

        return $this;
    }

    public function getMake()
    {
        return $this->make;
    }

    public function getModel()
    {
        return $this->model;
    }

    public function getYear()
    {
        return $this->year;
    }

    public function drive()
    {
        return "Driving the {$this->year} {$this->make} {$this->model}";
    }
}