<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="container">
    <div class="row">
        <div class="col-md-12"><h3 class="page-header"><?=lang('title_profil');?></h3></div>
    </div>
    <div class="jumbotron">
        <div class="row">
            <div class="col-md-12">
                <?=form_open('user/update',['class'=>'formAjaxRequest']); ?>
                <div class="form-group">
                    <label class="control-label"><?=lang('label_usergroup'); ?> :</label>
                    <span class="form-control-static"><?=$tplData['user']->role; ?></span>
                </div>
                <div class="form-group">
                    <label for="username"><?=lang('label_username'); ?>*</label>
                    <input type="text" class="form-control data-scope" name="username" data-scope="username" value="<?=$tplData['user']->username; ?>">
                </div>
                <div class="form-group">
                    <label for="email"><?=lang('label_email'); ?>*</label>
                    <input type="text" class="form-control" name="email" value="<?=$tplData['user']->email; ?>">
                </div>
                <div class="forminfo">
                    <p></p>
                </div>
                <button type="submit" class="btn btn-default"><?=lang('label_save'); ?></button>
                </form>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12"><h3 class="page-header"><?=lang('title_newpass');?></h3></div>
    </div>
    <div class="jumbotron">
        <div class="row">
            <div class="col-md-12">
                <?=form_open('user/newpass',['class'=>'formAjaxRequest']); ?>
                <div class="form-group">
                    <label for="password"><?=lang('label_password'); ?>*</label>
                    <input type="password" class="form-control" name="password" value="<?=set_value('password'); ?>">
                </div>
                <div class="form-group">
                    <label for="passconf"><?=lang('label_password_confirmation'); ?>*</label>
                    <input type="password" class="form-control" name="passconf" value="<?=set_value('passconf'); ?>">
                </div>
                <div class="forminfo">
                    <p></p>
                </div>
                <button type="submit" class="btn btn-default"><?=lang('label_save'); ?></button>
                </form>
            </div>
        </div>
    </div>
</div>