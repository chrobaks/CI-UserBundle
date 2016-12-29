<?php
/**
 * Created by PhpStorm.
 * Date: 24.09.2016
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Passwordmanager {

    protected $CI;

    public function __construct()
    {
        $this->CI =& get_instance();
    }
    public function passwordHash ($str) {
        return password_hash($str, PASSWORD_DEFAULT);
    }
    public function passwordVerify ($str, $hash) {
        return password_verify($str, $hash);
    }
    /**
     * Generate a random password.
     *
     * getRandomPassword() will return a random password with length 8-12 of lowercase letters only.
     *
     * @access    public
     * @param    $chars_min the minimum length of password (optional, default 8)
     * @param    $chars_max the maximum length of password (optional, default 20)
     * @param    $use_upper_case boolean use upper case for letters, means stronger password (optional, default false)
     * @param    $include_numbers boolean include numbers, means stronger password (optional, default false)
     * @param    $include_special_chars include special characters, means stronger password (optional, default false)
     *
     * @return    string containing a random password
     */
    public function randomPassword($chars_min=8, $chars_max=20, $use_upper_case=false, $include_numbers=false, $include_special_chars=false)
    {
        $length = rand($chars_min, $chars_max);
        $selection = 'aeuoyibcdfghjklmnpqrstvwxz';
        if($include_numbers) {
            $selection .= "1234567890";
        }
        if($include_special_chars) {
            $selection .= "!@\"#$%&[]{}?|";
        }

        $password = "";
        for($i=0; $i<$length; $i++) {
            $current_letter = $use_upper_case ? (rand(0,1) ? strtoupper($selection[(rand() % strlen($selection))]) : $selection[(rand() % strlen($selection))]) : $selection[(rand() % strlen($selection))];
            $password .=  $current_letter;
        }

        return $password;
    }
    public function anonymPassword()
    {
        $password = $this->randomPassword();

        return password_hash($password, PASSWORD_DEFAULT);
    }
}