<?php
namespace App\Services\Delivery;
/**
 * Interface TransportTypeInterface
 * @package App\Services\Delivery
 *
 * This interface defines the methods that must be implemented by any transport type.
 * It is used to ensure that all transport types have a consistent interface.
 */

 interface TransportTypeInterface {
    public function addJob(array $job): TransportTypeInterface;
	public function setJobs(array $jobs): TransportTypeInterface;
    public function getQuoteJSON(): string;
}