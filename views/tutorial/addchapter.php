<?php
/**
 * Created by PhpStorm.
 * Date: 23.09.2016
 */
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="container">
    <div class="row">
        <div class="col-md-12"><h3 class="page-header"><?=$tplTitle;?></h3></div>
    </div>
    <div class="jumbotron">
        <div class="row">
            <?=bootstrapAlert($tplData);?>
            <?=form_open('tutorial/createchapter'); ?>
            <div class="form-group">
                <label for="name_de"><?=lang('label_title_de');?>*</label>
                <input type="text" class="form-control" name="title_de" value="">
            </div>
            <div class="form-group">
                <label for="name_en"><?=lang('label_title_en');?>*</label>
                <input type="text" class="form-control" name="title_en" value="">
            </div>
            <div class="form-group">
                <label for="tutorial_category_id"><?=lang('label_category');?>*</label><br>
                <select
                    name="tutorial_category_id"
                    data-act="getDepVal"
                    data-key="awaiting-data"
                    data-val="chapterNewMaxZIndex"
                    data-module="catMaxZIndex">
                    <option value=""><?=lang('label_category_choice');?></option>
                    <?php foreach($tplData['categories'] as $cat):?>
                        <option value="<?=$cat['id'];?>"><?=$cat['title_en'];?></option>
                    <?php endforeach;?>
                </select>
            </div>
            <div class="form-group">
                <label><?=lang('label_page_index');?>*</label><br>
                <input type="number" name="zIndex" value="1" min="1" max="1">
            </div>
            <div class="form-group">
                <label><?=lang('label_active');?></label><br>
                <?=bootstrapBtnGroupUnique(1,['no','yes'],['name'=>'active']);?>
            </div>
            <div class="forminfo">
                <p></p>
            </div>
            <div class="form-group">
                <?=bootstrapHelpBlock(lang('label_help_input_required'));?>
            </div>
            <button type="submit" class="btn btn-default"><?=lang('label_save'); ?></button>
            </form>
        </div>
    </div>
</div>