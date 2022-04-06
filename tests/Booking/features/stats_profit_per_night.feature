Feature: Stats API responses
  In order to prove that the Sending API works as expected
  As an tester API client
  Given a list of booking requests
  I want to obtain the average, minimum, and maximum profit per night taking into account all the booking requests in the payload.

  Scenario: Sending booking requests without data
    When I send a POST request to '/stats' with JSON body:
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
    When I send a POST request to '/stats' with JSON body:
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

  Scenario: Sending 2 booking requests with valid data
    When I send a POST request to '/stats' with JSON body:
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
            "margin": 22
        }
    ]
    """
    Then the response status code should be 200
    And the response should be this JSON:
    """
    {
      "avg_night": 8.29,
      "min_night": 8,
      "max_night": 8.58
    }
    """

  Scenario: Sending 3 booking requests with valid data
    When I send a POST request to '/stats' with JSON body:
    """
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
    """
    Then the response status code should be 200
    And the response should be this JSON:
    """
    {
      "avg_night": 10.8,
      "min_night": 10,
      "max_night": 12.1
    }
    """
