<?php
/**
 * Created by PhpStorm.
 * Date: 03.10.2016
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Loginmodel extends MY_Model
{
    public function __construct()
    {
        parent::__construct('userentity');

        $this->load->library('manager/passwordmanager');

    }
    public function login ()
    {
        $this->modelActionOk = $this->userservice->signin($this->post);
    }
    public function passwordforgot()
    {
        $user = $this->entity->find('id, username, password',$this->post,1);

        if ($user !== NULL)
        {
            $userData = [
                'id'=>$user->id,
                'username'=>$user->username,
                'password'=>$user->password,
                'email'=>$this->post['email'],
                'confirmation_type' => 'passwordforgot'
            ];
            //create confirmation properties
            $this->userservice->setConfirmationProperties($userData);

            //create confirmation entry for user confirmation
            if ($this->userservice->setConfirmationEntry())
            {
                $confirmationHash = $this->userservice->getConfirmationHash();
                $format = [$user->username,$confirmationHash,$confirmationHash];
                $mailText = $this->lang->line('password_forgot_mail');
                $mailText = vsprintf($mailText,$format);

                if ($this->userservice->sendConfirmationMail($this->lang->line('password_forgot_mail_subject'),$mailText))
                {
                    $this->messagemanager->setInfo('page_info_signup_confirm_email');
                    $this->modelActionOk = true;
                }
            }
        } else {
            $this->messagemanager->setError('page_error_email_nouser');
        }
    }
    public function confirmation()
    {
        $confirmationHash = (isset($this->post['cnfhsh'])) ? $this->post['cnfhsh'] : $this->uri->segment(3);
        $where = ['confirmation_hash'=>$confirmationHash,'confirmation_type' => 'passwordforgot'];
        $userConfirmation = $this->entity->findDependence('userconfirmation', 'id, user_id, pass_hash', $where,1);
        $this->view = [
            'status' => 'confirm',
            'username' => '',
            'confirmationHash' => ''
        ];

        if ($userConfirmation != null)
        {
            $this->view['status'] = 'confirm';
            $this->view['confirmationHash'] = $confirmationHash;

            if (isset($this->post['password']) && isset($this->post['username']))
            {
                $password = $this->passwordmanager->passwordHash($this->post['password']);
                $where = [
                    'id' => $userConfirmation->user_id,
                    'username' => $this->post['username'],
                    'password' => $userConfirmation->pass_hash
                ];
                if ($this->entity->update(['password'=>$password],$where))
                {
                    $this->entity->deleteDependence('userconfirmation', ['id' => $userConfirmation->id]);
                    $this->modelActionOk = $this->userservice->signin($this->post);
                }
            }
        }
        if ( ! $this->modelActionOk && $userConfirmation == null)
        {
            $this->messagemanager->setError('page_error_password_update');
            $this->view['status'] = 'confirmerror';
        }
    }
}