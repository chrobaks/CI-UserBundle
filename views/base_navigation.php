<?php
/**
 * Create top navigation
 */

defined('BASEPATH') OR exit('No direct script access allowed');

// store rendered menu ul
$tplNavigation = ['left' => '','right' => ''];
// render left navigation
if ( ! empty($navigation['left'])) {
    foreach($navigation['left'] as $k=>$v){
        if ($k=='items') {
            $tplNavigation['left'] .= bootstrapNavbarItem($v, $viewController);
        } else if ($k=='dropdown') {
            $tplNavigation['left'] .= bootstrapDropdownItem($v, $activeNavigationPath);
        }
    }
    $tplNavigation['left'] = '<ul class="nav navbar-nav">'.$tplNavigation['left'].'</ul>';
}
// render right navigation
if ( ! empty($navigation['right'])) {
    foreach($navigation['right'] as $k=>$v){
        if ($k=='items') {
            $tplNavigation['right'] .= bootstrapNavbarItem($v, $viewController);
        } else if ($k=='dropdown') {
            $tplNavigation['right'] .= bootstrapDropdownItem($v, $activeNavigationPath);
        }
    }
    $tplNavigation['right'] = '<ul class="nav navbar-nav navbar-right">'.$tplNavigation['right'].'</ul>';
}
?>
    <nav id="navbar-wrapper" class="navbar navbar-inverse navbar-fixed-top">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="<?=site_url();?>"><img src="<?=site_url();?>public/image/app/logo.png"></a>
            </div>
            <div id="navbar" class="navbar-collapse collapse">
                <?=$tplNavigation['left'];?>
                <?=$tplNavigation['right'];?>
            </div>
        </div>
    </nav>