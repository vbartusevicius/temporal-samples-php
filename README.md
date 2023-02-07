# Temporal PHP SDK samples

This example illustrates the `BookingSaga` workflow with activities distributed across multiple services.

## Installation

* `docker compose run app composer install`
* `docker compose run app1 composer install`
* `docker compose run app2 composer install`
* `docker compose up`

### Dispatch command

`docker compose exec app php app.php booking-saga`

The command will be dispatched on the `app` service. It will wait for Temporal to process the workflow.
The `BookHotelActivity` will be executed on `app1`, and `ReserveCarActivity` - on `app2`.

### Results

The `ReserveCarActivity` will throw an error with 20% probability.
You can check the entire workflow and compensation on the Temporal dashboard - `localhost:8088`.
