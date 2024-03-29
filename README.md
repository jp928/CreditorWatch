[![Build Status](https://travis-ci.org/jp928/CreditorWatch.svg?branch=master)](https://travis-ci.org/jp928/CreditorWatch)
[![codecov](https://codecov.io/gh/jp928/CreditorWatch/branch/master/graph/badge.svg)](https://codecov.io/gh/jp928/CreditorWatch)

### RUN

```
composer run start
```

then open localhost:8080 from any browser

OR run in docker via

```
docker-compose up -d
```

then open localhost from any browser


### TEST

```
composer run test
```

### Code sniffer

```
composer run cs
```

### Basic Structure

```bash
  ├── docker                  # docker files
  ├── public                  # App entry point
  ├── src                     # Source files
  └── test                    # Automated tests
```
  src diretory structure
```bash
  ├── App                   
  │   ├── Base                # Contains Router, Request and Reponse
  │   ├── Cache               # Cache Logic
  │   ├── Container           # Service Container to make dependency injection easier
  │   ├── Controller          # Try to achieve MVC, here is the controller
  │   ├── Entity              # Here is the model
  │   ├── Exceptions          # Exceptions
  │   ├── Parser              # Html Parser
  │   ├── Service             # Service layer encapsulate logics to parse Html thereby make controller decouples from others
  │   ├── Template            # Template file to support front end
  │   ├── Transport           # Download HTML
  │   └── View                # Render entities
  ├── definition.php          # Definition of dependency injection   
  └── bootstrap.php           # Initiate App
```

### Design Pattern in use

* Dependency injection with service container. It is benefitial when instaniating new Controller(new Service(new Downloader, new Parser, new Cache));
* Service container itself is in sigleton pattern.
* Try to build a trail MVC


### CI\CD pipeline
* This repo is supported by Travis
* pre-commit and pre-push would force developer to comply code standard

### How to use

Type any keywords, such as 'CreditorWatch' or 'creditor watch' and click submit. The index of the keyword will show as [1, 3, 4, xxx]. The following list shows the exact matching keyword and index from google search result.

### To do list in production
* Render html in controller is not ideal, should build a middleware mechanism to render template. 
* The \App\Base\Request & \App\Base\Response doesn't comply PSR7 standard.
* \App\Exception\BadRequestException should return http status code 401 or 400
* \App\Exception\PageNotFoundException should return http status code 404
* Controller ideally should allow annotation to match request.
* Implement Authentication
* MVC structure needs to improve so they can decouple to each other (Currently the View is relying on the Entity).
* Chunk or buffer the downloaded html so the app doesn't limit to 100 results.
* Build Log class to do PSR7 compatible logs.
* Introduce REST api and do frontend and backend separation.
* Increase test coverage at least reach 90%
* Production should use more popular frameworks, such as Symphony, PHP-DI, MonoLog etc.

