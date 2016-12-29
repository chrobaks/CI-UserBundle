<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="container">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="signup-panel panel panel-default">
                <div class="panel-heading">
                    <div class="panel-title">
                        <span class="glyphicon glyphicon-warning-sign"></span>
                        <?=$tplTitle;?>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <?php if($tplData['msg']): ?>
                                <div class="alert alert-success" role="alert"><?=$tplData['msg']; ?></div>
                            <?php endif;?>
                            <?php if($tplData['error']): ?>
                                <div class="alert alert-danger" role="alert"><?=$tplData['error']; ?></div>
                            <?php endif;?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <hr>
                            <a href="<?=site_url();?>">
                                <span class="glyphicon glyphicon-home"></span>&nbsp;&nbsp;
                                <?=lang('label_show_homepage');?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

