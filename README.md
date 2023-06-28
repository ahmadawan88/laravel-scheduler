
# Laravel Scheduler:

## Setup

- composer installl
- php artisan migrate
- php artisan db:seed
- php artisan serve



## endpoints:
- [GET] http://[HOST]:[PORT]/api/available_slots
### payload:
```
{
    "to": [Y-m-d],
    "from": [Y-m-d],
    "service_id": 1,
}
```
### Response:
```
{
    "status": true,
    "message": "",
    "data": [
        {
            "date": "2023-06-28",
            "available_slots": [
                {
                    "startTime": "08:00",
                    "endTime": "08:15",
                    "date": "2023-06-28",
                    "day": "Wednesday",
                    "remainingCapacity": 3
                }
            ]
        }
    ]
}
```

- [POST] http://[HOST]:[PORT]/api/bookings
### payload:
```
{
    "service_id": 1,
    "start_time": "13:00",
    "end_time": "13:15",
    "date": "2023-07-03",
    "customers": [
        {
            "email": "example@email.com",
            "first_name": "john",
            "last_name": "doe"
        }
    ]
}
```
### Response:
```
{
    "status": true,
    "message": "Bookings created successfully",
    "data": true
}
```