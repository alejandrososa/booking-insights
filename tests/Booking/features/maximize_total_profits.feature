Feature: The best combination profit API responses
  In order to prove that the Sending API works as expected
  As an tester API client
  Given a list of booking requests
  I want to obtain the best combination of requests that maximizes total profits.

  Scenario: Sending booking requests without data
    When I send a POST request to '/maximize' with JSON body:
    """
    []
    """
    Then the response status code should be 400
    And the response should be this JSON:
    """
    {
        "errors": [
            {
                "field": "",
                "message": "You must specify at least one booking request."
            }
        ]
    }
    """

  Scenario: Sending booking requests without invalid data
    When I send a POST request to '/maximize' with JSON body:
    """
    [
        {
            "wrong_field_request_id": "bookata_XY123",
            "wrong_field_check_in": "2020-01-01",
            "wrong_field_nights": 5,
            "wrong_field_selling_rate": 200,
            "wrong_field_margin": 20
        }
    ]
    """
    Then the response status code should be 400
    And the response should be this JSON:
    """
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
                "field": "wrong_field_request_id",
                "message": "This field was not expected."
            },
            {
                "field": "wrong_field_check_in",
                "message": "This field was not expected."
            },
            {
                "field": "wrong_field_nights",
                "message": "This field was not expected."
            },
            {
                "field": "wrong_field_selling_rate",
                "message": "This field was not expected."
            },
            {
                "field": "wrong_field_margin",
                "message": "This field was not expected."
            }
        ]
    }
    """

  Scenario: Sending 4 booking requests with valid data
    When I send a POST request to '/maximize' with JSON body:
    """
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
    ]
    """
    Then the response status code should be 200
    And the response should be this JSON:
    """
    {
        "request_ids": [
            "bookata_XY123",
            "acme_AAAAA"
        ],
        "total_profit": 88,
        "avg_night": 10,
        "min_night": 8,
        "max_night": 12
    }
    """
