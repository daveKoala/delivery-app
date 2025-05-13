<?php
namespace App\Services\Delivery;

/**
 * JobClass
 * This class represents a delivery job.
 * It contains properties and methods to manage the job details.
 */

class JobClass implements JobInterface {
    private $additionalDriver;
    private $costPerMile;
    private $distance;

    public function setAdditionalDriver(bool $additionalDriver): self {
        $this->additionalDriver = $additionalDriver;
        return $this;
    }

    public function setCostPerMile(float $costPerMile): self {
        $this->costPerMile = $costPerMile;
        return $this;
    }

    public function setDistance(float $distance): self {
        $this->distance = $distance;
        return $this;
    }


    public function getAdditionalDriver(): bool {
        return $this->additionalDriver;
    }

    public function getCostPerMile(): float {
        return $this->costPerMile;
    }

    public function getDistance(): float {
        return $this->distance;
    }
}