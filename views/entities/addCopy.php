<?php
/**
 * Created by PhpStorm.
 * Date: 23.09.2016
 */
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="container">
    <div class="row">
        <div class="col-md-12"><h3 class="page-header"><?=lang('title');?></h3></div>
    </div>
    <?php if(empty($tplData['unusedTables'])):?>
        <div class="well">
            <?=bootstrapAlert($tplData);?>
            <div class="alert alert-warning" role="alert">
                <p>
                    <span class="glyphicon glyphicon-info-sign"></span><strong>Entity Information</strong><br>
                    Can't find availabel tables, all existing tables already stored in entities configurations.
                </p>
                <hr>
                <p>
                    <span class="glyphicon glyphicon-wrench"></span><strong>Solution</strong><br>
                    Please create new database tables to set a new entities configuration.
                </p>
            </div>
        </div>
    <?php else: ?>
        <div class="jumbotron">
            <?=form_open('entities/add',['id'=>'entitiesConfiguration']); ?>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <?=bootstrapAlert($tplData);?>
                    </div>
                    <div class="form-group">
                        <label for="name">Datenbank Tabelle ohne bestehende Konfiguration</label><br>
                        <?=bootstrapSelect(
                            $tplData['unusedTables'],
                            ['name'=>'tablename','data-space'=>'entities','data-act'=>'gettablecols','data-key'=>'tablename','data-csrf-key'=>$csrf_token_name,'data-target-id'=>'name'],
                            '',
                            true,
                            true,
                            ['val'=>lang('label_choose_table'),'key'=>'']
                        );?>
                    </div>
                    <div class="form-group">
                        <label for="name">Datenbank Tabelle mit bestehender Konfiguration</label><br>
                        <?=bootstrapSelect(
                            $tplData['tablesWithConfig'],
                            ['name'=>'entities_config_id','data-space'=>'entities','data-act'=>'getconfig','data-key'=>'entities_config_id','data-csrf-key'=>$csrf_token_name,'data-target-id'=>'entitiesConfig'],
                            '',
                            false,
                            true,
                            ['val'=>lang('label_choose_table'),'key'=>'']
                        );?>
                    </div>
                    <div class="wrapper-step2">
                        <div class="form-group changeConfig">
                            <label for="name">Mit Konfiguration anlegen</label><br>
                            <?=bootstrapSelect(
                                $tplData['filter']['entities_config_id'],
                                ['name'=>'entities_config_id','data-space'=>'entities','data-act'=>'getconfig','data-key'=>'entities_config_id','data-csrf-key'=>$csrf_token_name,'data-target-id'=>'entitiesConfig'],
                                '',
                                false,
                                true,
                                ['val'=>lang('label_choose_configs'),'key'=>'']
                            );?>
                        </div>
                        <div class="form-group changeDepMaster">
                            <label for="dependence_entities">is dependence Entity of*</label><br>
                            <?=bootstrapSelect(
                                $tplData['unusedTables'],
                                ['name'=>'dependence_entities_master','data-target-id'=>'change_dep_child','data-target'=>'change_dep_master'],
                                '',
                                true,
                                true,
                                ['val'=>lang('label_choose_table'),'key'=>'']
                            );?>
                        </div>
                        <div class="form-group changeDepChild">
                            <label for="dependence_entities">has dependence Entities</label><br>
                            <?=bootstrapSelect(
                                $tplData['unusedTables'],
                                ['name'=>'dependence_entities_child[]','size'=>'3','multiple'=>'multiple','data-target-id'=>'change_dep_master','data-target'=>'change_dep_child'],
                                '',
                                true,
                                true,
                                ['val'=>lang('label_choose_table'),'key'=>'']
                            );?>
                        </div>
                        <div class="form-group">
                            <label for="configuration_name">Configuration Name*</label>
                            <input type="text" class="form-control" name="configuration_name" value="" data-target="configuration_name" placeholder="Configuration Name">
                        </div>
                        <div class="form-group">
                            <label for="name">Entitiy Name*</label>
                            <input type="text" class="form-control" name="name" value="" data-target="name" placeholder="entity" readonly="readonly">
                        </div>
                        <div class="form-group">
                            <label for="query_cols">Tabellenspalten</label>
                            <input type="text" class="form-control" name="query_cols" value="" data-target="query_cols"  readonly="readonly">
                        </div>
                        <div class="form-group">
                            <label for="query_order_by">Order By</label>
                            <input type="text" class="form-control" name="query_order_by" value="">
                        </div>
                        <div class="form-group">
                            <?=bootstrapHelpBlock(lang('label_help_input_required'));?>
                        </div>
                        <div class="form-group testIntegrateConfig">
                            <button
                                type="button"
                                id="btnTestConfig"
                                class="btn btn-primary">Test Configuration</button>
                            <button
                                type="button"
                                class="btn btn-default"
                                disabled="disabled"><?=lang('label_save'); ?></button>
                        </div>
                        <div class="form-group testMasterConfig">
                            <button
                                type="button"
                                name="getmasterconf"
                                id="btnTestMasterConfig"
                                class="btn btn-primary"
                                data-space="entities"
                                data-act="getmasterconf"
                                data-key="tablename">Test Master Configuration</button>
                            <button
                                type="button"
                                id="btnCreateMasterConfig"
                                name="createmasterconf"
                                class="btn btn-default"
                                data-space="entities"
                                data-act="createmasterconf"
                                data-key="masterconf"
                                disabled="disabled">Save Master Configuration</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="wrapper-step3">
                        <div class="form-group">
                            <div id="configErrorAlert" class="alert alert-danger" role="alert"></div>
                        </div>
                        <div class="form-group" data-target="entitiesConfig"></div>
                    </div>
                </div>
            </div>
            </form>
        </div>
    <?php endif; ?>
</div>