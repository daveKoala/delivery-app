<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Services\Delivery\MopedClass;
use App\Services\Delivery\BikeClass;
use App\Services\Delivery\VanClass;
use App\Services\Delivery\DeliveryClass;
use App\Services\Delivery\JobClass;

// See README.md
Route::post('/quote', function (Request $request) {
    // "The transport strategy is currently hard-coded rather than dynamically resolved from a service container or configuration system."
    $strategies = [
        'moped' => new MopedClass(),
        'bike' => new BikeClass(),
        'van' => new VanClass(),
    ];

    if(!$request->has('transport_method') || !array_key_exists($request->input('transport_method'), $strategies)) {
        return response()->json([
            'error' => 'Invalid transport method specified.'
        ], 400);
    }
    $strategy = $strategies[$request->input('transport_method')];
    $delivery = new DeliveryClass($strategy);
    $jobs = $request->input('jobs');
    foreach ($jobs as $jobData) {
        $job = new JobClass();
        $job->setDistance($jobData['distance']);
        $job->setCostPerMile($jobData['cost_per_mile']);
        $job->setAdditionalDriver($jobData['additional_driver'] ?? false);
        $delivery->addJob($job);
    }
    
    return response()->json($delivery->getQuote());
});

Route::get('/quote', function (Request $request) {
    return response()->json([
        'message' => 'Please use POST method to get a quote.'
    ]);
});