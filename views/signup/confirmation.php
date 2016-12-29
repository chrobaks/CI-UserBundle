<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="container">
    <div class="row">
        <div class="col-md-4 col-md-offset-4 form-panel">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="panel-title"><span class="glyphicon glyphicon-registration-mark"></span><?=lang('title');?></div>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <?php if($tplData['status'] === 'invitation'): ?>
                                <?=$tplData['confirmationText'];?>
                            <?php elseif($tplData['status'] === 'confirm'): ?>
                                <p><?=lang('text_enter_userdata');?></p>
                                <?=bootstrapAlert($tplData);?>
                                <?=form_open('signup/confirmation'); ?>
                                <input type="hidden" name="cnfhsh" value="<?=$tplData['confirmationHash'];?>">
                                <div class="row form-group">
                                    <div class="col-md-12">
                                        <label for="username"><?=lang('label_username'); ?>*</label>
                                        <input type="text" class="form-control input-sm" name="username" value="<?=set_value('username'); ?>">
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class="col-md-12">
                                        <label for="password"><?=lang('label_password'); ?>*</label>
                                        <input type="password" class="form-control input-sm" name="password" value="<?=set_value('password'); ?>">
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class="col-md-12">
                                        <?=bootstrapHelpBlock(lang('label_help_input_required'));?>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary btn-sm"><?=lang('label_send'); ?></button>
                                </form>
                            <?php else:?>
                                <?=bootstrapAlert($tplData);?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
