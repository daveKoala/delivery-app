<?php

namespace Tests\Services\Delivery;

use App\Services\Delivery\DeliveryClass;
use App\Services\Delivery\BikeClass;
use App\Services\Delivery\JobClass;
use PHPUnit\Framework\TestCase;

// See README.md

// Note: This a test for the 'happy path', i.e. the expected behavior of the class.
// Due to time constraints it does not cover edge cases or error handling, which should be tested separately.

class DeliveryClassTest extends TestCase
{
    public function strategy() {
        return new BikeClass();
    }

    public function testDeliveryClassCreation()
    {
        // $bike = new BikeClass();

        $deliveryClass = new DeliveryClass($this->strategy());
        $this->assertInstanceOf(DeliveryClass::class, $deliveryClass);
    }

    public function testDeliveryClassReturnsCorrectQuoteForSingleJob()
    {
        $job = new JobClass();
        $job->setDistance(10);
        $job->setCostPerMile(2.0);
        $job->setAdditionalDriver(false);

        $delivery = new DeliveryClass($this->strategy());
        $delivery->addJob($job); // assuming addJob exists

        $quote = $delivery->getQuote();

        // Assert that quote is an array
        $this->assertIsArray($quote);

        // Assert expected values
        $this->assertEquals(10, $quote['total_miles']);
        $this->assertEquals(2.0, $quote['average_cost_per_mile']);
        $this->assertEquals(20.0, $quote['total_mileage_cost']);
        $this->assertEquals(0.0, $quote['additional_driver_cost']);
        $this->assertEquals(20.0, $quote['total_cost']);
    }

    public function testDeliveryClassReturnsCorrectQuoteForMultipleJobs()
    {
        $job1 = new JobClass();
        $job1->setDistance(10);
        $job1->setCostPerMile(2.0);
        $job1->setAdditionalDriver(false);

        $job2 = new JobClass();
        $job2->setDistance(18);
        $job2->setCostPerMile(7.0);
        $job2->setAdditionalDriver(true);

        $delivery = new DeliveryClass($this->strategy());
        $delivery->addJob($job1); // assuming addJob exists
        $delivery->addJob($job2); // assuming addJob exists

        $quote = $delivery->getQuote();

        // Assert that quote is an array
        $this->assertIsArray($quote);

        // Assert expected values
        $this->assertEquals(28, $quote['total_miles']);
        $this->assertEquals(5.21, $quote['average_cost_per_mile']);
        $this->assertEquals(146.0, $quote['total_mileage_cost']);
        $this->assertEquals(15.0, $quote['additional_driver_cost']);
        $this->assertEquals(161.0, $quote['total_cost']);
    }
}