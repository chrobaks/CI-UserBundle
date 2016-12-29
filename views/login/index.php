<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="container">
    <div class="row">
        <div class="col-md-4 col-md-offset-4 form-panel">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="panel-title">
                        <span class="glyphicon glyphicon-log-in"></span>
                        <?=lang('title');?>
                    </div>
                </div>
                <div class="panel-body">
                    <?=bootstrapAlert($tplData);?>
                    <?=form_open('login'); ?>
                    <div class="row form-group">
                        <div class="col-md-6">
                            <label for="username"><?=lang('label_username'); ?>*</label>
                            <input type="text" class="form-control  input-sm" name="username" value="<?=set_value('username'); ?>">
                        </div>
                        <div class="col-md-6">
                            <label for="password"><?=lang('label_password'); ?>*</label>
                            <input type="password" class="form-control input-sm" name="password" value="<?=set_value('password'); ?>">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-primary btn-sm">Login</button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <hr>
                            <p>
                                <a data-toggle="modal" data-target="#myModalPasswordForgot">
                                    <span class="glyphicon glyphicon-hand-right"></span>
                                    <?=lang('label_password_forgot');?>?
                                </a>
                            </p>
                            <p>
                                <a href="signup">
                                    <span class="glyphicon glyphicon-hand-right"></span>
                                    <?=lang('label_signup_as_user');?>
                                </a>
                            </p>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal PasswordForgot -->
<div class="modal fade" id="myModalPasswordForgot" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><?=lang('title_get_new');?></h4>
            </div>
            <?=form_open('login/passwordforgot',['class'=>'formAjaxRequest']); ?>
            <div class="modal-body">
                <div class="form-group">
                    <label for="username"><?=lang('text_enter_email');?></label>
                    <input type="text" class="form-control input-sm" name="email" id="passwordforgot-email" value="">
                </div>
                <div class="forminfo">
                    <p></p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-sm" data-dismiss="modal"><?=lang('label_close');?></button>
                <button type="submit" class="btn btn-primary btn-sm"><?=lang('label_send'); ?></button>
            </div>
            </form>
        </div>
    </div>
</div>
