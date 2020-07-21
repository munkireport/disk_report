<?php

/**
 * Disk report controller class
 *
 * @package munkireport
 * @author AvB
 **/
class Disk_report_controller extends Module_controller
{
    public function __construct()
    {
        $this->module_path = dirname(__FILE__);
        
        // Add local config
        configAppendFile(__DIR__ . '/config.php');
    }

    /**
     * Retrieve data in json format
     *
     * @return void
     * @author
     **/
    public function get_data($serial_number = '')
    {
        jsonView(
            Disk_report_model::select("diskreport.*")
                ->where("diskreport.serial_number", $serial_number)
                ->filter()
                ->get()
                ->toArray()
        );
    }

    /**
     * Get filevault statistics
     *
     * @return void
     * @author
     **/
    public function get_filevault_stats($mount_point = '/')
    {
        jsonView(
            Disk_report_model::selectRaw("COUNT(CASE WHEN encrypted = 1 AND mountpoint = '/' THEN 1 END) AS encrypted")
                ->selectRaw("COUNT(CASE WHEN encrypted = 0 AND mountpoint = '/' THEN 1 END) AS unencrypted")
                ->filter()
                ->first()
                ->toLabelCount()
        );
    }
    
     /**
     * Get disk type
     *
     * @return void
     * @author tuxudo
     **/
    public function get_disk_type()
    {
        jsonView(
            Disk_report_model::selectRaw("COUNT(CASE WHEN media_type = 'hdd' THEN 1 END) AS hdd")
                ->selectRaw("COUNT(CASE WHEN media_type = 'ssd' THEN 1 END) AS ssd")
                ->selectRaw("COUNT(CASE WHEN media_type = 'fusion' THEN 1 END) AS fusion")
                ->selectRaw("COUNT(CASE WHEN media_type = 'raid' THEN 1 END) AS raid")
                ->filter()
                ->first()
                ->toLabelCount()
        );
    }
    
     /**
     * Get filesystem type
     *
     * @return void
     * @author tuxudo
     **/
    public function get_volume_type()
    {
        jsonView(
            Disk_report_model::selectRaw("COUNT(CASE WHEN volumetype = 'APFS' THEN 1 END) AS apfs")
                ->selectRaw("COUNT(CASE WHEN volumetype = 'bootcamp' THEN 1 END) AS bootcamp")
                ->selectRaw("COUNT(CASE WHEN volumetype = 'Journaled HFS+' THEN 1 END) AS hfs")
                ->filter()
                ->first()
                ->toLabelCount()
        );
    }
    
    /**
     * Get global used/free
     *
     * @return void
     * @author tuxudo
     **/
    public function get_global_used_free()
    {
        jsonView(
            Disk_report_model::selectRaw("SUM(totalsize) AS total")
                ->selectRaw("SUM(freespace) AS free")
                ->selectRaw("SUM(totalsize)-SUM(freespace) AS used")
                ->filter()
                ->first()
                ->toArray()
        );
    }
    
    /**
     * Get statistics
     *
     * @return void
     * @author
     **/
    public function get_stats($mount_point = '/')
    {
        $out = [];
        $thresholds = conf('disk_thresholds', array('danger' => 5, 'warning' => 10));
        $out['thresholds'] = $thresholds;
        $level1 = $thresholds['danger'] . '000000000';
        $level2 = $thresholds['warning'] . '000000000';
        $level2_minus_one = $level2 - 1;

        $out['stats'] = Disk_report_model::selectRaw("COUNT(CASE WHEN freespace > $level2_minus_one THEN 1 END) AS success")
                            ->selectRaw("COUNT(CASE WHEN freespace < $level2 THEN 1 END) AS warning")
                            ->selectRaw("COUNT(CASE WHEN freespace < $level1 THEN 1 END) AS danger")
                            ->where("mountpoint", "/")
                            ->filter()
                            ->first();

        jsonView($out);
    }
    
    /**
     * Get statistics
     *
     * @return void
     * @author
     **/
    public function get_smart_stats()
    {
        jsonView(
            Disk_report_model::selectRaw("COUNT(CASE WHEN smartstatus='Failing' THEN 1 END) AS failing")
                ->selectRaw("COUNT(CASE WHEN smartstatus='Verified' THEN 1 END) AS verified")
                ->selectRaw("COUNT(CASE WHEN smartstatus='Not Supported' THEN 1 END) AS unsupported")
                ->filter()
                ->first()
                ->toLabelCount()
        );
    }
} // END class disk_report_module
