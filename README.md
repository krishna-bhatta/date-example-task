# Sundays Counter API

This API provides an endpoint to calculate the number of Sundays between two dates, excluding Sundays falling on or after the 28th of the month.

## Endpoint

### Count Sundays

Calculate the number of Sundays between two dates.

- **URL:** `/api/count-sundays`
- **Method:** POST
- **Request Body:**

  ```json
    {
     "start_date": "2023-01-02",
     "end_date": "2023-12-31"
    }
- **Response:**

  ```json
    {
      "success": 1,
      "data": {
        "sundays_count": 46
      }
    }
  
### Error Handling
- **If the dates are not at least two years apart or more than five, the API will respond with a 422 status code and an error message.**
- **If the start date is a Sunday, the API will respond with a 422 status code and an error message.**
