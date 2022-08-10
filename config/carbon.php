<?php
return [
    'opening-hours' => [
        'monday' => ['08:00-12:30', '13:30-17:00'],
        'tuesday' => ['08:00-12:30', '13:30-17:00'],
        'wednesday' => ['08:00-12:30', '13:30-17:00'],
        'thursday' => ['08:00-12:30', '13:30-17:00'],
        'friday' => ['08:00-12:30', '13:30-17:00'],
        'saturday' => [],
        'sunday' => [],
        'exceptions' => [
            '01-01' => [], // Recurring on each 1st of january
            '12-25' => [], // Recurring on each 25th of december
        ],
    ],
];
