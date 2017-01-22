<?php
/**
 * Created by PhpStorm.
 * Date: 03.10.2016
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Signupmodel extends MY_Model
{
    private $confirmationProperties;

    public function __construct()
    {
        parent::__construct('userentity');

        $this->load->library('manager/passwordmanager');

        $this->view = [
            'status' => 'invitation',
            'tplTitle' => 'Registrierung',
            'title' => 'Bestätigung',
            'username' => '',
            'confirmationHash' => ''
        ];
        $this->confirmationProperties = [];
    }
    public function create ()
    {
        $salt = $this->userservice->getSalt();
        $password = $this->passwordmanager->passwordHash($this->post['password'].$salt);

        $userData = [
            'username'=>$this->post['username'],
            'password'=>$password,
            'salt'=>$salt,
            'email'=>$this->post['email'],
            'role' => 'user'
        ];
        if ($this->entity->create($userData))
        {
            $userData['id'] = $this->entity->getLastInsertId();
            $userData['confirmation_type'] = 'signup';

            //create confirmation properties
            $this->userservice->setConfirmationProperties($userData);

            //create confirmation entry for user confirmation
            if ($this->userservice->setConfirmationEntry())
            {
                $confirmationHash = $this->userservice->getConfirmationHash();
                $format = [$this->post['username'],$confirmationHash,$confirmationHash];
                $mailText = $this->lang->line('signup_confirmation_mail');
                $mailText = vsprintf($mailText,$format);

                if ($this->userservice->sendConfirmationMail($this->lang->line('signup_confirmation_mail_subject'),$mailText))
                {
                    $confirmationText = $this->lang->line('confirmation_text');
                    $this->view['confirmationText'] = sprintf($confirmationText,$this->post['username']);
                    $this->modelActionOk = true;
                }
            }
        }
    }
    public function confirmation()
    {
        $confirmationHash = (isset($this->post['cnfhsh'])) ? $this->post['cnfhsh'] : $this->uri->segment(3);
        $where = ['confirmation_hash'=>$confirmationHash,'confirmation_type' => 'signup'];
        $userConfirmation = $this->entity->findDependence('userconfirmation', 'id, user_id, pass_hash', $where,1);

        if ($userConfirmation != null)
        {
            $this->view['status'] = 'confirm';
            $this->view['confirmationHash'] = $confirmationHash;

            if (isset($this->post['password']) && isset($this->post['username']))
            {
                $where = [
                    'id' => $userConfirmation->user_id,
                    'username' => $this->post['username'],
                    'password' => $userConfirmation->pass_hash
                ];

                if ($this->entity->update(['confirmation'=>1],$where)) {
                    if ($this->userservice->signin($this->post))
                    {
                        $this->modelActionOk = $this->entity->deleteDependence('userconfirmation', ['id'=>$userConfirmation->id]);
                    }
                }
            }
        }
        if ( ! $this->modelActionOk && $userConfirmation == null)
        {
            $this->messagemanager->setError('page_error_confirmation');
            $this->view['status'] = 'confirmerror';
        }
    }
}