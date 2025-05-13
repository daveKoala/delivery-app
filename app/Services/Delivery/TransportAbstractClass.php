<?php
namespace App\Services\Delivery;

abstract class TransportAbstractClass implements TransportTypeInterface {
	
	private $jobs = [];
    private $additionalDriverCost = 15.00; // Example cost for an additional driver, this can be later changed and be dynamic
	
	public function setJobs(array $jobs): TransportTypeInterface {
		$this->jobs = $jobs;
		
		return $this;
	}

    public function addJob($job): TransportTypeInterface {
        array_push($this->jobs, $job);
        return $this;
    }
	
	public function getQuote()
	{
		// Big O! Doing each seperatly is OK, Stick with it unless profiling shows a bottleneck.
        $averageCostPerMile = $this->calculateAverageCostPerMile();
        $driverCost = $this->calculateAdditionalDriverCost();
        $mileageCost = $this->calculateMileageCost();
        $totalCost = $mileageCost + $driverCost;
		$totalMiles = $this->calculateTotalMiles();

        return [
            'average_cost_per_mile' => round($averageCostPerMile, 2),
            'additional_driver_cost' => round($driverCost, 2),
            'total_mileage_cost' => round($mileageCost, 2),
            'total_cost' => round($totalCost, 2),
            'total_miles' => $totalMiles,
        ];
	}
	
	public function getQuoteJSON(): string {
		return json_encode($this->getQuote(), JSON_PRETTY_PRINT);
	}
	
	protected function calculateTotalMiles(): float {
        return array_reduce($this->jobs, fn($carry, $job) => $carry + $job->getDistance(), 0.0);
    }

    protected function calculateAverageCostPerMile(): float {
    $totals = array_reduce($this->jobs, function ($carry, $job) {
        $carry['cost'] += $job->getDistance() * $job->getCostPerMile();
        $carry['miles'] += $job->getDistance();
        return $carry;
    }, ['miles' => 0, 'cost' => 0]);

    if ($totals['miles'] === 0) return 0.0;
    return $totals['cost'] / $totals['miles'];
}

    protected function calculateMileageCost(): float {
        return array_reduce($this->jobs, fn($carry, $job) =>
            $carry + ($job->getDistance() * $job->getCostPerMile()), 0.0);
    }

    protected function calculateAdditionalDriverCost(): float {
        return array_reduce($this->jobs, fn($carry, $job) =>
            $carry + ($job->getAdditionalDriver() ? $this->additionalDriverCost : 0), 0.0);
    }
}