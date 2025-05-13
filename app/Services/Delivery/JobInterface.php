<?php
namespace App\Services\Delivery;

interface JobInterface {
    public function getAdditionalDriver(): bool;
    public function getCostPerMile(): float;
    public function getDistance(): float;
}