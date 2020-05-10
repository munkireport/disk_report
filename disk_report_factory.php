<?php
/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Disk_report_model::class, function (Faker\Generator $faker) {

    $totalSize = $faker->randomElement([250, 512, 1000, 2000]) * 1000 * 1000 * 1000;
    $freeSpace = $faker->numberBetween(0, $totalSize / 2 );
    $percentage = $faker->numberBetween(0, 100);

    return [
        'totalsize' => $totalSize,
        'freespace' => $freeSpace,
        'percentage' => $percentage,
        'smartstatus' => $faker->randomElement(['Failing', 'Verified', 'Not Supported']),
        'volumetype' => $faker->randomElement(['APFS', 'bootcamp', 'Journaled HFS+']),
        'media_type' => $faker->randomElement(['hdd', 'ssd', 'fusion', 'raid']),
        'busprotocol' => 'PCI-Express',
        'internal' => $faker->randomElement([0, 1]),
        'mountpoint' => '/',
        'volumename' => 'Macintosh HD',
        'encrypted' => $faker->randomElement([0, 1]),
    ];
});
