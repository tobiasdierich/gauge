<?php

use TobiasDierich\Gauge\EntryType;
use TobiasDierich\Gauge\Storage\EntryModel;

/* @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(EntryModel::class, function (Faker\Generator $faker) {
    return [
        'sequence' => random_int(1, 10000),
        'uuid'     => $faker->uuid,
        'type'     => $faker->randomElement([EntryType::QUERY, EntryType::REQUEST]),
        'duration' => random_int(20, 150) * 1000,
        'content'  => [$faker->word => $faker->word],
    ];
});
