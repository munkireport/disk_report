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
     * Default method
     *
     * @author AvB
     **/
    public function index()
    {
        echo "You've loaded the disk report module!";
    }

    /**
     * Retrieve data in json format
     *
     * @return void
     * @author
     **/
    public function get_data($serial_number = '')
    {
        $obj = new View();

        if (! $this->authorized()) {
            $obj->view('json', array('msg' => array('error' => 'Not authenticated')));
            return;
        }
        $out = array();
        $model = new Disk_report_model;
        foreach ($model->retrieve_records($serial_number) as $res) {
            $out[] = $res->rs;
        }
        $obj->view('json', array('msg' => $out));
    }

    /**
     * Get filevault statistics
     *
     * @return void
     * @author
     **/
    public function get_filevault_stats($mount_point = '/')
    {
        $disk_report = new Disk_report_model;
        $out = [];
        foreach($disk_report->get_filevault_stats($mount_point) as $label => $value){
            $out[] = ['label' => $label, 'count' => $value];
        }
        jsonView($out);
    }
    
     /**
     * Get disk type
     *
     * @return void
     * @author tuxudo
     **/
    public function get_disk_type()
    {
        $disk_report = new Disk_report_model;
        $out = [];
        foreach($disk_report->get_disk_type() as $label => $value){
            $out[] = ['label' => $label, 'count' => $value];
        }
        jsonView($out);
    }
    
     /**
     * Get filesystem type
     *
     * @return void
     * @author tuxudo
     **/
    public function get_volume_type()
    {
        $disk_report = new Disk_report_model;
        $out = [];
        foreach($disk_report->get_volume_type() as $label => $value){
            $out[] = ['label' => $label, 'count' => $value];
        }
        jsonView($out);
    }
    
    /**
     * Get global used/free
     *
     * @return void
     * @author tuxudo
     **/
    public function get_global_used_free()
    {
        $obj = new View();

        if (! $this->authorized()) {
            $obj->view('json', array('msg' => array('error' => 'Not authenticated')));
            return;
        }
        
        $disk_report = new Disk_report_model;
        $out = array();

        $obj->view('json', array('msg' => $disk_report->get_global_used_free()));
    }
    
    /**
     * Get statistics
     *
     * @return void
     * @author
     **/
    public function get_stats($mount_point = '/')
    {
        $obj = new View();

        if (! $this->authorized()) {
            $obj->view('json', array('msg' => array('error' => 'Not authenticated')));
            return;
        }
                $disk_report = new Disk_report_model;
                $out = array();
                $thresholds = conf('disk_thresholds', array('danger' => 5, 'warning' => 10));
                $out['thresholds'] = $thresholds;
                $out['stats'] = $disk_report->get_stats(
                    $mount_point,
                    $thresholds['danger'],
                    $thresholds['warning']
                );

                $obj->view('json', array('msg' => $out));
    }
    
    /**
     * Get statistics
     *
     * @return void
     * @author
     **/
    public function get_smart_stats()
    {
        $disk_report = new Disk_report_model;
        $out = [];
        foreach($disk_report->getSmartStats() as $label => $value){
            $out[] = ['label' => $label, 'count' => $value];
        }
        jsonView($out);
    }
} // END class disk_report_module
