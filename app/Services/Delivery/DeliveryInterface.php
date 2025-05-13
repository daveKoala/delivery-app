<?php
namespace App\Services\Delivery;

interface DeliveryInterface {
	public function setJobs(array $jobs): DeliveryInterface;
    public function addJob(JobInterface $job): DeliveryInterface;
    public function getQuote(): array;
}