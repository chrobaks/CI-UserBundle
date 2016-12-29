<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!-- start render devTool /-->
<div id="devTool" data-service-controller="devTool">
    <div id="devToolBtn"><span class="glyphicon glyphicon-cog"></span>  UserBundle DevTool</div>
    <div id="devToolContent">
        <div class="content-wrapper">
            <div class="content-data">

                <?php if( ! empty($envData)):?>
                    <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="headingOne">
                                <h4 class="panel-title">
                                    <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse" aria-expanded="false" aria-controls="collapse" class="collapsed">
                                        <span class="glyphicon glyphicon-folder-close"></span>
                                        <strong>viewData</strong>
                                    </a>
                                </h4>
                            </div>
                            <div id="collapse" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading" aria-expanded="false">
                                <div class="panel-body">
                                    <?php foreach($envData as $k=>$v):?>
                                        <?php if( ! is_array($v) && ! is_object($v)):?>
                                            <div class="row">
                                                <div class="col-md-4 text-right"><strong><?=$k;?></strong></div>
                                                <div class="col-md-8"><?=$v;?></div>
                                            </div>
                                        <?php endif;?>
                                    <?php endforeach;?>
                                </div>
                            </div>
                        </div>
                        <?php foreach($envData as $k=>$v):?>
                            <?php if( is_array($v) || is_object($v)):?>
                                <div class="panel panel-default">
                                    <div class="panel-heading" role="tab" id="headingOne">
                                        <h4 class="panel-title">
                                            <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse<?=$k;?>" aria-expanded="false" aria-controls="collapse<?=$k;?>" class="collapsed">
                                                <span class="glyphicon glyphicon-folder-close"></span>
                                                <strong><?=$k;?></strong>
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapse<?=$k;?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading<?=$k;?>" aria-expanded="false">
                                        <div class="panel-body">
                                            <?php foreach($v as $vk=>$vv):?>
                                                <div class="row">
                                                    <div class="col-md-4 text-right"><strong><?=$vk;?></strong></div>
                                                    <div class="col-md-8">
                                                        <?php if( !  is_array($vv) && ! is_object($vv)):?>
                                                            <?=htmlentities($vv);?>
                                                        <?php else:?>
                                                            <pre><?=var_dump($vv);?></pre>
                                                        <?php endif;?>
                                                    </div>
                                                </div>
                                            <?php endforeach;?>
                                        </div>
                                    </div>
                                </div>
                            <?php endif;?>
                        <?php endforeach;?>
                    </div>
                <?php endif;?>

            </div>
        </div>
    </div>
</div>
<!-- end render devTool /-->
