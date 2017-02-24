<?php defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Form_validation extends CI_Form_validation
{
    function __construct()
    {
        parent::__construct();
        $this->CI->lang->load('my_form_validation');
    }

    public function datetime($str)
    {
        $date_time = explode(' ',$str);
        if(sizeof($date_time)==2)
        {
            $date = $date_time[0];
            $date_values = explode('-',$date);
            if((sizeof($date_values)!=3) || !checkdate( (int) $date_values[1], (int) $date_values[2], (int) $date_values[0]))
            {
                return FALSE;
            }
            $time = $date_time[1];
            $time_values = explode(':',$time);
            if((int) $time_values[0]>23 || (int) $time_values[1]>59 || (int) $time_values[2]>59)
            {
                return FALSE;
            }
            return TRUE;
        }
        return FALSE;
    }

    public function phone_number($str, $param)
    {
        if($param===FALSE) $param=11;
        $characters = array('+','-','.',' ','(',')');
        $str = str_replace($characters,'',$str);
        if(strlen($str)==$param) return TRUE;
        return FALSE;
    }

    /**
     * Is Unique Except
     *
     * Check if the input value doesn't already exist
     * in the specified database field, except in the row mentioned with identifying field.
     *
     * @param	string	$str
     * @param	string	$field with the format table.field_to_check_uniqueness.identifying_field.identifying_field_value
     * @return	bool
     */
    public function is_unique_except($str,$field)
    {
        list($table,$field,$id_column,$id_value) = sscanf($field, '%[^.].%[^.].%[^.].%[^.]');
        return isset($this->CI->db)
            ? ($this->CI->db->limit(1)->get_where($table, array($field => $str, $id_column.' != ' => $id_value))->num_rows() === 0)
            : FALSE;
    }

    public function email_domain($email, $param)
    {
        $allowed_domains = explode(',',$param);
        if(empty($allowed_domains)) return TRUE;

        if (filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            $email_arr = explode('@', $email);
            $domain = array_pop($email_arr);

            if ( ! in_array($domain, $allowed_domains))
            {
                return FALSE;
            }
        }
        else
        {
            return FALSE;
        }
        return TRUE;
    }

    /**
     * Value should be within an array of values
     *
     * @param	string
     * @param	string
     * @return	bool
     */
    public function not_in_list($value, $list)
    {
        return !in_array($value, explode(',', $list), TRUE);
    }
}