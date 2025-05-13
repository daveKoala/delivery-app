<?php
namespace App\Services\Delivery;
/*
 * Assumptions and naming conventions:
 * Delivery Class
 * A Delivery is an array of Jobs
 * A Job has distance, cost per mile, additional driver(s) or helper(s)
 * Additional help/driver is on a per job basis
 * We need to calculate a quote for the completion of a delivery (all jobs)
 * This needs to be extensible to add new transport methods
 * This implementation make use of the Strategy pattern and Builder pattern
 * The Strategy pattern is used to define a family of algorithms, encapsulate each one, and make them interchangeable.
*/

class DeliveryClass implements DeliveryInterface {
	private $transportMethod;
	
	public function __construct(TransportTypeInterface $transportMethod) {
        $this->transportMethod = $transportMethod;
    }
	
	public function getQuote(): array
	{
		return $this->transportMethod->getQuote();
	}
	
	public function setJobs(array $jobs): DeliveryInterface {
    	$this->transportMethod->setJobs($jobs);
    	return $this;
	}

	public function addJob(JobInterface $job): DeliveryInterface {
		$this->transportMethod->addJob($job);
		return $this;
	}
}