# Store Test Assignment

### Run tests

API is covered with feature tests.

1. `git clone https://github.com/xEdelweiss/store-test-assignment.git`
2. `cd store-test-assignment`
3. `composer install`
4. `cp .env.example .env`
5. `php artisan key:generate`
6. `php vendor/bin/phpunit` 

### Features that are considered out of scope

* Prices are not saved for the order.
* Cart is processed on a frontend.

### Overview

**Modules**:
* User
  * ~~Registration~~ _(out of scope)_
  * ~~Authentication~~ _(out of scope)_
  * [x] See orders history
  * ~~Rate order~~ _(out of scope)_
  * [x] Update profile
* Product
  * [x] List products
  * [x] Filter products
  * [x] See product details
  * ~~Add/Update/Remove products~~ _(out of scope)_
* Order
  * [x] Make order
  * [x] See order details
  * ~~Filter orders~~ _(out of scope)_

**Events and their possible use**:
* OrderCreated:
  * Block products for some amount of time
  * Track order start for analytics
* ~~OrderPaid~~ _(out of scope)_
  * Trigger order shipping from the warehouse
  * Financial analytics
* OrderShipped
  * Client notification
  * Tracking logistics
* ~~OrderDelivered~~ _(consider as completed, out of scope)_
  * Track order completion for analytics
* ~~OrderCancelled~~ _(out of scope)_
  * Prepare warehouse for products returning
  * Trigger refund
  * Track success rate
* ~~OrderRefunded~~ _(out of scope)_
  * Financial accounting

**Order statuses**:
* Created
* ~~Paid~~ _(out of scope)_
* Shipped
* Delivered
* ~~Cancelled~~ _(out of scope)_
* ~~Refunded~~ _(out of scope)_

### Possible improvements
* Full-text search by title/description

### Structure

With the project's growth, we could use `laravel-modules` to further divide code. For now, it should be enough to keep the default Laravel structure and use subdirectories to separate modules, e.g. `App\Events\Orders\OrderCreated`.
