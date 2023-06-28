<?php

use Carbon\Carbon;
return [
    'mockAdminName' =>  'Mock Admin',
    'mockUser' => [
        'email' => 'example@example.com',
        'first_name' => 'John',
        'last_name' => 'Smith',
    ],
    'serviceSeedData' => [
        [
            'service' => [
                'name' => 'Men Haircut',
                'slot_duration' => 10,
                'capacity' => 3,
                'clean_time' => 5,
                'booking_time_limit' => 7,
            ],
            'workingDays' => [
                [
                    'day' => 'Monday',
                    'start_time' => Carbon::createFromTime(8,0,0),
                    'end_time'   => Carbon::createFromTime(20,0,0),
                ],
                [
                    'day' =>'Tuesday',
                    'start_time' => Carbon::createFromTime(8,0,0),
                    'end_time'   => Carbon::createFromTime(20,0,0),
                ],
                [
                    'day' => 'Wednesday',
                    'start_time' => Carbon::createFromTime(8,0,0),
                    'end_time'   => Carbon::createFromTime(20,0,0),
                ],
                [
                    'day' => 'Thursday',
                    'start_time' => Carbon::createFromTime(8,0,0),
                    'end_time'   => Carbon::createFromTime(20,0,0),
                ],
                [
                    'day' => 'Friday',
                    'start_time' => Carbon::createFromTime(8,0,0),
                    'end_time'   => Carbon::createFromTime(20,0,0),
                ],
                [
                    'day' => 'Saturday',
                    'start_time' => Carbon::createFromTime(10,0,0),
                    'end_time'   => Carbon::createFromTime(22,0,0),
                ]
            ],
            'breaks' => [
                [
                    'name' => 'Lunch Break',
                    'start_time' => Carbon::createFromTime(12,0,0),
                    'end_time' => Carbon::createFromTime(13,0,0),
                ],
                [
                    'name' => 'Cleaning Break',
                    'start_time' => Carbon::createFromTime(15,0,0),
                    'end_time' => Carbon::createFromTime(16,0,0),
                ]
            ],
            'holidays' => [
                [
                    'name' => 'Public Holiday',
                    'date' => Carbon::now()->addDays('3')->startOfDay(), //3rd day from now is holioday
                    //no need to add start_time && end_time as it's full day holiday
                ]
            ]

        ],
        [
            'service' => [
                'name' => 'Women Haircut',
                'slot_duration' => 60,
                'capacity' => 3,
                'clean_time' => 10,
                'booking_time_limit' => 7,
            ],
            'workingDays' => [
                [
                    'day' => 'Monday',
                    'start_time' => Carbon::createFromTime(8,0,0),
                    'end_time'   => Carbon::createFromTime(20,0,0),
                ],
                [
                    'day' =>'Tuesday',
                    'start_time' => Carbon::createFromTime(8,0,0),
                    'end_time'   => Carbon::createFromTime(20,0,0),
                ],
                [
                    'day' => 'Wednesday',
                    'start_time' => Carbon::createFromTime(8,0,0),
                    'end_time'   => Carbon::createFromTime(20,0,0),
                ],
                [
                    'day' => 'Thursday',
                    'start_time' => Carbon::createFromTime(8,0,0),
                    'end_time'   => Carbon::createFromTime(20,0,0),
                ],
                [
                    'day' => 'Friday',
                    'start_time' => Carbon::createFromTime(8,0,0),
                    'end_time'   => Carbon::createFromTime(20,0,0),
                ],
                [
                    'day' => 'Saturday',
                    'start_time' => Carbon::createFromTime(10,0,0),
                    'end_time'   => Carbon::createFromTime(22,0,0),
                ]
            ],
            'breaks' => [
                [
                    'name' => 'Lunch Break',
                    'start_time' => Carbon::createFromTime(12,0,0),
                    'end_time' => Carbon::createFromTime(13,0,0),
                ],
                [
                    'name' => 'Cleaning Break',
                    'start_time' => Carbon::createFromTime(15,0,0),
                    'end_time' => Carbon::createFromTime(16,0,0),
                ]
            ],
            'holidays' => [
                [
                    'name' => 'Public Holiday',
                    'date' => Carbon::now()->addDays('3')->startOfDay(), //3rd day from now is holioday
                    //no need to add start_time && end_time as it's full day holiday
                ]
            ]
        ]
    ]
];