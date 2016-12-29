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

class Emailservice
{
    private $CI;
    private $config;
    private $configEnv;
    /**
     * CONSTRUCTOR
     *
     * @access	PUBLIC
     */
    public function __construct ()
    {
        $this->CI =& get_instance();

        $this->config = [
            'protocol' => 'sendmail',
            'charset' => 'utf-8',
            'wordwrap' => true,
            'mailtype' => 'html'
        ];

        $this->configEnv = $this->CI->envservice->getConfig('email');
    }
    public function send ($mailTo,$subject,$msg,$attachment='',$sysMsg=false)
    {
        $sendOk = false;
        $mailTo = ( ! $sysMsg) ? $mailTo : $this->configEnv['mail_to_default'];
        $this->init();

        $this->CI->email->from($this->configEnv['mail_from_default'], $this->CI->lang->line('label_account_administration'));
        $this->CI->email->to($mailTo);
        $this->CI->email->subject($subject);
        $this->CI->email->message($msg);

        if ( $this->CI->email->send())
        {
            $sendOk = true;
        }else{
            $this->CI->messagemanager->setError("error_email_not_send", $this->CI->email->print_debugger());

        }

        $this->CI->email->clear(TRUE);

        return $sendOk;
    }
    private function init ()
    {
        $this->CI->load->library('email');
        $this->CI->email->initialize($this->config);
    }
}