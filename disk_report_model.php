<?php

use munkireport\models\MRModel as Eloquent;

class Disk_report_model extends Eloquent
{
    protected $table = 'diskreport';

    protected $fillable = [
      'serial_number',
      'totalsize',
      'freespace',
      'percentage',
      'smartstatus',
      'volumetype',
      'media_type',
      'busprotocol',
      'internal',
      'mountpoint',
      'volumename',
      'encrypted',
    ];
}
