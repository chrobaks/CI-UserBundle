<?php
/**
 * Created by PhpStorm.
 * Date: 23.09.2016
 */
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="container">
    <div class="row">
        <div class="col-md-12"><h3 class="page-header"><?=$tplTitle.' / '.$tplData['documentation_title'];?></h3></div>
    </div>
    <?php if(userAccessRootExists($sessionUser)):?>
    <div class="row">
        <div class="col-md-12">
            <ul class="listrow clearfix">
                <li>
                    <div class="well well-sm">
                        <a data-toggle="modal" data-target="#myModalChapter">
                            <span class="glyphicon glyphicon-edit"></span> <?=lang('label_content_create');?>
                        </a>
                    </div>
                </li>
                <li>
                    <div class="well well-sm">
                        zeige :
                    <?=bootstrapSelect(
                        $tplData['filter']['active'],
                        ['data-filter-module'=>'filter','data-act'=>'chapter/'.$tplData['category'],'data-val'=>$tplData['chapter'],'data-filter'=>'active'],
                        $tplData['active'],
                        false,
                        true,
                        ['val'=>lang('label_content_all'),'key'=>'']
                    );?>
                    </div>
                </li>
            </ul>
            <hr>
        </div>
    </div>
    <?php endif;?>
    <?php if( ! empty($tplData['contents'])):?>
    <?php foreach($tplData['contents'] as $row => $col):?>
    <div class="row">
        <div class="col-md-12 content-wrapper">
            <?php
            if ($col['is_pre_tag'] == '0') {
                echo '<div data-clone-source="content_'.$col['id'].'">'.html_entity_decode($col['content'], ENT_QUOTES, 'UTF-8').'</div>';
            } else {
                echo '<pre data-clone-source="content_'.$col['id'].'">'.$col['content'].'</pre>';
            }
            ?>
        </div>
    </div>
    <?php if(userAccessRootExists($sessionUser)):?>
    <div class="row">
        <div class="col-md-12 toolbar toolbar-form">
            <ul class="listrow clearfix">
                <li>
                    <div class="well well-sm">
                        <a data-space="documentation" data-act="deletecontent" data-val="<?=$col['id'];?>" data-toggle="confirmation" data-placement="top" title="<?=lang('label_content_delete'); ?>?">
                            <span class="glyphicon glyphicon-trash"></span> <?=lang('label_content_delete');?>
                        </a>
                    </div>
                </li>
                <li>
                    <div class="well well-sm">
                        <a data-module="clone" data-clone-modal-id="#myModalEditContent" data-clone-target-id="#CloneChapterContent" data-clone-source-id="content_<?=$col['id'];?>" data-key="id" data-val="<?=$col['id'];?>">
                            <span class="glyphicon glyphicon-pencil"></span> <?=lang('label_content_edit');?>
                        </a>
                    </div>
                </li>
                <li>
                    <div class="well well-sm">
                        <?=lang('label_page_index');?> :
                        <?=bootstrapSelectNumber(
                            ['start'=>1,'end'=>$tplData['maxContentsZIndex'][$col['documentation_chapter_id']]],
                            ['data-module'=>'colupdate','data-act'=>'updatecontent','data-id'=>$col['id'],'data-key'=>'zIndex'],
                            $col['zIndex'],
                            false,
                            true
                        );?>
                    </div>
                </li>
                <li>
                    <?=bootstrapBtnGroupUnique(
                        $col['active'],
                        ['no','yes'],
                        ['name'=>'active'],
                        ['data-module'=>'colupdate','data-act'=>'updatecontent','data-id'=>$col['id'],'data-key'=>'active'],
                        ['eye-slash','eye']
                    );?>
                </li>
            </ul>
        </div>
    </div>
    <?php endif;?>
    <?php endforeach;?>
    <?php endif;?>
</div>
<!-- Modal create content -->
<div class="modal fade" id="myModalChapter" tabindex="-1" role="dialog" aria-labelledby="myModalChapter">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel"><?=lang('label_content_create');?></h4>
            </div>
            <?=form_open('documentation/createcontent',['id'=>'formNewchapter','class'=>'formAjaxRequest chapter','data-decode'=>'content']);?>
            <input type="hidden" name="documentation_chapter_id" value="<?=$tplData['documentation_chapter_id'];?>">
            <input type="hidden" name="<?=$csrf_token_name;?>" value="<?=$csrf_hash;?>">
            <div class="modal-body">
                <div class="form-group">
                    <label for="username"><?=lang('label_content');?>*</label>
                    <textarea name="content" class="data-scope"></textarea>
                </div>

                <div class="form-group">
                    <input data-source-id="filename" value="">
                    <a data-module="loadFileAsText"
                       data-target="textarea[name=content]"
                       data-source="input[data-source-id=filename]"><span class="glyphicon glyphicon-file"></span>Load file</a>
                </div>
                <div class="form-group">
                    <label><?=lang('label_page_index');?>*</label><br>
                    <input type="number" name="zIndex" class="data-scope" value="<?=($tplData['maxContentsZIndex'][$tplData['documentation_chapter_id']]+1);?>" min="1" max="<?=($tplData['maxContentsZIndex'][$tplData['documentation_chapter_id']]+1);?>">
                </div>
                <div class="form-group">
                    <label><?=lang('label_active');?></label><br>
                    <?=bootstrapBtnGroupUnique(1,['no','yes'],['name'=>'active']);?>
                </div>
                <div class="form-group">
                    <label><?=lang('label_is_pre_tag');?></label><br>
                    <?=bootstrapBtnGroupUnique(0,['no','yes'],['name'=>'is_pre_tag']);?>
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
<!-- Modal create content -->
<div class="modal fade" id="myModalEditContent" tabindex="-1" role="dialog" aria-labelledby="myModalEditContent">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel"><?=lang('label_content_edit');?></h4>
            </div>
            <?=form_open('documentation/updatecontent',['id'=>'formUpdateChapter','class'=>'formAjaxRequest chapter','data-decode'=>'content']);?>
            <input type="hidden" name="id" value="">
            <input type="hidden" name="<?=$csrf_token_name;?>" value="<?=$csrf_hash;?>">
            <div class="modal-body">
                <div class="form-group">
                    <label for="content"><?=lang('label_content');?>*</label>
                    <textarea name="content" id="CloneChapterContent"></textarea>
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
