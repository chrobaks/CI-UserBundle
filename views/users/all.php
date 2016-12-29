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
    <div class="row">
        <div class="col-md-12 group-filter"
             data-act="all"
             data-val=""
             data-dependence-master="topfilter">
            <ul class="listrow clearfix">
                <li>
                    <div class="well well-sm">
                        <?=lang('label_show');?> :
                        <?=bootstrapSelect(
                            $tplData['filter']['confirmation'],
                            ['data-filter-module'=>'group-filter','data-filter'=>'confirmation'],
                            $tplData['confirmation'],
                            false,
                            true,
                            ['val'=>lang('label_content_all'),'key'=>'']
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
                <th><?=lang('label_username'); ?></th>
                <th><?=lang('label_email'); ?></th>
                <th><?=lang('label_created_at'); ?></th>
                <th><?=lang('label_last_login'); ?></th>
                <th><?=lang('label_confirmed'); ?></th>
                <th><?=lang('label_usergroup'); ?></th>
                <th colspan="2"></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($tplData['users'] as $row => $user):?>
                <tr class="group-row" data-container="ajax-list">
                    <td><?=$user['username'];?></td>
                    <td><?=$user['email'];?></td>
                    <td><?=$user['createAt'];?></td>
                    <td><?=$user['last_login'];?></td>
                    <td class="td-center">
                        <?=bootstrapBtnGroupUnique($user['confirmation'],['no','yes'],['data-key'=>'confirmation']);?>
                    </td>
                    <td>
                        <?=bootstrapSelect($tplData['secureRoles'], ['data-key'=>'role'], $user['role'], true);?>
                    </td>
                    <td class="td-center">
                        <input type="hidden" data-key="id" value="<?=$user['id'];?>">
                        <input type="hidden" data-key="<?=$csrf_token_name;?>" data-csrf-key="<?=$csrf_token_name;?>" value="<?=$csrf_hash;?>">
                        <button type="button" class="btn btn-primary btn-sm ajax-container-request" data-act="update" title="<?=lang('label_edit'); ?>">
                            <span class="glyphicon glyphicon-edit"></span>
                        </button>
                    </td>
                    <td class="td-center">
                        <a class="btn btn-warning btn-sm" data-act="delete" data-val="<?=$user['id'];?>" data-toggle="confirmation" data-placement="left" title="<?=lang('label_delete'); ?>?">
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