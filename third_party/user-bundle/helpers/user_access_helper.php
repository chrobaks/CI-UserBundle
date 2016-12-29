<?php
/**
 * Created by PhpStorm.
 * Date: 14.10.2016
 * ---------------------------------
 * CodeIgniter Bootstrap Helper
 *
 * @package		NetcodevAuth
 * @subpackage	Helpers
 * @category	Helpers
 * @author		netcodev team
 * @link		http://www.netcodev.de
 */

defined('BASEPATH') OR exit('No direct script access allowed');
// ------------------------------------------------------------------------

if ( ! function_exists('userAccessRootExists'))
{
    /**
     * userAccessRootExists
     *
     * @param	string
     * @return	boolean
     */
    function userAccessRootExists ($user)
    {
        if ( ! array_key_exists('role',$user)) {
            return false;
        }
        return ($user['role'] == 'root') ? true:false;
    }
}