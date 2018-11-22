<?php

return [
  'disk_thresholds' => [
      'danger' => env('DISK_REPORT_THRESHOLD_DANGER', 5),
      'warning' => env('DISK_REPORT_THRESHOLD_WARNING', 10),
    ],
];
