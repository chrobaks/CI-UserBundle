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
                    <a class="btn btn-default">
                        <img src="public/image/app/Git-Icon-Black.png" class="icon-git">
                        Download von GitHub (Repo)
                    </a>
                </div>
            </div>
            <div class="col-md-1"></div>
        </div>
    </div>
    <div class="row content-home">
        <div class="col-md-6">
            <div class="panel panel-success">
                <div class="panel-heading">
                    <h3 class="panel-title"><?=lang('panel_title_1');?></h3>
                </div>
                <div class="panel-body">
                    <p>User Authentification</p>
                    <p>Route Secure</p>
                    <p>Session Handling</p>
                    <p>Database Entity Konfiguration &amp; Webinterface</p>
                    <p>Views und Navigation mit Json-Konfiguration</p>
                    <p>Einbindung von Javascript und CSS mit Json-Konfiguration</p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="panel panel-success">
                <div class="panel-heading">
                    <h3 class="panel-title"><?=lang('panel_title_2');?></h3>
                </div>
                <div class="panel-body">
                    <p>Bootstrap CSS und Javascript</p>
                    <p>jQuery Javascript</p>
                    <p>Javascript Controller Service</p>
                    <p>Ajax Formulare und Events</p>
                    <p>Pagination &amp; Filter Modul</p>
                    <p>Responsive Design (Layout, Navigation)</p>
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