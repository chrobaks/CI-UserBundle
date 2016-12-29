<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="container">
    <div class="row">
        <div class="col-md-6 col-md-offset-3 form-panel">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="panel-title">
                        <span class="glyphicon glyphicon-registration-mark"></span>
                        <?=lang('title');?>
                    </div>
                </div>
                <div class="panel-body">
                    <?=bootstrapAlert($tplData);?>
                    <?=form_open('signup'); ?>
                    <div class="row form-group">
                        <div class="col-md-6">
                            <label for="username"><?=lang('label_username'); ?>*</label>
                            <input type="text" class="form-control input-sm" name="username" value="<?=set_value('username'); ?>">
                        </div>
                        <div class="col-md-6">
                            <label for="email"><?=lang('label_email'); ?>*</label>
                            <input type="text" class="form-control input-sm" name="email" value="<?=set_value('email'); ?>">
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-md-6">
                            <label for="password"><?=lang('label_password'); ?>*</label>
                            <input type="password" class="form-control input-sm" name="password" value="<?=set_value('password'); ?>">
                        </div>
                        <div class="col-md-6">
                            <label for="passconf"><?=lang('label_password_confirmation'); ?>*</label>
                            <input type="password" class="form-control input-sm" name="passconf" value="<?=set_value('passconf'); ?>">
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-md-12">
                            <?=bootstrapHelpBlock(lang('label_help_input_required'));?>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm"><?=lang('label_send'); ?></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
