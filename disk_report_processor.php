<?php

use CFPropertyList\CFPropertyList;
use munkireport\processors\Processor;

class Disk_report_processor extends Processor
{
    /**
     * Process data sent by postflight
     *
     * @param string data
     * @author abn290
     **/
    public function run($plist)
    {
        $parser = new CFPropertyList();
        $parser->parse($plist, CFPropertyList::FORMAT_XML);
        $mylist = $parser->toArray();
        if (! $mylist) {
            throw new Exception("No Disks in report", 1);
        }

        // Convert old style reports from not migrated clients
        if (isset($mylist['DeviceIdentifier'])) {
            $mylist = array($mylist);
        }

        // Delete previous set
        Disk_report_model::where('serial_number', $this->serial_number)->delete();

        // Get fillable items
        $fillable = array_fill_keys((new Disk_report_model)->getFillable(), null);
        $fillable['serial_number'] = $this->serial_number;

        $save_list = [];
        foreach ($mylist as $disk) {
            
            $disk = array_change_key_case($disk, CASE_LOWER);

            // Calculate percentage
            if (isset($disk['totalsize']) && isset($disk['freespace'])) {
                $disk['percentage'] = round(($disk['totalsize'] - $disk['freespace']) /
                    max($disk['totalsize'], 1) * 100);
            }

            $disk['volumetype'] = "-";
            $disk['media_type'] = "hdd";
            if (isset($disk['solidstate']) && $disk['solidstate'] == true) {
                $disk['media_type'] = "ssd";
            }
            if (isset($disk['corestoragecompositedisk']) && $disk['corestoragecompositedisk'] == true) {
                $disk['media_type'] = "fusion";
            }
            if (isset($disk['raidmaster']) && $disk['raidmaster'] == true) {
                $disk['media_type'] = "raid";
            }
            if (isset($disk['filesystemname'])) {
                $disk['volumetype'] = $disk['filesystemname'];
            }
            if (isset($disk['content']) && $disk['content'] == 'Microsoft Basic Data') {
                $disk['volumetype'] = "bootcamp";
            }
            # Legacy FV info field
            if(isset($disk['corestorageencrypted'])) {
                $disk['encrypted'] = (int) $disk['corestorageencrypted'];
            }
            if(isset($disk['fusion']) && $disk['fusion'] == true) {
                $disk['volumetype'] = "apfs_fusion";
            }
            
            $disk['encrypted'] = (int) $disk['encrypted'];

            $save_list[] = array_replace($fillable, array_intersect_key($disk, $fillable));
            
            $this->handle_events($disk);
        }

        Disk_report_model::insertChunked($save_list);
    }

    public function handle_events($disk)
    {
        if ($disk['mountpoint'] != '/') {
            return;
        }

        // Fire event when systemdisk hits a threshold
        $type = 'success';
        $msg = '';
        $data = '';
        $lowvalue = 1000; // Lowest value (GB)
        
        // Check SMART Status
        if ($disk['smartstatus'] == 'Failing') {
            $type = 'danger';
            $msg = 'disk_report.smartstatus_failing';
        }

        foreach (conf('disk_thresholds', array()) as $name => $value) {
            if ($disk['freespace'] < $value * 1000000000) {
                if ($value < $lowvalue) {
                    $type = $name;
                    $msg = 'free_disk_space_less_than';
                    $data = json_encode(array('gb'=> $value));
                    // Store lowest value
                    $lowvalue = $value;
                }
            }
        }
        
        if ($type == 'success') {
            $this->delete_event();
        } else {
            $this->store_event($type, $msg, $data);
        }
    }
}
