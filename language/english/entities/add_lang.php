<?php
/**
 * Created by PhpStorm
 * Date: 19.10.2016
 */

defined('BASEPATH') OR exit('No direct script access allowed');

$lang['title'] = 'Neue Entity';
$lang['txt_choose_config'] = 'No Configuration';
$lang['txt_choose_table'] = 'No Table';
$lang['txt_entity_info'] = 'Entity Information';
$lang['txt_solution'] = 'Solution';
$lang['txt_no_table'] = "Can't find availabel tables, all existing tables already stored in entities configurations.";
$lang['txt_create_new_table'] = "Please create new database tables to set a new entities configuration.";
$lang['txt_tables_no_conf'] = "Database table without existing configuration";
$lang['txt_tables_with_conf'] = "Database table with existing configuration";
$lang['txt_conf_name'] = "Configuration name";
$lang['txt_table_col'] = "Table columns";
$lang['txt_save_integration'] = "Save integrated Configurationstables";
$lang['txt_test_new_conf'] = "Test new Configuration";
$lang['txt_save_new_conf'] = "Save new Configuration";
$lang['js_controller_msg'] = "
warningIsDep : 'Warning, this table is dependence of, please change to this table : ',
warningSelectIsDep : 'This table is dependence of, please select all options : ',
noMastertableMatch : 'Warning, this configuration not match the mastertable, please change Database Tablename Selection!',
createNewConf : 'New Configuration [configName] will saved with [countTable] Tables.',
createConfigIntegration : 'These [countTable] Tables integrate into [configName].'
";