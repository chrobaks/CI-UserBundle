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
    <div class="jumbotron">
        <div class="row">
            <div class="col-md-12">
                <?=form_open('users/create',['class'=>'formAjaxRequest']); ?>
                <div class="form-group">
                    <label for="username"><?=lang('label_username'); ?>*</label>
                    <input type="text" class="form-control" name="username" value="<?=set_value('username');?>">
                </div>
                <div class="form-group">
                    <label for="email"><?=lang('label_email'); ?>*</label>
                    <input type="text" class="form-control" name="email" value="<?=set_value('email');?>">
                </div>
                <div class="form-group">
                    <label for="passconf"><?=lang('label_usergroup'); ?>*</label>
                    <?=bootstrapSelect($tplData['secureRoles'], ['name'=>'role'], 'user', true);?>
                </div>
                <div class="forminfo">
                    <p></p>
                </div>
                <div class="forminfo">
                    <?=bootstrapAlert($tplData);?>
                </div>
                <div class="form-group">
                    <?=bootstrapHelpBlock(lang('label_help_input_required'));?>
                </div>
                <input type="hidden" name="<?=$csrf_token_name;?>" value="<?=$csrf_hash;?>">
                <button type="submit" class="btn btn-default"><?=lang('label_save'); ?></button>
                </form>
            </div>
        </div>
    </div>
</div>