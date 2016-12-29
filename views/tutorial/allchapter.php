<?php
/**
 * Created by PhpStorm
 * Date: 23.09.2016
 */
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="container">
    <div class="row">
        <div class="col-md-12"><h3 class="page-header"><?=$tplTitle;?></h3></div>
    </div>
    <div class="row">
        <div class="col-md-12 group-filter"
             data-act="allchapter"
             data-val=""
            data-dependence-master="topfilter">
            <ul class="listrow clearfix">
                <li>
                    <div class="well well-sm">
                        <?=lang('label_show');?> :
                        <?=bootstrapSelect(
                            $tplData['filter']['active'],
                            ['data-filter-module'=>'group-filter','data-filter'=>'active'],
                            $tplData['active'],
                            false,
                            true,
                            ['val'=>lang('label_content_all'),'key'=>'']
                        );?>
                    </div>
                </li>
                <li>
                    <div class="well well-sm">
                        <?=lang('label_category');?> :
                        <?=bootstrapSelect(
                            $tplData['filter']['tutorial_category_id'],
                            ['data-filter-module'=>'group-filter','data-filter'=>'tutorial_category_id'],
                            $tplData['tutorial_category_id'],
                            false,
                            true,
                            ['val'=>lang('label_category_choice'),'key'=>'']
                        );?>
                    </div>
                </li>
            </ul>
            <?php $this->load->view('base_pagination_master');?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <table class="table table-bordered table-striped table-hover table-condensed table-responsive">
                <thead>
                <tr>
                    <th><?=lang('label_id');?></th>
                    <th><?=lang('label_category');?></th>
                    <th><?=lang('label_name');?></th>
                    <th><?=lang('label_title_de');?></th>
                    <th><?=lang('label_title_en');?></th>
                    <th><?=lang('label_page_index');?></th>
                    <th><?=lang('label_active');?></th>
                    <th colspan="2"></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach($tplData['chapters'] as $row => $col):?>
                    <?php $maxZIndex = 1; if( ! empty($tplData['maxChaptersZIndex'])) { $maxZIndex = $tplData['maxChaptersZIndex'][$col['url_link']]; }?>
                    <tr class="group-row" data-container="ajax-list">
                        <td><?=$col['id'];?></td>
                        <td><?=$col['category'];?></td>
                        <td><input type="text" data-key="url_link" value="<?=$col['url_link'];?>"></td>
                        <td><input type="text" data-key="title_de" value="<?=$col['title_de'];?>"></td>
                        <td><input type="text" data-key="title_en" value="<?=$col['title_en'];?>"></td>
                        <td><input type="number" data-key="zIndex" value="<?=$col['zIndex'];?>" min="1" max="<?=$maxZIndex;?>"></td>
                        <td class="td-center">
                            <?=bootstrapBtnGroupUnique($col['active'],['no','yes'],['data-key'=>'active']);?>
                        </td>
                        <td class="td-center">
                            <input type="hidden" data-key="id" value="<?=$col['id'];?>">
                            <input type="hidden" data-key="<?=$csrf_token_name;?>" data-csrf-key="<?=$csrf_token_name;?>" value="<?=$csrf_hash;?>">
                            <button type="button" class="btn btn-primary btn-sm ajax-container-request" data-act="updatechapter" title="<?=lang('label_edit'); ?>">
                                <span class="glyphicon glyphicon-edit"></span>
                            </button>
                        </td>
                        <td class="td-center">
                            <a class="btn btn-warning btn-sm" data-act="deletechapter" data-val="<?=$col['id'];?>" data-toggle="confirmation" data-placement="left" title="<?=lang('label_delete'); ?>?">
                                <span class="glyphicon glyphicon-trash"></span>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php $this->load->view('base_pagination_child');?>
</div>