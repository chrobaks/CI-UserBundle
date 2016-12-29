<?php
/**
 * Created by PhpStorm.
 * Date: 30.09.2016
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Ajaxmodel extends MY_Model
{
    private $filterHelp;

    public function __construct()
    {
        parent::__construct();
        $this->filterHelp = ['license'];
    }
    public function module ()
    {
        $actionOk = false;

        switch (isset($this->post['data-mod'])) {
            case('lang'):
                if ($this->languagemodule->changeLang($this->post['lang']))
                {
                    $this->responseData = ["redirect"=>"redirect"];
                    $actionOk = true;
                } else {
                    $this->messagemanager->setError('page_error_application');
                }
                break;
        }
        return $actionOk;
    }
    public function help ()
    {
        if (isset($this->post['val']) && in_array($this->post['val'], $this->filterHelp))
        {
            $this->languagemodule->loadRouteLang ('public/'.$this->post['val']);
            $response = $this->lang->line('license', false);

            if ($response) {
                $this->responseData["data"] = $response;
            }
        }
    }
    public function setContact ()
    {
        $this->languagemodule->loadRouteLang ('public/contact');
        $subject = $this->lang->line('txt_contact_subject', false);
        $message = $this->lang->line('txt_contact', false);

        if ($message && $subject)
        {
            $message = sprintf($message,$this->post['name'],$this->post['email'],$this->post['message']);

            if ($this->emailservice->send('', $subject, $message, '', true)) {
                $this->messagemanager->setInfo('page_info_last_action_success');
            } else {
                $this->messagemanager->setError('page_error_email_send');
            }
        } else {
            $this->messagemanager->setError('page_error_application');
        }
    }
}