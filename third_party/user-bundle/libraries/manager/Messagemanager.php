<?php
/*
|--------------------------------------------------------------------------
| CLASS DESCRIPTION
|
| @name	   : MY_AppMessage
| @type	   : LIBARY MODULE CLASS
| @version : 0.2
|--------------------------------------------------------------------------
*/
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Messagemanager
{
    private $CI;
    private $info;
    private $error;
    /**
     * CONSTRUCTOR
     *
     * @access	PUBLIC
     */
    public function __construct ()
    {
        $this->CI =& get_instance();
        $this->info = array();
        $this->error = array();
    }
    /**
     * GET INFO
     *
     * @access	PUBLIC
     * @param   string
     * @return	string
     */
    public function getInfo ($break = "")
    {
        $result = "";
        if( ! empty($this->info)){
            $break = ($break == "") ? "<br>" : $break;
            $result = implode($break,$this->info);
            $this->info = array();
        }
        return $result;
    }
    /**
     * GET ERROR
     *
     * @access	PUBLIC
     * @param   string
     * @return	string
     */
    public function getError ($break = "")
    {
        $result = "";
        if( ! empty($this->error)){
            $break = ($break == "") ? "<br>" : $break;
            $result = implode($break,$this->error);
            $this->error = array();
        }
        return $result;
    }
    /**
     * ERROR Length
     *
     * @access	PUBLIC
     * @return	number
     */
    public function errorLength ()
    {
        return count($this->error);
    }
    /**
     * SET INFO
     *
     * @access	public
     * @param   string
     */
    public function setInfo ($str)
    {
        if(is_string($str) && ! empty($str))
        {
            $str = trim($str);
            $str = ( $this->CI->lang->line($str, FALSE) ) ? $this->CI->lang->line($str) : $str;

            $this->info[] = $str;
        }
    }
    /**
     * SET REDIRECT INFO
     *
     * @access	public
     * @param   string
     */
    public function setRedirectInfo ($str)
    {
        if(is_string($str) && ! empty($str))
        {
            $this->CI->sessionservice->set(['readyMessage' => $this->CI->lang->line(trim($str), FALSE)]);
        }
    }
    /**
     * SET ERROR
     *
     * @access	public
     * @param   string|array
     * @param   string
     * @param   array
     */
    public function setError ($errorKeys, $errorTxt = '', $format = [])
    {
        $txt = "";

        if( ! empty($errorKeys))
        {
            $txt = $this->getLang ($errorKeys, $format);
        }
        if( ! empty($errorTxt)){
            $txt .= $errorTxt;
        }
        if( ! empty($txt)){
            $this->error[] = $txt;
        }
    }
    /**
     * GET LANG TEXT
     *
     * @access	private
     * @param   string|array
     * @param   array
     * @return string
     */
    private function getLang ($args, $format)
    {
        $txt = "";

        if( ! empty($args))
        {
            if(is_array($args)){
                foreach($args as $k){ $txt .= $this->CI->lang->line(trim($k), FALSE); }
            }else{
                $txt = $this->CI->lang->line(trim($args), FALSE);
            }
            if ( ! empty($format)) {
                $txt = vsprintf($txt, $format);
            }
        }
        return $txt;
    }
}