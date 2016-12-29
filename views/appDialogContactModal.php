<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!-- Modal -->
<div class="modal fade" tabindex="-1" role="dialog" id="myAppDialogContactModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel"><?=lang('page_title_email_form');?></h4>
            </div>
            <?=form_open('ajax/contact',['class'=>'formAjaxRequest']); ?>
            <div class="modal-body">
                <div class="row form-group">
                    <div class="col-md-12">
                        <label for="name"><?=lang('label_name'); ?>*</label>
                        <input type="text" class="form-control input-sm" name="name" value="<?=set_value('name'); ?>">
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-md-12">
                        <label for="email"><?=lang('label_email'); ?>*</label>
                        <input type="text" class="form-control input-sm" name="email" value="<?=set_value('email'); ?>">
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-md-12">
                        <label for="message"><?=lang('label_message'); ?>*</label><br>
                        <textarea name="message"></textarea>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-md-12 forminfo"><p></p></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary btn-sm"><?=lang('label_send');?></button>
                <button type="button" class="btn btn-default btn-sm" data-dismiss="modal"><?=lang('label_close');?></button>
            </div>
            </form>
        </div>
    </div>
</div>