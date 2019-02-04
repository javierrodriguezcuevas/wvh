# Requirements
You need to have docker installed in your system. You can find how to install in https://docs.docker.com/install/

In `docker-compose.yml` file you can find two configurations, one for Windows with Virtual Box, and another for Linux, uncomment the configuration line needed.

In case you have choose Windows with Virtual Box, you will need at least 2GB of memory in the virtual machine, share the project folder and finally restart Docker machine.

How to share the project folder in Virtual Box: 
- Open Virtual Box
- Choose the Docker machine
- Toolbar -> Machine/Configuration
- Configuration Dialog -> Shared folders/Add new shared folder
- New shared folder Dialog -> 
    - Folder path: 'Select the project folder'
    - Folder name: code-challenge
    - Auto-Mount: true
    - Make Permanent: true

# Installation
Run Docker container

`
docker-compose up
`

Access docker container

`
docker exec -it code-challenge-php bash
`

**Inside Docker container**

Install dependencies

`
composer install
`

Install database

`
php bin/console doctrine:schema:create
`

**In your browser**

`
http://127.0.0.1:8000
`

# Tests
Tests can be launch inside docker container

PHPUnit

`
vendor/bin/phpunit
`

Behat

`
vendor/bin/behat
`

PHPMD

`
vendor/bin/phpmd src/ text phpmd.xml
`

PHPCS

`
vendor/bin/phpcs src/ --standard=phpcs.xml
`

## Architecture

### PHP 
Used PHP version 7.1

### Database
Used MySQL version 8

#### Schema
Place:
 - id BINARY 16 PRIMARY KEY
 - point POINT SRID 0 NOT NULL with SPATIAL INDEX
 - name VARCHAR 255
 
Event:
 - id BINARY 16 PRIMARY KEY
 - place_id BINARY 16 as FOREIGN KEY with INDEX
 - name VARCHAR 255

Post:
 - id BINARY 16 PRIMARY KEY
 - event_id BINARY 16 as FOREIGN KEY with INDEX
 - text TEXT

#### Doctrine
Created custom type 'point' (https://www.doctrine-project.org/projects/doctrine-orm/en/2.6/cookbook/advanced-field-value-conversion-using-custom-mapping-types.html) which is not implemented by default. The query to get events where place position is inside a circle is done with Doctrine native SQL because ST_* functions are not implemented in Doctrine and also Doctrine needs a comparision operator in where clause and in this query that is not possible. 

Used annotations to define ORM mappings, it can be also defined as xml. Yml definition are deprecated.

Primary keys are UUID, defined as binary indexed column. Used bundle ramsey/doctrine-uuid.

Used doctrine embedded, which allows to have model properties as ValueObjects.

##### Relations:
One to many relations with doctrine are by default bidirectional, that means that a child has a reference to his parent, from Event to Place and from Post to Event. If unidirectional relation is needed, it can be done with a join table https://www.doctrine-project.org/projects/doctrine-orm/en/2.6/reference/association-mapping.html#one-to-many-unidirectional-with-join-table.
From a model point of view relations are ArrayCollections objects from Doctrine, they are lazy loaded by default, they can be configured with the option fetch in the mapping definition https://www.doctrine-project.org/projects/doctrine-orm/en/2.6/reference/annotations-reference.html#onetomany.

### Backend
For an REST API we can use an external bundle FOSRestBundle, but I decided not to use.

#### Routes
The route for detail of Event should be like `/place/{placeId}/event/{eventId}` but the requirements says that to show an event you have to pass an event id, not post id.

#### CORS
To allow reading from remote resource we enabled the CORS header with the use of NelmioCorsBundle.

#### HATEOAS
Used BazingaHateoasBundle with JMSSerializer with yml configuration to serialize api response data. The models configuration can be found in src/CodeChallenge/Infrastructure/Resources/config/serializer/*.yml

#### Swagger
Api endpoints, accepted content types, parameters and return responses documentation done with swagger in yml. It can be found in src/CodeChallenge/Infrastructure/Resources/doc/swagger_api.yaml