<?php
/**
 * Created by PhpStorm
 * Date: 23.09.2016
 */
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="container">
    <div class="row">
        <div class="col-md-12"><h3 class="page-header"><?=lang('title');?></h3></div>
    </div>
    <div class="row">
        <div class="col-md-12 group-filter"
             data-act="all"
             data-val=""
             data-dependence-master="topfilter">
            <ul class="listrow clearfix">
                <li>
                    <div class="well well-sm">
                        <?=lang('txt_system_dependence');?> :
                        <?=bootstrapSelect(
                            $tplData['filter']['app_dependence'],
                            ['data-filter-module'=>'group-filter','data-filter'=>'app_dependence','data-target-to-default'=>'entities_config_id'],
                            $tplData['app_dependence'],
                            true,
                            false,
                            ['val'=>lang('txt_without_depend'),'key'=>'']
                        );?>
                    </div>
                </li>
                <li>
                    <div class="well well-sm">
                        <?=lang('txt_show_conf');?> :
                        <?=bootstrapSelect(
                            $tplData['filter']['entities_config_id'],
                            ['data-filter-module'=>'group-filter','data-filter'=>'entities_config_id'],
                            $tplData['entities_config_id'],
                            false,
                            true,
                            ['val'=>lang('txt_choose_configs'),'key'=>'']
                        );?>
                    </div>
                </li>
            </ul>
            <?php $this->load->view('base_pagination_master');?>
        </div>
    </div>

    <div class="row">
        <?php foreach($tplData['results'] as $k=>$v):?>
        <div class="col-md-6">
            <div
                class="listbox-wrapper all">
                <ul data-clone-source="entityContent_<?=$v['tablename'];?>">
                    <li><strong><span class="glyphicon glyphicon-file"></span><?=$v['configName'];?></strong></li>
                    <li><strong>Entity :</strong> <?=$v['name'];?></li>
                    <li><strong>Table :</strong> <?=$v['tablename'];?></li>
                    <li><strong>Dependencies :</strong> <?=$v['dependence_entities'];?></li>
                    <li class="hidden">
                        <strong>Columns :</strong>
                        <ul>
                            <?php foreach($v['columns'] as $qk=>$qv):?>
                                <li><strong><?=$qv['key'];?> :</strong> <?=$qv['value'];?></li>
                            <?php endforeach;?>
                        </ul>
                    </li>
                    <li class="hidden"><strong>Query Cols :</strong> <?=$v['query_cols'];?></li>
                    <li class="hidden">
                        <strong>Order By :</strong>
                        <ul>
                            <?php foreach($v['queryOrderBy'] as $ok=>$ov):?>
                                <li><strong><?=$ok;?> :</strong> <?=$ov;?></li>
                            <?php endforeach;?>
                        </ul>
                    </li>
                    <li class="hidden">
                        <strong>Defined Queries :</strong>
                        <ul>
                            <?php foreach($v['queryDefined'] as $odk=>$odv):?>
                                <li><strong><?=$odv['key'];?> :</strong><p><?=$odv['value'];?></p></li>
                            <?php endforeach;?>
                        </ul>
                    </li>
                </ul>
            </div>
            <div class="listbox-wrapper-btn-group toolbar toolbar-form">
                <ul class="listrow clearfix">
                    <li>
                        <div class="well well-sm show">
                            <a data-module="clone"
                               data-clone-modal-id="#myModalContent"
                               data-clone-target-id="#CloneContent"
                               data-clone-source-id="entityContent_<?=$v['tablename'];?>"
                               data-clone-type="wrapper">
                                <span class="glyphicon glyphicon-file"></span> <?=lang('label_data_show');?>
                            </a>
                        </div>
                    </li>
                    <?php if( $v['app_dependence']=='public'):?>
                    <?php if( $v['dependence_level'] > 0 ):?>
                    <li>
                        <div class="well well-sm danger">
                            <a data-act="deleteentitiy"
                               data-val="<?=$v['id'];?>"
                               data-toggle="confirmation"
                               data-placement="top"
                               title="<?=lang('label_data_delete'); ?>: <?=$v['name'];?>">
                                <span class="glyphicon glyphicon-trash"></span> <?=lang('label_data_delete');?>
                            </a>
                        </div>
                    </li>
                    <?php else:?>
                    <li>
                        <div class="well well-sm danger">
                            <a data-act="deleteconfig"
                               data-val="<?=$v['entities_config_id'];?>"
                               data-toggle="confirmation"
                               data-placement="top"
                               title="<?=lang('label_data_delete'); ?>: <?=$v['name'];?>">
                                <span class="glyphicon glyphicon-trash"></span> Konfiguration löschen
                            </a>
                        </div>
                    </li>
                    <?php endif;?>
                    <?php endif;?>
                </ul>
            </div>
        </div>
        <?php endforeach;?>
    </div>

    <?php $this->load->view('base_pagination_child');?>

</div>
<!-- Modal -->
<div class="modal fade" id="myModalContent" tabindex="-1" role="dialog" aria-labelledby="myModalContent">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel"><?=lang('title_modal');?></h4>
            </div>
            <div class="modal-body"><div id="CloneContent"></div></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-sm" data-dismiss="modal"><?=lang('label_close');?></button>
            </div>
            </form>
        </div>
    </div>
</div>