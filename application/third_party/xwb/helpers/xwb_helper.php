<?php if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}
/**
 * XWB Purchasing
 *
 * XWB Purchasing is located here
 *
 * @package     XWB Purchasing
 * @author      Jay-r Simpron
 * @copyright   Copyright (c) 2017, Jay-r Simpron
 */


if (! function_exists('do_shortcode')) {
/**
     * Convert shortcode to string
     *
     * @param type|string $content
     * @param type|array $conditions
     * @return string
     */
    function do_shortcode($content = "", $conditions = array())
    {
        $message = "";
        foreach ($conditions as $key => $value) {
            $message = str_replace('['.$key.']', $value, $content);
            $content = $message;
        }
        return $message;
    }

}



if (! function_exists('pre')) {
/**
     * Run print_r or vardump php function
     * @param array|string $data
     * @param type|bool $die
     * @param type|string $var_dump
     * @return string
     */
    function pre($data = '', $die = false, $print_r = false)
    {
        echo "<pre>";
        if ($print_r == true) {
            print_r($data);
        } else {
            var_dump($data);
        }
        echo "</pre>";
        if (!$die) {
            die('die()');
        }
    }

}


if (! function_exists('in_array_r')) {
/**
     * Check if array exists in multidimensional array
     *
     * @param string $needle
     * @param array $haystack
     * @param type|bool $strict
     * @return bool
     */
    function in_array_r($needle, $haystack, $strict = false)
    {
        foreach ($haystack as $item) {
            if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && in_array_r($needle, $item, $strict))) {
                return true;
            }
        }
        return false;
    }

}


if (! function_exists('getParentArrayIndex')) {
/**
     * Get the parent key from multidimentional array
     *
     * @param  [string] $indexName
     * @param  [string] $name
     * @param  [array] $array
     * @return [string]
     */
    function getParentArrayIndex($indexName, $name, $array)
    {
        foreach ($array as $key => $value) {
            if (is_array($value) && $value[$indexName] == $name) {
                return $key;
            }
        }
        return null;
    }

}


if (! function_exists('objectToArray')) {
/**
     * Convert object to array
     *
     * @param  [Object] $obj
     * @return [array]
     */
    function objectToArray($obj)
    {
        if (!is_array($obj) && !is_object($obj)) {
            return $obj;
        }
        if (is_object($obj)) {
            $obj = get_object_vars($obj);
        }
        return array_map(__FUNCTION__, $obj);
    }

}

if (! function_exists('objectToArray')) {
/**
     * lz = leading zero
     *
     * Add leading zero for those tens value
     *
     * @param  [Int] $num
     * @return [string]
     */
    function lz($num)
    {
        return (strlen($num) < 2) ? "0{$num}" : $num;
    }

}


if (! function_exists('getConfig')) {
/**
     * Get configuration value
     *
     * @param  [string] $config_name [Configuration Name]
     * @return [string]              [Configuration Value]
     */
    function getConfig($config_name)
    {
        $CI =& get_instance();
        $res = $CI->db->get_where('settings', array('name'=>$config_name))->row();
        if (is_null($res)) {
            return "";
        } else {
            return $res->description;
        }
    }

}


if (! function_exists('priority_label')) {
/**
     * Get priority level label
     *
     * @param int $level
     * @return string
     */
    function priority_label($level)
    {
        switch ($level) {
            case 1:
                  $label = '<span class="label label-info">Normal</span>';

                break;
            case 2:
                  $label = '<span class="label label-danger">Urgent</span>';

                break;
            default:
                  $label = '<span class="label label-info">Normal</span>';

                break;
        }

        return $label;
    }

}

if (! function_exists('priority_time')) {
/**
     * Get time duration for priority level
     *
     * @param int $level
     * @param string|null $date_from
     * @return string
     */
    function priority_time($level, $date_from = null)
    {

        if ($level == 1) {
            $duration = getConfig('normal_duration');
        } elseif ($level == 2) {
            $duration = getConfig('urgent_duration');
        }




        $date_to = strtotime($date_from);
        $date_to = strtotime('+'.$duration.' days', $date_to);
        $date_to = date('Y-m-d H:i:s', $date_to);
        $date_from =  date('Y-m-d H:i:s');
        return time_togo($date_from, $date_to);
    }

}


if (! function_exists('time_elapse')) {
/**
     * Calculate time lapse
     *
     * @param string $hours
     * @return string
     */
    function time_elapse($hours)
    {
        $to_time = new \DateTime(Date('Y-m-d H:i:s'));
        $from_time = new \DateTime($hours);
        $interval = $to_time->diff($from_time);
        $hours = $interval->h;
        $hours = $hours + ($interval->days*24);
        $minSec = $interval->format('%i:%s');
        $hours = $hours.':'.$minSec;
        $age = '';
        list($hour, $min, $sec) = explode(":", $hours);
        if ($hour > 24) {
            $days = round($hour / 24);
        } else {
            $days = 0;
        }

        if ($days >= 61) {
            $date = date('M d, Y', strtotime("-$hour hours"));
            return $date;
        } else if ($days >= 1) {
            $age = "$days day";
            if ($days > 1) {
                $age .= "s";
            }
        } else {
            if ($hour > 0) {
                $hour = ltrim($hour, '0');
                $age .= " $hour hour";
                if ($hour > 1) {
                    $age .= "s";
                }
            }
            if ($min > 0) {
                $min = ltrim($min, '0');
                if (!$min) {
                    $min = '0';
                }
                $age .= " $min min";
                if ($min != 1) {
                    $age .= "s";
                }
            }
        }

        if ($min < 1 and $hour < 1) {
            $age = 'a few seconds';
        }
        $age .= ' ago';
        return $age;
    }


}



if (! function_exists('child_category')) {
/**
     * Get Child Product Category
     *
     * @param int $cate_id
     * @return array
     */
    function child_category($cate_id)
    {

        $CI =& get_instance();
        $CI->load->model('product_category/Product_category_model', 'Prodcat');
        return $CI->Prodcat->getChildCat($cate_id);
    }

}



if (! function_exists('array_append')) {
/**
     * Append array
     *
     * @param array $existing_array
     * @param array $array_to_append
     * @return array
     */
    function array_append($existing_array, $array_to_append)
    {
        foreach ($array_to_append as $key => $value) {
            if (!in_array($key, array_keys($array_to_append))) {
                $existing_array[$key] = $value;
            } else {
                $existing_array['new_'.$key] = $value;
            }
        }

        return $existing_array;
    }

}




if (! function_exists('getMessage')) {
/**
     * Get Email Message for the process
     *
     * @param string $process_key
     * @return string
     */
    function getMessage($process_key)
    {
        $CI = $CI =& get_instance();
        $e = $CI->db->get_where('emails', array('process_key'=>$process_key));
//pre($CI->db->last_query());
        if ($e->num_rows()>0) {
            $data['message'] = $e->row()->message;
            $data['subject'] = $e->row()->subject;
        } else {
            $data['message'] = "";
            $data['subject'] = "";
        }
        return $data;
    }

}


if (! function_exists('do_shortcode')) {
/**
     * Convert shortcode to string
     *
     * @param type|string $content
     * @param type|array $conditions
     * @return string
     */
    function do_shortcode($content = "", $conditions = array())
    {
        $message = "";
        foreach ($conditions as $key => $value) {
            $message = str_replace('['.$key.']', $value, $content);
            $content = $message;
        }
        return $message;
    }

}


if (! function_exists('getPaymentTerm')) {
    /**
     * Get Payment Term label
     *
     * @param type|string $term
     * @return string
     */
    function getPaymentTerm($term = "")
    {
        switch ($term) {
            case 'cash':
                  $label = "Cash";

                break;
            case 'open_account':
                  $label = "Open Account";

                break;
            case 'secured_account':
                  $label = "Secured Account";

                break;
          
            default:
                  $label = "Cash";

                break;
        }

        return $label;
    }

}

if (! function_exists('getExpenditureName')) {
/**
     * Get Expenditure Name
     *
     * @param type|string $ex
     * @return string
     */
    function getExpenditureName($ex = "")
    {
        switch ($ex) {
            case 'OPEX':
                  $label = "Operating Expenditure";

                break;
            case 'CAPEX':
                  $label = "Capital Expenditure";

                break;
            default:
                  $label = "NONE";

                break;
        }

        return $label;
    }

}


if (! function_exists('time_togo')) {
/**
     * compute time to go and time ago
     *
     * @param string $date_from
     * @param string $date_to
     * @return string
     */
    function time_togo($date_from, $date_to)
    {
        $is_valid = is_date_time_valid($date_to);
        if ($is_valid) {
            $timestamp = strtotime($date_to);
            $difference = strtotime($date_from) - $timestamp;
            $periods = array("sec", "min", "hour", "day", "week", "month", "years", "decade");
            $lengths = array("60", "60", "24", "7", "4.35", "12", "10");
            $overdue = "";
            $class = "badge";
            if ($difference > 0) {
            // this was in the past time
                $ending = "ago";
                $overdue = "Overdue: ";
                $class = "label label-danger";
            } else {
            // this was in the future time
                $difference = -$difference;
                $ending = "to go";
            }
            for ($j = 0; $difference >= $lengths[$j]; $j++) {
                        $difference /= $lengths[$j];
            }

            $difference = round($difference);
            if ($difference != 1) {
                $periods[$j].= "s";
            }


            $text = '<span class="'.$class.'">'.$overdue.$difference." ".$periods[$j]." ".$ending.'</span>';
            return $text;
        } else {
            return 'Date Time must be in "yyyy-mm-dd hh:mm:ss" format';
        }
    }

}


if (! function_exists('is_date_time_valid')) {
/**
     * Validate date
     *
     * @param string $date
     * @return string
     */
    function is_date_time_valid($date)
    {
        if (date('Y-m-d H:i:s', strtotime($date)) == $date) {
            return true;
        } else {
            return false;
        }
    }


}


if (! function_exists('getSuplliersFromRequest')) {
/**
     * Get Supplier from request item
     *
     * @param  integer $request_id   [Request ID]
     * @param  integer $product_id   [Product ID]
     * @param  string  $product_name [Product Name]
     * @return Object                [Results]
     */
    function getSuplliersFromRequest($request_id = 0, $product_id = 0, $product_name = "")
    {
        $CI =& get_instance();
        $CI->load->model('request/Request_model', 'Request');
        $results = $CI->Request->getSuplliersFromRequest($request_id, $product_id, $product_name);
        return $results;
    }


}

if (! function_exists('getSuplliersFromCanvassed')) {
/**
     * Get Supplier from canvassed item
     *
     * @param  integer $request_id   [Request ID]
     * @param  integer $product_id   [Product ID]
     * @param  string  $product_name [Product Name]
     * @return Object                [Results]
     */
    function getSuplliersFromCanvassed($request_id = 0, $product_id = 0, $product_name = "")
    {
        $CI =& get_instance();
        $CI->load->model('request/Request_model', 'Request');
        $results = $CI->Request->getSuplliersFromCanvassed($request_id, $product_id, $product_name);
//pre($CI->db->last_query(),true);
        return $results;
    }


}

if (! function_exists('checkItemExistsPICanvassed')) {
  
    /**
     * Chech if item exists from po_item table
     * @param  integer $request_id    [Request ID]
     * @param  integer $product_id    [Product ID]
     * @param  string  $product_name  [Product Name]
     * @param  string  $supplier_name [Supplier Name]
     * @return boolean
     */
    function checkItemExistsPICanvassed($request_id = 0, $product_id = 0, $product_name = "", $supplier_name = "")
    {
        $CI =& get_instance();
        $CI->load->model('item/Item_model', 'Item');
        $results = $CI->Item->checkItemExistsPICanvassed();
        if ($results->num_rows()>0) {
            return true;
        } else {
            return false;
        }
    }

}
