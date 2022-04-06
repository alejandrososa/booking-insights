Booking Code Challenge
==

Requirements
---

* ✅ Include a README.md file explaining at least how to install and run the application. 
* ✅ Following the best practices of the programming language of your choice, your solution must show off some maintainability characteristics such as modularity,
  understandability, changeability, testability, and reusability. 
* ✅ For instance, it could follow Hexagonal architecture and SOLID principles to comply with some of the above characteristics.
* ✅ Unit testing.
 
Nice to have
---

* ✅ A Docker environment to run the application. Docker Compose would also be appreciated.
* ✅ E2E (end-to-end) testing.
* ✅ Performant for big inputs.
  
Programming language
---
* ✅ You can code your solution with any of these: Go, PHP, Java, Kotlin, Python, JavaScript, or Ruby.
* If you want to use a different language, please get in touch before continuing.


Problem definition
---

We own an apartment and we’re renting it through different popular web sites. Before
actually renting the apartment for some days, those platforms send us booking requests. We
want to get insights from those booking requests in order to make better decisions. For
instance, we’d like to know what’s the profit per night we’re getting and what could be the
best combination of bookings to maximize our profits.

We will create an API for this purpose.

##**/stats endpoint**

Given a list of booking requests, return the average, minimum, and maximum profit per
night taking into account all the booking requests in the payload.

Example request:

POST /stats
```
#example request 1
[
   {
      "request_id": "bookata_XY123",
      "check_in": "2020-01-01",
      "nights": 5,
      "selling_rate": 200,
      "margin": 20
   },
   {
      "request_id": "kayete_PP234",
      "check_in": "2020-01-04",
      "nights": 4,
      "selling_rate": 156,
      "margin": 22
   }
]


#example request 2
[
   {
      "request_id": "bookata_XY123",
      "check_in": "2020-01-01",
      "nights": 1,
      "selling_rate": 50,
      "margin": 20
   },
   {
      "request_id": "kayete_PP234",
      "check_in": "2020-01-04",
      "nights": 1,
      "selling_rate": 55,
      "margin": 22
   },
   {
      "request_id": "trivoltio_ZX69",
      "check_in": "2020-01-07",
      "nights": 1,
      "selling_rate": 49,
      "margin": 21
   }
]
```
Example response:

```
#example request 1
200 OK

{
   "avg_night": 8.29,
   "min_night": 8,
   "max_night": 8.58
}


#example request 2
200 OK

{
   "avg_night": 10.8,
   "min_night": 10,
   "max_night": 12.1
}
```

##**/maximize endpoint**

Given a list of booking requests, return the best combination of requests that maximizes total
profits.

Acceptance Criteria
---

* ✅ Two booking requests cannot overlap in time. For instance, the following requests overlap and cannot be combined. Remember we are renting a single apartment!
  - ✅ A: `check_in: 2020-01-01; nights: 5` (check out on `2020-01-06`)
  - ✅ B: `check_in: 2020-01-03; nights: 5` (check out on `2020-01-08`)
  - ✅ If more than one combination yields the same maximum profit, return any of them.
* ✅ Response fields
    - **request_ids** - list of IDs of the best combination
    - **total_profit** - the total profit of the best combination
    - **avg_night** - the average profit per night of the best combination
    - **min_night** - the minimum profit per night of the best combination
    - **max_night** - the maximum profit per night of the best combination

Detailed example
---
  
Given this set of booking requests:
+ A: check_in: 2018-01-01; nights: 10; selling_rate: 1000€; margin: 10%
+ B: check_in: 2018-01-06; nights: 10; selling_rate: 700€; margin: 10%
+ C: check_in: 2018-01-12; nights: 10; selling_rate: 400€; margin: 10%

We will choose:

| combination | valid | profit                       |
|-------------|-------|------------------------------|
| A           | YES   | 1000*0,1 = 100               |
| B           | YES   | 700*0,1 = 70                 |
| C           | YES   | 400*0,1 = 40                 |
| A+B         | NO    | --                           |
| `A+C`       | `YES` | 1000 * 0.1 + 400 * 0.1 = 140 |
| A+B+C       | NO    | --                           |   
| B+C         | NO    | --                           |   

`A+C` is the best combination of bookings that do not overlap and yield the maximum profit.

With the profit being:

A.sellingRate * A.margin + C.sellingRate * C.margin = 1000 * 0.1 400 * 0.1 = 140€ 

The maxim profit would be: `140€` by combining `A+C`

Example request:
--

POST /stats
```
[
    {
        "request_id":"bookata_XY123",
        "check_in":"2020-01-01",
        "nights":5,
        "selling_rate":200,
        "margin":20
    },
    {
        "request_id":"kayete_PP234",
        "check_in":"2020-01-04",
        "nights":4,
        "selling_rate":156,
        "margin":5
    },
    {
        "request_id":"atropote_AA930",
        "check_in":"2020-01-04",
        "nights":4,
        "selling_rate":150,
        "margin":6
    },
    {
        "request_id":"acme_AAAAA",
        "check_in":"2020-01-10",
        "nights":4,
        "selling_rate":160,
        "margin":30
    }
]
```
Example response:

```
200 OK

{
    "request_ids":[
        "bookata_XY123",
        "acme_AAAAA"
    ],
    "total_profit":88,
    "avg_night":10,
    "min_night":8,
    "max_night":12
}
```



How to validate the Test?
--

By PHP
---

```
#install composer
composer install

#run the test
php vendor/bin/phpunit --testdox
php vendor/bin/behat 

```

By Makefile and Docker (the easiest step)
---

```
#to run all step into one step 
make run

#to run all tests
make tests

#to run only the tests
make tests-unit

#to run only the tests
make tests-behat

```

With Docker Compose (the hardest step)
---

```
#to create the container of php
docker-compose up -d php

#install composer
docker-compose exec php composer install

#run the phpunit test
docker-compose exec php vendor/bin/phpunit --testdox

#run the behat test
docker-compose exec php vendor/bin/behat

#access to the container
docker-compose exec php bash 

#show the container are running
docker-compose ps

NAME                       COMMAND                  SERVICE             STATUS              PORTS
booking-insights-caddy-1   "caddy run --config …"   caddy               running             0.0.0.0:80->80/tcp, 0.0.0.0:443->443/tcp, 0.0.0.0:443->443/udp
booking-insights-php-1     "docker-entrypoint p…"   php                 running (healthy)   9000/tcp
```

## Usage

Just run `make run` or `docker-compose up -d`, then:

* App: visit -> [http://localhost](http://localhost)

Make requests to the endpoint [/stats](https://localhost/stats)
---

```
TEST WITH cURL

#example request 1
curl --location -g -k --request POST 'http://localhost/stats' \
--header 'Content-Type: application/json' \
--data-raw '[
    {
        "request_id": "bookata_XY123",
        "check_in": "2020-01-01",
        "nights": 5,
        "selling_rate": 200,
        "margin": 20
    },
    {
        "request_id": "kayete_PP234",
        "check_in": "2020-01-04",
        "nights": 4,
        "selling_rate": 156,
        "margin": 22
    }
]' | json_pp


#example request 2
curl --location -g -k --request POST 'http://localhost/stats' \
--header 'Content-Type: application/json' \
--data-raw '[
    {
        "request_id": "bookata_XY123",
        "check_in": "2020-01-01",
        "nights": 1,
        "selling_rate": 50,
        "margin": 20
    },
    {
        "request_id": "kayete_PP234",
        "check_in": "2020-01-04",
        "nights": 1,
        "selling_rate": 55,
        "margin": 22
    },
    {
        "request_id": "trivoltio_ZX69",
        "check_in": "2020-01-07",
        "nights": 1,
        "selling_rate": 49,
        "margin": 21
    }
]' | json_pp
```

```
RESULTS /stats

RESPONSE 200 OK
#example request 1
{
   "avg_night" : 8.29,
   "max_night" : 8.58,
   "min_night" : 8
}

#example request 2
{
   "avg_night" : 10.8,
   "max_night" : 12.1,
   "min_night" : 10
}


RESPONSE 400 KO

{
    "errors": [
        {
            "field": "",
            "message": "You must specify at least one booking request."
        }
    ]
}


RESPONSE 400 KO

{
    "errors": [
        {
            "field": "request_id",
            "message": "This field is missing."
        },
        {
            "field": "check_in",
            "message": "This field is missing."
        },
        {
            "field": "nights",
            "message": "This field is missing."
        },
        {
            "field": "selling_rate",
            "message": "This field is missing."
        },
        {
            "field": "margin",
            "message": "This field is missing."
        },
        {
            "field": "wrong_request_id",
            "message": "This field was not expected."
        },
        {
            "field": "wrong_check_in",
            "message": "This field was not expected."
        },
        {
            "field": "wrong_nights",
            "message": "This field was not expected."
        },
        {
            "field": "wrong_selling_rate",
            "message": "This field was not expected."
        },
        {
            "field": "wrong_margin",
            "message": "This field was not expected."
        }
    ]
}
```

Make requests to the endpoint [/maximize](https://localhost/maximize)
---

```
TEST WITH cURL

#example request 1
curl --location -g -k --request POST 'http://localhost/maximize' \
--header 'Content-Type: application/json' \
--data-raw '[
    {
        "request_id": "bookata_XY123",
        "check_in": "2020-01-01",
        "nights": 5,
        "selling_rate": 200,
        "margin": 20
    },
    {
        "request_id": "kayete_PP234",
        "check_in": "2020-01-04",
        "nights": 4,
        "selling_rate": 156,
        "margin": 5
    },
    {
        "request_id": "atropote_AA930",
        "check_in": "2020-01-04",
        "nights": 4,
        "selling_rate": 150,
        "margin": 6
    },
    {
        "request_id": "acme_AAAAA",
        "check_in": "2020-01-10",
        "nights": 4,
        "selling_rate": 160,
        "margin": 30
    }
]' | json_pp
```

```
RESULTS /maximize

RESPONSE 200 OK

{
    "request_ids":[
        "bookata_XY123",
        "acme_AAAAA"
    ],
    "total_profit":88,
    "avg_night":10,
    "min_night":8,
    "max_night":12
}


RESPONSE 400 KO

{
    "errors": [
        {
            "field": "",
            "message": "You must specify at least one booking request."
        }
    ]
}


RESPONSE 400 KO

{
    "errors": [
        {
            "field": "request_id",
            "message": "This field is missing."
        },
        {
            "field": "check_in",
            "message": "This field is missing."
        },
        {
            "field": "nights",
            "message": "This field is missing."
        },
        {
            "field": "selling_rate",
            "message": "This field is missing."
        },
        {
            "field": "margin",
            "message": "This field is missing."
        },
        {
            "field": "wrong_request_id",
            "message": "This field was not expected."
        },
        {
            "field": "wrong_check_in",
            "message": "This field was not expected."
        },
        {
            "field": "wrong_nights",
            "message": "This field was not expected."
        },
        {
            "field": "wrong_selling_rate",
            "message": "This field was not expected."
        },
        {
            "field": "wrong_margin",
            "message": "This field was not expected."
        }
    ]
}
```


Assumptions
--
* Assuming docker compose is used. [How to install it?](https://docs.docker.com/get-docker/)
* Assuming Makerfile is used. [How to install it?](https://linuxhint.com/install-make-ubuntu/)
* Assuming port 80 is not being used on localhost


### 🔥 Application execution

1. Install all the dependencies and bring up the project with Docker executing: `make run`
2. Then you'll have 1 apps available:
    1. [Kata](/stats): http://localhost/stats
    1. [Kata](/maximize): http://localhost/maximize

### ✅ Tests execution

1. Install the dependencies if you haven't done it previously: `make vendor`
2. Execute PHPUnit and Behat tests: `make tests`

## 👩‍💻 Project explanation

This project is using the framework Symfony 6 and PHP 8.0

### ⛱️ Bounded Contexts

* [Common](src/Common): Place to look in if you wanna see some code 🙂.
* [Booking](src/Booking): Here you'll find the use cases needed by the Booking endpoint in order to get insights.

### 🎯 Hexagonal Architecture

This repository follows the Hexagonal Architecture pattern. .
With this, we can see that the current structure of a Bounded Context is:

```scala
$ tree -L 5 src
src
├── Booking  // Booking subdomain / Bounded Context: Features related to the insights
│   ├── Application
│   │   ├── MaximizeBookingRequest
│   │   │   ├── CalculateBestCombinationProfit.php
│   │   │   ├── CalculateBestCombinationProfitHandler.php
│   │   │   └── MaximizeTotalProfitsResponse.php
│   │   └── StatsBookingRequest
│   │       ├── CalculateProfitPerNight.php
│   │       ├── CalculateProfitPerNightHandler.php
│   │       └── ProfitPerNightResponse.php
│   ├── Domain
│   │   ├── BookingRequest
│   │   │   ├── BookingRequest.php  // The Aggregate of the Module
│   │   │   └── BookingRequestFactory.php  // Simple Factory Pattern Design
│   │   └── Insights
│   │       ├── Calculator
│   │       │   ├── InsightCalculator.php // Composition Principle
│   │       │   ├── MaximizeTotalProfitsCalculator.php
│   │       │   └── ProfitPerNightCalculator.php
│   │       └── Pipes   // Interface Segregation Principle 
│   │           ├── AveragePipe.php
│   │           ├── CalculateProfitPerNightPipe.php
│   │           ├── CleanOverlapsPipe.php
│   │           ├── MaxProfitPerNightPipe.php
│   │           ├── MinProfitPerNightPipe.php
│   │           ├── Pipe.php
│   │           └── SearchAllCombinationsPipe.php
│   └── Infrastructure
│       └── Ui
│           └── Rest
│               ├── AbstractController.php
│               ├── MaximizeTotalProfitsController.php
│               └── StatsProfitPerNightController.php
├── Common  // Shared Kernel: Common infrastructure and domain shared between the different Bounded Contexts
│   ├── Domain
│   │   ├── Assert.php
│   │   ├── Collection.php
│   │   ├── Contracts
│   │   │   ├── Entity.php
│   │   │   ├── Enum.php
│   │   │   ├── Equatable.php
│   │   │   ├── IntValueObject.php
│   │   │   ├── StringValueObject.php
│   │   │   └── ValueObject.php
│   │   ├── Criteria
│   │   │   ├── Criteria.php
│   │   │   ├── CriteriaConverter.php
│   │   │   ├── Filter.php
│   │   │   ├── FilterField.php
│   │   │   ├── FilterOperator.php
│   │   │   ├── FilterValue.php
│   │   │   ├── Filters.php
│   │   │   ├── Order.php
│   │   │   ├── OrderBy.php
│   │   │   └── OrderType.php
│   │   ├── Model
│   │   └── Transformable.php
│   └── Infrastructure
│       └── Bus
│           ├── Bus.php
│           └── BusComponent.php  // Composition Principle
└── Kernel.php
```

### Useful commands

```bash
# access to container
$ docker-compose exec php sh

# Composer (e.g. composer install)
$ docker-compose exec php composer install

# Symfony commands
$ docker-compose exec php /var/www/bin/console cache:clear 

# Retrieve an IP Address (here for the nginx container)
$ docker inspect $(docker ps -f name=nginx -q) | grep IPAddress

# Delete all containers
$ docker rm $(docker ps -aq)

# Delete all images
$ docker rmi $(docker images -q)
```

## FAQ

* Got this error: `ERROR: Couldn't connect to Docker daemon at http+docker://localunixsocket - is it running?
  If it's at a non-standard location, specify the URL with the DOCKER_HOST environment variable.` ?  
  Run `docker-compose up -d` instead.

* Permission problem? See [this doc (Setting up Permission)](http://symfony.com/doc/current/book/installation.html#checking-symfony-application-configuration-and-setup)
