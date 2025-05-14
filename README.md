# PHP Mid-Senior Developer Task

_The files are designed to plug into a Laravel Project_

Create a calculator module that will be used to generate costs for a courier service. In particular, this calculator will calculate costs for a
driver and their van undertaking a delivery job

Treat your calculator as one that will be built upon. Other developers will need to either extend on or build ones similar to the one you develop here. Be sure to structure your code in a way that accommodates this scenario, preferably using OOP methodology.

The resulting pricing data should include the following items:

## Definitions

- Delivery: A collection of drop offs or Jobs. Each Delivery will do one pick up. There will be between 1 and 5 Jobs per Delivery
- Job: A 'drop off', the spec is cost per mile, total miles, optional additional driver/helper
- 'van': Method of delivery

## Approach to completing the task

I can see that this task has two main goals

- Create costs for a courier
- Be open to extension

The 'Single Responsibility Principle' in SOLID, I switch out in favour of Local of Behaviour (LOB). A function might be 60–80 lines long, and that’s okay—if you can understand everything it does right there.

“The larger the part of an implementation you can understand by looking at it—without jumping between files—the better.”

By keeping related behaviour together, I reduce mental overhead, simplify debugging, and make accidental breakage less likely.

- The way 'van' is used in the task description and from personal experience there will be more than one mode of delivery (Bikes, Moped, etc)
- I can envision different modes will have different methods of calculating courier costs

## Planning

- I need modes of delivery (Van, bike, etc)
- I need a way to have a common method of calculating courier costs, that can be extended or overwritten
- I am acutely aware that OOP can get overly complex with interfaces/abstract classes, inheritance, traits and composition. So maintainability and reducing cognitive load on developers is a factor
- I need to be able to test not just the whole module but also individual parts

## Design Overview

**1. Strategy Pattern**

- The core logic for calculating costs is implemented using the Strategy pattern.
- TransportTypeInterface defines the required methods.
- TransportAbstractClass provides a base implementation.
- Concrete transport classes like BikeClass, VanClass, and MopedClass can override the base calculation if needed.

This makes it easy to introduce new transport methods later without modifying existing logic.

**2. Delivery Class (Coordinator)**

- The DeliveryClass acts as a wrapper and orchestrator:
- It receives a transport strategy (e.g. BikeClass) at construction.
- Jobs are added via addJob() or setJobs() methods.
- It delegates cost calculations to the transport strategy via getQuote().

This class currently performs simple delegation, but is deliberately designed to grow in responsibility — for example, adding validation, logging, business rules, or multi-pickup coordination in future.

**3. Job Abstraction**

Each Job represents a single drop-off:

- Distance (in miles)
  -Cost per mile
- Whether an additional driver is required

Jobs are passed to the transport strategy, where the actual pricing logic resides.

## Limitations and Caveats

- This prototype omits input validation and assumes well-formed user data. For example, The number of jobs is not constrained to a max of 5. This can be added is needed to the Delivery class, or with input validation
- Logging, error reporting, and config integration have been intentionally left out for brevity.
- Authentication and authorization (e.g., Auth0) are not implemented but would be essential in a production environment.
- The transport strategy is hard-coded rather than dynamically resolved from a service container or configuration.
- No API docs
- The response body and headers would need additional information. E.g. header with request id, and api version. Body would look like

```
{
    data: <data>,
    // Depending on your common response envelope
    continuationToken: ''
    nextURL: ''
    message: ''
    requestId: ''
}

```

## API Documentation

Time constraints, prevent me from creating full documentation.

Example cURL

```
curl --location 'http://my-first-application.test/api/quote' \
--header 'Content-Type: application/json' \
--data '{"transport_method":"bike","jobs":[{"distance":55,"cost_per_mile":1,"additional_driver":true}]}'

```

Response body

```
{
    "average_cost_per_mile": 1,
    "additional_driver_cost": 15,
    "total_mileage_cost": 55,
    "total_cost": 70,
    "total_miles": 55
}
```

Example cURL

```
curl --location 'http://my-first-application.test/api/quote' \
--header 'Content-Type: application/json' \
--data '{"transport_method":"bike","jobs":[{"distance":55,"cost_per_mile":1,"additional_driver":true},{"distance":13,"cost_per_mile":3,"additional_driver":false},{"distance":22,"cost_per_mile":1.5}]}'
```

Response body

```
{
    "average_cost_per_mile": 1.41,
    "additional_driver_cost": 15,
    "total_mileage_cost": 127,
    "total_cost": 142,
    "total_miles": 90
}

```
