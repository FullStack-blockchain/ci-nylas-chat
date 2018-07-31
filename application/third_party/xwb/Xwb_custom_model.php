<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Xwb_custom_model extends CI_Model
{
    

    /**
     * Generate data for gauge
     *
     * @param array $request
     * @return array
     */
    public function generateGaugeData($request)
    {
        $r_data = [];

        //key = status, value = progress label key/level
        $status_label = array(
            1 => 0,
            2 => 1,
            3 => 2,
            4 => 3,
            5 => 1,
            6 => 3,
            7 => 3,
            8 => 3,
            9 => 3,
            10 => 3,
            11 => 3,
            12 => 4,
            13 => 5,
            );
        
        foreach ($request as $key => $value) {
            if (array_key_exists($value->status, $status_label)) {
                $r_data[] = array(
                    'request_name' => $value->request_name,
                    'req_id' => $value->id,
                    'status'=> $value->status,
                    'status_level'=> $status_label[$value->status],
                    );
            }
        }

        return $r_data;
    }

    /**
     * Set all gauge level label
     *
     * @return array
     */
    public function progressLabel()
    {
        $progress_labels = array(
            'Encode New Request',
            'Head/Recommending Approval',
            'Canvassing',
            'Budget/Board Approval',
            'Purchasing',
            'Done',
            );
        return $progress_labels;
    }
}
