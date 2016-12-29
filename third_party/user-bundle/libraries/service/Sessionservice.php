<?php
/**
 * Created by PhpStorm.
 * Date: 01.10.2016
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Sessionservice
{
    protected $CI;
    private $sessionUser;

    public function __construct()
    {
        $this->CI =& get_instance();

        $this->initSessionUser();
    }
    /**
     * @function update
     * @return bool
     */
    public function update ()
    {
        if ($this->sessionUser['id'] != '')
        {
            $entity = $this->CI->entitymanager->instance('userentity');
            $user = $entity->find('*', ['id'=>$this->sessionUser['id']], 1);

            if ($user !== null) {
                foreach($user as $key => $val){
                    if (array_key_exists($key,$this->sessionUser)) {
                        $this->CI->session->set_userdata($key,$val);
                    }
                }
            }
        }

        $check = $this->checkSessionUser();

        if ($check ===  true) {
            $this->CI->session->set_userdata($this->sessionUser);
        }
        return $check;
    }
    /**
     * @function set
     * @param $data
     * @return bool
     */
    public function set ($data)
    {
        foreach($this->sessionUser as $k => $v) {
            if (is_object($data) && property_exists($data, $k)) {
                $this->sessionUser[$k] = $data->{$k};
            }
            if (is_array($data) && array_key_exists($k, $data)) {
                $this->sessionUser[$k] = $data[$k];
            }
        }

        $check = $this->checkSessionUser();

        if ($check ===  true) {
            $this->CI->session->set_userdata($this->sessionUser);
        }
        return $check;
    }
    /**
     * @function get
     * @param string $key
     * @return mixed
     */
    public function get ($key = '')
    {
        return ($key !== '') ? $this->CI->session->userdata($key) : $this->CI->session->userdata();
    }
    /**
     * @function destroy
     */
    public function destroy ()
    {
        $this->deleteDbSession($this->CI->session->userdata('lastsessid'));
        $this->deleteDbSession(session_id());
        $this->CI->session->sess_destroy();
    }
    /**
     * @function initSessionUser
     */
    private function initSessionUser ()
    {
        if ($this->CI->session->userdata('username')) {
            $this->sessionUser = $this->CI->session->userdata();
        } else {
            $this->sessionUser = array(
                'id'  => '',
                'username' => '',
                'role' => '',
                'readyMessage' => '',
                'logged_in' => FALSE
            );
        }
        $this->sessGarbageCollection();
    }
    /**
     * @function deleteDbSession
     * @param $sessid
     */
    private function deleteDbSession ($sessid)
    {
        $this->CI->db->query('DELETE FROM ci_sessions WHERE id="'.$sessid.'"');
    }
    /**
     * @function sessGarbageCollection
     */
    private function sessGarbageCollection ()
    {
        if ($this->CI->session->userdata('lastsessid')) {
            if (session_id() != $this->CI->session->userdata('lastsessid')) {
                $this->deleteDbSession($this->CI->session->userdata('lastsessid'));
                $this->CI->session->set_userdata('lastsessid',session_id());
            }
        } else {
            $this->CI->session->set_userdata('lastsessid',session_id());
        }
    }
    /**
     * @function checkSessionUser
     * @return bool
     */
    private function checkSessionUser ()
    {
        $check = true;
        $emptyKeys = ['readyMessage'];

        foreach($this->sessionUser as $k => $v) {
            if (empty($v) && ! in_array($k,$emptyKeys)) {
                $check = false;
                break;
            }
        }
        return $check;
    }
}