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

if ( ! function_exists('bootstrapAlert'))
{
    /**
     * bootstrapAlert
     *
     * @param	array
     * @return	string	html
     */
    function bootstrapAlert($tplData)
    {
        $res = '';

        if(isset($tplData['error']) && ! empty($tplData['error']))
        {
            $res = '<div class="alert alert-danger" role="alert">'.$tplData['error'].'</div>';
        }
        if(isset($tplData['msg']) && ! empty($tplData['msg']))
        {
            $res = '<div class="alert alert-success" role="alert">'.$tplData['msg'].'</div>';
        }

        return $res;
    }
}

// ------------------------------------------------------------------------

if ( ! function_exists('bootstrapDropdownMenu'))
{
    /**
     * bootstrapDropdownMenuItems
     *
     * @param	array
     * @param	string
     * @return	string	html
     */
    function bootstrapDropdownMenuItems($menuitems, $activeitem = '')
    {
        $res = '';

        foreach($menuitems as $item ){
            if ($item['type'] == 'link')
            {
                $haystack = $activeitem;
                $needle = $item['path'];
                $pathArgs = explode('/',$item['path']);
                $activeitemArgs = explode('/',$activeitem);
                $cssactive = '';

                // change haystack to needle
                if (count($pathArgs) > count($activeitemArgs))
                {
                    $haystack = $item['path'];
                    $needle = $activeitem;
                }
                // controller route must be the same
                if ($activeitemArgs[0] == $pathArgs[0])
                {
                    $cssactive = (strstr($haystack, $needle)) ? ' class="active"' : '';
                }

                $res .= '<li'.$cssactive.'><a href="'.site_url().$item['path'].'"><span class="glyphicon glyphicon-'.$item['icon'].'"></span> '.$item['label'].'</a></li>';
            }
            else if ($item['type'] == 'ajax')
            {
                $cssactive = (strstr($activeitem,$item['path'])) ? ' class="active"': '';
                $data = '';

                foreach($item['data'] as $key=>$val){
                    $data .= ' data-'.$key.'="'.$val.'"';
                }

                $res .= '<li'.$cssactive.'><a class="btn-ajax"'.$data.'><span class="glyphicon glyphicon-'.$item['icon'].'"></span> '.$item['label'].'</a></li>';
            }
            else if ($item['type'] == 'divider')
            {
                $res .= '<li class="divider"></li>';
            }
        }

        if ($res != '')
        {
            $res = '<ul class="dropdown-menu" aria-labelledby="dLabel">'.$res.'</ul>';
        }

        return $res;
    }
}

// ------------------------------------------------------------------------

if ( ! function_exists('bootstrapDropdownItem'))
{
    /**
     * bootstrapDropdownItem
     *
     * @param	array
     * @param	string
     * @param	string
     * @return	string	html
     */
    function bootstrapDropdownItem($dropdowns, $activeNavigationPath)
    {
        $res = '';

        foreach($dropdowns as $dropdown ){

            $label = (isset($dropdown['menu_label'])) ? $dropdown['menu_label'] : '';
            $dropdownMenu = bootstrapDropdownMenuItems($dropdown['menu_items'],$activeNavigationPath['menuItemActive']);
            $cssactive = ($activeNavigationPath['menuActive'] == $dropdown['path']) ? ' active': '';

            $res .= '<li class="dropdown'.$cssactive.'"><a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="glyphicon glyphicon-'.$dropdown['menu_icon'].'"></span> '.$label.' <span class="caret"></span></a>'.$dropdownMenu.'</li>';

        }
        return $res;
    }
}

// ------------------------------------------------------------------------

if ( ! function_exists('bootstrapNavbarItem'))
{
    /**
     * bootstrapNavbarItem
     *
     * @param	array
     * @param	string
     * @return	string	html
     */
    function bootstrapNavbarItem($navbarItems, $activeitem = '')
    {
        $res = '';
        foreach($navbarItems as $navbarItem ){

            $cssactive = ($activeitem == $navbarItem['path']) ? ' class="active"': '';
            $icon = (array_key_exists('menu_icon',$navbarItem) && ! empty($navbarItem['menu_icon'])) ? '<span class="glyphicon glyphicon-'.$navbarItem['menu_icon'].'"></span>' : '';

            $res .= '<li'.$cssactive.'><a class="tab-wrapper-item" href="'.site_url().$navbarItem['path'].'">'.$icon.$navbarItem['label'].'</a></li>';

        }
        return $res;
    }
}

// ------------------------------------------------------------------------

if ( ! function_exists('bootstrapListGroup'))
{
    /**
     * bootstrapListGroup
     *
     * @param	array
     * @param	string
     * @param	string
     * @return	string	html
     */
    function bootstrapListGroup($listGroupItems, $customerCss='', $activeitem = '', $activeIcon = '')
    {
        $res = '';
        foreach($listGroupItems as $item){

            $cssactive = (strstr($activeitem,$item['path'])) ? ' disabled': '';
            $href = ' href="'.site_url().$item['path'].'"';
            $css = 'list-group-item'.$cssactive;
            $icon = $item['icon'];


            if (isset($item['subitems'])) {
                $cssactive = (strstr($activeitem,$item['path'])) ? ' active': '';
                $href = '';
                $css = 'list-group-dropdown-item'.$cssactive;
            }
            if ($activeIcon != '' && $cssactive == ' active') {
                $icon = $activeIcon;
            }

            $res .= '<a'.$href.' class="'.$css.'"><span class="glyphicon glyphicon-'.$icon.'"></span> '.$item['label'].'</a>';

            if (isset($item['subitems']) && ! empty($item['subitems']))
            {
                $sub = '';

                foreach($item['subitems'] as $key=>$val){

                    $subcssactive = (strstr($activeitem,$val['path'])) ? ' disabled': '';
                    $sub .= '<li><a href="'.site_url().$val['path'].'" class="list-group-item'.$subcssactive.'">'.$val['label'].'</a></li>';
                }
                $res .= '<ul class="sidebar-sub-menu'.$cssactive.'">'.$sub.'</ul>';
            }
        }

        $res = '<div class="'.(($customerCss != '') ? $customerCss . ' list-group' : 'list-group').'">'.$res.'</div>';

        return $res;
    }
}

// ------------------------------------------------------------------------

if ( ! function_exists('bootstrapBtnGroupUnique'))
{
    /**
     * bootstrapBtnGroupUnique
     *
     * @param	number
     * @param	array
     * @param	array
     * @return	string	html
     */
    function bootstrapBtnGroupUnique($activRadio, $radioLable, $inputAttr=[], $wrapperAttr=[], $radioIcons=[])
    {
        $radio1 = ( ! $activRadio) ? ' checked="checked"':'';
        $radio2 = ( $activRadio)   ? ' checked="checked"':'';
        $lable1 = ( ! $activRadio) ? 'btn  btn-primary  btn-sm active first':'btn  btn-default  btn-sm first';
        $lable2 = ( $activRadio)   ? 'btn  btn-primary  btn-sm active last':'btn  btn-default  btn-sm last';
        $labletxt1 = ( ! empty($radioIcons))   ? '<i class="fa fa-'.$radioIcons[0].' fa-lg"></i>':$radioLable[0];
        $labletxt2 = ( ! empty($radioIcons))   ? '<i class="fa fa-'.$radioIcons[1].' fa-lg"></i>':$radioLable[1];
        $wrapperAttrRes = '';
        $inputAttrRes = '';

        if (! empty($wrapperAttr)) {
            foreach($wrapperAttr as $k=>$v){
                $wrapperAttrRes .= ' '.$k.'="'.$v.'"';
            }
        }
        if (! empty($inputAttr)) {
            foreach($inputAttr as $k=>$v){
                $inputAttrRes .= ' '.$k.'="'.$v.'"';
            }
        }

        $res = '<div class="btn btn-group radio-group unique" data-toggle="buttons"'.$wrapperAttrRes.'>
                    <label class="'.$lable1.'"><input type="radio" value="0" autocomplete="off"'.$radio1.'> '.$labletxt1.'</label>
                    <label class="'.$lable2.'"><input type="radio" value="1" autocomplete="off"'.$radio2.'> '.$labletxt2.'</label>
                    <input type="hidden"'.$inputAttrRes.' value="'.$activRadio.'">
                </div>';
        return $res;
    }
}

// ------------------------------------------------------------------------

if ( ! function_exists('bootstrapHelpBlock'))
{
    /**
     * bootstrapHelpBlock
     *
     * @param	string
     * @return	string	html
     */
    function bootstrapHelpBlock($help)
    {
        return '<div class="help-block">'.$help.'</div>';
    }
}

// ------------------------------------------------------------------------

if ( ! function_exists('bootstrapSelect'))
{
    /**
     * bootstrapSelect
     *
     * @param	array
     * @param	mixed
     * @param	mixed
     * @param	boolean
     * @param	boolean
     * @param	string
     * @return	string	html
     */
    function bootstrapSelect($data, $attr, $select, $valAsKey = false, $selectKey = false, $emptyOption = '')
    {
        $res = '';
        $selectAttr = '';
        $selected = '';
        $select = trim($select);

        if (! empty($attr))
        {
            foreach($attr as $k=>$v){
                $selectAttr .= ' '.$k.'="'.$v.'"';
            }
        }
        if (! empty($data))
        {
            if( ! strlen($select)) {
                $selected = ' selected="selected"';
            }

            if ( ! empty($emptyOption))
            {
                $res .= '<option value="'.$emptyOption['key'].'"'.$selected.'>'.$emptyOption['val'].'</option>';
            }

            foreach($data as $k=>$v){
                if ( ! $selectKey) {
                    $selected = (strlen($select) && $v == $select) ? ' selected="selected"' : '';
                } else {
                    $selected = (strlen($select) && $k == $select) ? ' selected="selected"' : '';
                }

                $k = ( ! $valAsKey) ? $k : $v;
                $res .= '<option value="'.$k.'"'.$selected.'>'.$v.'</option>';
            }
        }

        if (! empty($res)) {
            $res = '<select'.$selectAttr.'>'.$res.'</select>';
        }

        return $res;
    }
}
// ------------------------------------------------------------------------

if ( ! function_exists('bootstrapSelectNumber'))
{
    /**
     * bootstrapSelectNumber
     *
     * @param	array
     * @param	mixed
     * @param	mixed
     * @return	string	html
     */
    function bootstrapSelectNumber($data, $attr, $select)
    {
        $res = '';
        $selectAttr = '';

        if (! empty($attr))
        {
            foreach($attr as $k=>$v){
                $selectAttr .= ' '.$k.'="'.$v.'"';
            }
        }
        if (! empty($data) && array_key_exists('start',$data) && array_key_exists('end',$data))
        {
            $counter = $data['start'];

            while($counter <= $data['end']){

                $selected = ($counter==$select) ? $selected = ' selected="selected"' : '';
                $res .= '<option value="'.$counter.'"'.$selected.'>'.$counter.'</option>';
                $counter++;
            }
        }

        if (! empty($res)) {
            $res = '<select'.$selectAttr.'>'.$res.'</select>';
        }

        return $res;
    }
}

// ------------------------------------------------------------------------

if ( ! function_exists('bootstrapTextBlock'))
{
    /**
     * bootstrapTextBlock
     *
     * @param	string
     * @return	string	html
     */
    function bootstrapTextBlock($data)
    {
        $res = '';
        $blockTags = [
            '[#p#]' => 'p'
        ];
        $tag = '';

        foreach($blockTags as$k=>$v) {
            if(preg_match('/'.$k.'/',$data)) {
                $tag = $k;
                break;
            }
        }
        if ($tag) {
            $res = explode($tag,$data);
            $res = '<'.$blockTags[$tag].'>'.implode('</'.$blockTags[$tag].'><'.$blockTags[$tag].'>',$res).'</'.$blockTags[$tag].'>';
        }
        return $res;
    }
}