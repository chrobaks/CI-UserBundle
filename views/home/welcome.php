<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="container top">
    <div class="well teaser home">
        <div class="row">
            <div class="col-md-1"></div>
            <div class="col-md-10 text-center">
                <h4><?=lang('teaser_hd');?></h4>
                <p><?=lang('teaser_text');?></p>
                <hr>
                <div class="btn-wrapper">
                    <a class="btn btn-default">
                        <span class="glyphicon glyphicon-download-alt"></span>
                        Download als Zip oder Tar
                    </a>
                    <img src="public/image/app/logo_green.png">
                    <a class="btn btn-default">
                        <img src="public/image/app/Git-Icon-Black.png" class="icon-git">
                        Download von GitHub (Repo)
                    </a>
                </div>
                <hr>
                <ul>
                    <li>CodeIgniter 3</li>
                    <li>PHP 5,7</li>
                    <li>jQuery 3</li>
                    <li>Bootstrap 3</li>
                </ul>
            </div>
            <div class="col-md-1 clearfix"></div>
        </div>
    </div>
    <div class="row content-home">
        <div class="col-md-6">
            <div class="panel panel-success">
                <div class="panel-heading">
                    <h3 class="panel-title"><?=lang('panel_title_1');?></h3>
                </div>
                <div class="panel-body">
                    <?=bootstrapTextBlock(lang('list_backend_features'));?>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="panel panel-success">
                <div class="panel-heading">
                    <h3 class="panel-title"><?=lang('panel_title_2');?></h3>
                </div>
                <div class="panel-body">
                    <?=bootstrapTextBlock(lang('list_frontend_features'));?>
                </div>
            </div>
        </div>
    </div>

    <div class="well teaser footer">
        <div class="row">
            <div class="col-md-12">
                <ul>
                    <li><span class="glyphicon glyphicon-time"></span> Create at December 2016</li>
                    <li><a href="http://www.netcoapp.de/" target="_blank"><span class="glyphicon glyphicon-info-sign"></span> CI-UserBundle &copy; created by NetCoApp</a></li>
                    <li><a class="dialog-request"
                           data-space="ajax"
                           data-act="help"
                           data-key="license"><span class="glyphicon glyphicon-cog"></span> MIT license</a></li>
                    <li><a data-toggle="modal" data-target="#myAppDialogContactModal"><span class="glyphicon glyphicon-envelope"></span> contactUs</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>