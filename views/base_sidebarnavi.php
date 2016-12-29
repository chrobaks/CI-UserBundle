<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (array_key_exists('sidebar', $navigation) && ! empty($navigation['sidebar'])) {
    echo bootstrapListGroup($navigation['sidebar']['menu_items'], 'sidebar', $activeNavigationPath['sidebarItemActive'], 'folder-open');
}
