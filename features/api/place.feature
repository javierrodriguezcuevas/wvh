Feature: Place
    In order to use my api
    As an API client
    I need to be able to manage place resource

    Scenario: POST a new place
        Given I have the payload:
            """
            {
                "latitude": 41.484590,
                "longitude": 2.175202
            }
            """
        When I request "POST /api/v1/places"
        Then the response status code should be 201
        And the "id" property should exist
        And the "latitude" property should equal "41.484590"
        And the "longitude" property should equal "2.175202"

    Scenario: Error response on missing required latitude
        Given I have the payload:
            """
            {
                "longitude": 181.025678
            }
            """
        When I request "POST /api/v1/places"
        Then the response status code should be 400
        And the following properties should exist:
            """
            status
            type
            title
            """
        And the "Content-Type" header should be "application/json"
        And the "type" property should equal "missing_required_parameters"

    Scenario: Error response on missing required longitude
        Given I have the payload:
            """
            {
                "latitude": 24.055749
            }
            """
        When I request "POST /api/v1/places"
        Then the response status code should be 400
        And the following properties should exist:
            """
            status
            type
            title
            """
        And the "Content-Type" header should be "application/json"
        And the "type" property should equal "missing_required_parameters"

    Scenario: Error response on invalid latitude
        Given I have the payload:
            """
            {
                "latitude": 224.055749,
                "longitude": 1.025678
            }
            """
        When I request "POST /api/v1/places"
        Then the response status code should be 400
        And the following properties should exist:
            """
            status
            type
            title
            """
        And the "Content-Type" header should be "application/json"
        And the "type" property should equal "invalid_body_format"

    Scenario: Error response on invalid longitude
        Given I have the payload:
            """
            {
                "latitude": 24.055749,
                "longitude": 181.025678
            }
            """
        When I request "POST /api/v1/places"
        Then the response status code should be 400
        And the following properties should exist:
            """
            status
            type
            title
            """
        And the "Content-Type" header should be "application/json"
        And the "type" property should equal "invalid_body_format"
