<?php
/**
 * Created by PhpStorm.
 * Date: 23.09.2016
 */
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="container" data-service-controller="entityConfig" id="entityConfig">
    <div class="row">
        <div class="col-md-12"><h3 class="page-header"><?=lang('title');?></h3></div>
    </div>
    <?php if(empty($tplData['unusedTables']) && empty($tplData['tablesWithConfig'])):?>
        <div class="well">
            <?=bootstrapAlert($tplData);?>
            <div class="alert alert-warning" role="alert">
                <p>
                    <span class="glyphicon glyphicon-info-sign"></span><strong><?=lang('txt_entity_info');?></strong><br>
                    <?=lang('txt_no_table');?>
                </p>
                <hr>
                <p>
                    <span class="glyphicon glyphicon-wrench"></span><strong><?=lang('txt_solution');?></strong><br>
                    <?=lang('txt_create_new_table');?>
                </p>
            </div>
        </div>
    <?php else: ?>
    <div class="jumbotron">
        <?=form_open('entities/add'); ?>
        <div class="row">
            <div class="col-md-12"><?=bootstrapAlert($tplData);?></div>
        </div>
        <div class="row">
            <?php if( ! empty($tplData['unusedTables'])):?>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="name"><?=lang('txt_tables_no_conf');?></label><br>
                    <?=bootstrapSelect(
                        $tplData['unusedTables'],
                        ['name'=>'tablename','data-act'=>'gettablecols','data-key'=>'tablename','data-csrf-key'=>$csrf_token_name,'data-target-id'=>'name'],
                        '',
                        true,
                        true,
                        ['val'=>lang('txt_choose_table'),'key'=>'']
                    );?>
                </div>
            </div>
            <?php endif;?>
            <?php if( ! empty($tplData['tablesWithConfig'])):?>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="name"><?=lang('txt_tables_with_conf');?></label><br>
                    <?=bootstrapSelect(
                        $tplData['tablesWithConfig'],
                        ['name'=>'entities_config_id','data-act'=>'getconfig','data-key'=>'entities_config_id','data-csrf-key'=>$csrf_token_name,'data-target-id'=>'entitiesConfig'],
                        '',
                        false,
                        true,
                        ['val'=>lang('txt_choose_config'),'key'=>'']
                    );?>
                </div>
            </div>
            <?php endif;?>
        </div>
        <div class="wrapper-step2">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="configuration_name"><?=lang('txt_conf_name');?>*</label>
                        <input type="text" class="form-control" name="configuration_name" value="" data-target="configuration_name" placeholder="<?=lang('txt_conf_name');?>">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name">Entitiy Name*</label>
                        <input type="text" class="form-control" name="name" value="" data-target="name" placeholder="entity" readonly="readonly">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="query_cols"><?=lang('txt_table_col');?></label>
                        <input type="text" class="form-control" name="query_cols" value="" data-target="query_cols"  readonly="readonly">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="query_order_by">Order By</label>
                        <input type="text" class="form-control" name="query_order_by" value="">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12"><?=bootstrapHelpBlock(lang('label_help_input_required'));?></div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="form-group updateConfig">
                    <button
                        type="button"
                        name="updateconf"
                        id="btnUpdateConfig"
                        class="btn btn-success"
                        data-act="updateconf"
                        data-key="entityconf"><?=lang('txt_save_integration'); ?></button>
                </div>
                <div class="form-group createConfig">
                    <button
                        type="button"
                        name="settestconf"
                        id="btnTestConfig"
                        class="btn btn-primary"
                        data-act="getnewconf"
                        data-key="tablename"><?=lang('txt_test_new_conf'); ?></button>
                    <button
                        type="button"
                        id="btnCreateConfig"
                        name="createconf"
                        class="btn btn-default"
                        data-act="createconf"
                        data-key="entityconf"
                        disabled="disabled"><?=lang('txt_save_new_conf'); ?></button>
                </div>
                <div class="form-group errorAlert">
                    <div id="configErrorAlert" class="alert alert-danger" role="alert"></div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="wrapper-step3">
                    <div id="configInfoTpl" class="alert alert-success" role="alert"></div>
                    <div id="entitiesConfigTpl" class="form-group"></div>
                </div>
            </div>
        </div>
        </form>
    </div>
    <?php endif; ?>
</div>