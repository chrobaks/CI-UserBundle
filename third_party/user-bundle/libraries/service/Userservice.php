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

class Userservice
{
    private $CI;
    private $user;
    private $entity;
    private $post;
    private $confirmationProperties;
    /**
     * CONSTRUCTOR
     *
     * @access	PUBLIC
     */
    public function __construct ()
    {
        $this->CI =& get_instance();
        $this->user = null;
        $this->entity = $this->CI->entitymanager->instance('userentity');
        $this->post = [];
        $this->confirmationProperties = [];
    }
    public function signin ($post)
    {
        $actionOk = false;
        $this->user = $this->entity->find('*',['username'=>$post['username']],1);

        if ($this->user !== null) {
            if($this->CI->passwordmanager->passwordVerify($post['password'].$this->user->salt,$this->user->password)) {
                if ($this->user->confirmation === '1') {
                    if ($this->loginSession()) {
                        $this->loginUpdate();
                        $actionOk = true;
                    } else {
                        $this->CI->messagemanager->setError('page_error_session_start');
                    }
                } else {
                    $this->CI->messagemanager->setError('page_error_user_confirmation');
                }
            } else {
                $this->CI->messagemanager->setError('page_error_user_undefined');
            }
        } else {
            $this->CI->messagemanager->setError('page_error_user_notfound');
        }
        return $actionOk;
    }
    public function deleteConfirmationEntry ($id)
    {
        return $this->entity->deleteDependence ('userconfirmation', ['id'=>$id]);
    }
    public function setConfirmationEntry ()
    {
        return $this->entity->createDependence ('userconfirmation', $this->confirmationProperties, false);
    }
    public function setConfirmationProperties ($userData)
    {
        $this->post = $userData;

        $this->confirmationProperties = [
            'user_id' => $userData['id'],
            'pass_hash' => $userData['password'],
            'confirmation_type' => $userData['confirmation_type'],
            'confirmation_hash' => $this->CI->passwordmanager->randomPassword(12, 30, true, true)
        ];
    }
    public function sendConfirmationMail ($subject, $mailText)
    {
        if ($this->CI->emailservice->send($this->post['email'], $subject, $mailText))
        {
            return true;
        }
        return false;
    }
    public function getConfirmationHash ()
    {
        return $this->confirmationProperties['confirmation_hash'];
    }
    public function getSalt ()
    {
        $salt = $this->CI->passwordmanager->randomPassword(16,30,true,true);
        $query = $this->entity->find('id',['salt'=>$salt],1);

        if ($query == null) {
            return $salt;
        } else {
            return $this->getSalt();
        }
    }
    private function loginSession ()
    {
        $this->user->logged_in = true;
        return $this->CI->sessionservice->set($this->user);
    }
    private function loginUpdate ()
    {
        $now = time();
        $userData = [
            'last_login' => unix_to_human($now, FALSE, 'eu')
        ];
        $where = [
            'id' => $this->user->id,
            'username' => $this->user->username
        ];

        $this->entity->update($userData, $where, false);
    }
}