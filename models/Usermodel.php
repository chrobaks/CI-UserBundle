<?php
/**
 * Created by PhpStorm.
 * Date: 30.09.2016
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Usermodel extends MY_Model
{
    public function __construct()
    {
        parent::__construct('userentity');

        $this->view = [
            'user' => $this->entity->find('',['id'=>$this->session->userdata('id')],1)
        ];
    }
    public function update ()
    {
        if ($this->entity->update($this->post,['id'=>$this->session->userdata('id')]))
        {
            if ( ! $this->sessionservice->update()) {
                $this->messagemanager->setError('page_error_session_update');
            }
        }
    }
    public function newpass ()
    {
        $this->load->library('manager/passwordmanager');
        $passhash = $this->passwordmanager->passwordHash($this->post['password']);

        if ($this->entity->update(['password'=>$passhash], ['id'=>$this->session->userdata('id')], false)) {
            $this->messagemanager->setInfo('page_info_update_password_success');
        }
    }
}