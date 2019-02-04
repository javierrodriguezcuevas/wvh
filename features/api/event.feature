Feature: Event
    In order to use my api
    As an API client
    I need to be able to manage place resource

    Scenario: GET a list of events inside radius
        Given the following places exists:
            | latitude  | longitude | id                                   | name        |
            | 41.484590 | 2.175202  | 35f843b2-2625-11e9-ab14-d663bd873d93 | Barcelona 1 |
            | 41.312452 | 2.108002  | 35f843b3-2625-11e9-ab14-d663bd873d93 | Barcelona 2 |
            | 41.375156 | 2.154084  | 35f843b4-2625-11e9-ab14-d663bd873d93 | Barcelona 3 |
            | 41.196385 | 1.566925  | 35f843b5-2625-11e9-ab14-d663bd873d93 | Calafell    |
            | 41.102283 | 1.227722  | 35f843b6-2625-11e9-ab14-d663bd873d93 | Tarragona   |
            | 41.646937 | -0.903910 | 35f843b7-2625-11e9-ab14-d663bd873d93 | Zaragoza    |
        And the following events exists:
            | placeId                              | id                                   | name       |
            | 35f843b2-2625-11e9-ab14-d663bd873d93 | 65f843b2-2625-11e9-ab14-d663bd873d93 | B1 Event   |
            | 35f843b2-2625-11e9-ab14-d663bd873d93 | 65f843b3-2625-11e9-ab14-d663bd873d93 | B1.2 Event |
            | 35f843b3-2625-11e9-ab14-d663bd873d93 | 65f843b4-2625-11e9-ab14-d663bd873d93 | B2 Event   |
            | 35f843b4-2625-11e9-ab14-d663bd873d93 | 65f843b5-2625-11e9-ab14-d663bd873d93 | B3 Event   |
            | 35f843b5-2625-11e9-ab14-d663bd873d93 | 65f843b6-2625-11e9-ab14-d663bd873d93 | C1 Event   |
            | 35f843b6-2625-11e9-ab14-d663bd873d93 | 65f843b7-2625-11e9-ab14-d663bd873d93 | T1 Event   |
            | 35f843b7-2625-11e9-ab14-d663bd873d93 | 65f843b8-2625-11e9-ab14-d663bd873d93 | Z1 Event   |
        When I request "GET /api/v1/events?latitude=41.374991&longitude=2.149186&radius=50"
        Then the response status code should be 200
        And the HAL link "self" should exist and its value should be "/api/v1/events?latitude=41.374991&longitude=2.149186&radius=50"
        And the "_embedded.events" property should be an array
        And the embedded "events.0" should have a "id" property equal to "65f843b2-2625-11e9-ab14-d663bd873d93"
        And the embedded "events.0._links.self" should have a "href" property equal to "/api/v1/events/65f843b2-2625-11e9-ab14-d663bd873d93"

    Scenario: GET a detail of event
        Given the following places exists:
            | latitude  | longitude | id                                   | name        |
            | 41.484590 | 2.175202  | 35f843b2-2625-11e9-ab14-d663bd873d93 | Barcelona 1 |
        And the following events exists:
            | placeId                              | id                                   | name       |
            | 35f843b2-2625-11e9-ab14-d663bd873d93 | 65f843b2-2625-11e9-ab14-d663bd873d93 | B1 Event   |
        And the following posts exists:
            | eventId                              | text      |
            | 65f843b2-2625-11e9-ab14-d663bd873d93 | Post 1 B1 |
            | 65f843b2-2625-11e9-ab14-d663bd873d93 | Post 2 B1 |
            | 65f843b2-2625-11e9-ab14-d663bd873d93 | Post 3 B1 |
        When I request "GET /api/v1/events/65f843b2-2625-11e9-ab14-d663bd873d93"
        Then the response status code should be 200
        And the HAL link "self" should exist and its value should be "/api/v1/events/65f843b2-2625-11e9-ab14-d663bd873d93"
        And the "_embedded.posts" property should be an array
        And the embedded "posts.0" should have a "text" property equal to "Post 1 B1"
        And the embedded "posts.1" should have a "text" property equal to "Post 2 B1"
        And the embedded "posts.2" should have a "text" property equal to "Post 3 B1"

    Scenario: POST a new event
        Given the following places exists:
            | latitude  | longitude | id                                   | name        |
            | 41.484590 | 2.175202  | 35f843b2-2625-11e9-ab14-d663bd873d93 | Barcelona 1 |
        Given I have the payload:
            """
            {
                "placeId": "35f843b2-2625-11e9-ab14-d663bd873d93",
                "name": "Event B1"
            }
            """
        When I request "POST /api/v1/events"
        Then the response status code should be 201
        And the following properties should exist:
            """
            id
            _links.self.href
            """

    Scenario: Error response on list when missing required latitude
        When I request "GET /api/v1/events?longitude=2.149186&radius=50"
        Then the response status code should be 400
        And the following properties should exist:
            """
            status
            type
            title
            """
        And the "Content-Type" header should be "application/json"
        And the "type" property should equal "missing_required_parameters"


    Scenario: Error response on list when missing required longitude
        When I request "GET /api/v1/events?latitude=41.374991&radius=50"
        Then the response status code should be 400
        And the following properties should exist:
            """
            status
            type
            title
            """
        And the "Content-Type" header should be "application/json"
        And the "type" property should equal "missing_required_parameters"


    Scenario: Error response on list when missing required radius
        When I request "GET /api/v1/events?latitude=41.374991&longitude=2.149186"
        Then the response status code should be 400
        And the following properties should exist:
            """
            status
            type
            title
            """
        And the "Content-Type" header should be "application/json"
        And the "type" property should equal "missing_required_parameters"

    Scenario: Error response on list when invalid latitude
        When I request "GET /api/v1/events?latitude=141.374991&longitude=2.149186&radius=50"
        Then the response status code should be 400
        And the following properties should exist:
            """
            status
            type
            title
            """
        And the "Content-Type" header should be "application/json"
        And the "type" property should equal "invalid_body_format"

    Scenario: Error response on list when invalid longitude
        When I request "GET /api/v1/events?latitude=41.374991&longitude=182.149186&radius=50"
        Then the response status code should be 400
        And the following properties should exist:
            """
            status
            type
            title
            """
        And the "Content-Type" header should be "application/json"
        And the "type" property should equal "invalid_body_format"

    Scenario: Error response on list when invalid radius
        When I request "GET /api/v1/events?latitude=41.374991&longitude=182.149186&radius=0"
        Then the response status code should be 400
        And the following properties should exist:
            """
            status
            type
            title
            """
        And the "Content-Type" header should be "application/json"
        And the "type" property should equal "invalid_body_format"

    Scenario: Error response on create when missing required parameters
        Given I have the payload:
            """
            {
                "foo": "bar"
            }
            """
        When I request "POST /api/v1/events"
        Then the response status code should be 400
        And the following properties should exist:
            """
            status
            type
            title
            """
        And the "Content-Type" header should be "application/json"
        And the "type" property should equal "missing_required_parameters"

    Scenario: Error response on create when invalid parameters
        Given I have the payload:
            """
            {
                "placeId": "35f843b2-2625-0000-ab14-d663bd873d93",
                "name": "Event B1"
            }
            """
        When I request "POST /api/v1/events"
        Then the response status code should be 400
        And the following properties should exist:
            """
            status
            type
            title
            """
        And the "Content-Type" header should be "application/json"
        And the "type" property should equal "invalid_body_format"
