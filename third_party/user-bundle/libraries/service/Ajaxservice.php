<?php
/*
|--------------------------------------------------------------------------
| CLASS DESCRIPTION
|
| @name	   : MY_Ajax
| @type	   : LIBARY MODULE CLASS
| @version : 0.2
|--------------------------------------------------------------------------
*/
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Ajaxservice
{
    private $CI;
    /**
     * CONSTRUCTOR
     *
     * @access	PUBLIC
     */
    public function __construct ()
    {
        $this->CI =& get_instance();
    }
    /**
     * RESPONSE
     *
     * @access	PUBLIC
     * @param   string
     * @param   string
     * @param   mixed
     */
    public function response ($message = '', $error = '', $responseData = '')
    {
        $modelError = $this->CI->messagemanager->getError();
        $response = [];

        if ($error != '' || ! empty($modelError))
        {
            if(! empty($modelError)) {
                $error = $modelError;
            }
            $response = ['error'=>$error];
        } else {

            $modelInfo = $this->CI->messagemanager->getInfo();

            if ( ! empty($modelInfo)) {
                $message = $modelInfo;
            }
            $response = ['message'=>$message];
        }

        if(is_array($responseData) && ! empty($responseData))
        {
            $response = array_merge($response,$responseData);
        }

        print json_encode($response);
        exit(0);
    }
}