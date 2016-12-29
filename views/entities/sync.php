<?php
/**
 * Created by PhpStorm.
 * User: Chrobak
 * Date: 23.09.2016
 * Time: 15:09
 */
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="container">
    <div class="row">
        <div class="col-md-12"><h3 class="page-header"><?=lang('title');?></h3></div>
    </div>
</div>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <table class="sync-table" data-container="ajax">
                <tr class="sync-label">
                    <td><span class="glyphicon glyphicon-file"></span>PHP Files</td>
                    <td class="sync-btn-group">Sync Aktion</td>
                    <td><span class="glyphicon glyphicon-hdd"></span>Database</td>
                </tr>
                <?php if(! empty($tplData['syncConf']['hasConfig'])):?>
                    <?php foreach($tplData['syncConf']['hasConfig'] as $k=>$v):?>
                        <tr class="sync-row">
                            <td>
                                <div>
                                    <a class="ajax-container-request locked-<?=$v['app_dependence'];?>"
                                       data-act="updateappdependence"
                                       data-val="<?=$v['name'];?>"
                                       data-key="syncname"><span class="glyphicon glyphicon-lock"></span></a>
                                    <span class="glyphicon glyphicon-file"></span><strong><?=$v['app_dependence'];?></strong> <?=$v['name'];?>
                                </div>
                            </td>
                            <td class="sync-btn-group">
                                <?php if($v['app_dependence']=='public'):?>
                                <a class="ajax-container-request"
                                   data-request-status="left"
                                   data-act="createfileconf"
                                   data-val="<?=$v['name'];?>"
                                   data-key="syncname"><span class="glyphicon glyphicon-arrow-left"></span>toPHP</a>
                                <?php else:?>
                                <a class="locked"><span class="glyphicon glyphicon-lock"></span>toPHP</a>
                                <?php endif;?>
                                <?php if($v['app_dependence']=='public'):?>
                                <a class="ajax-container-request"
                                   data-request-status="right"
                                   data-act="createdbconf"
                                   data-val="<?=$v['name'];?>"
                                   data-key="syncname">toDB<span class="glyphicon glyphicon-arrow-right"></span></a>
                                <?php else:?>
                                    <a class="right locked">toDB<span class="glyphicon glyphicon-lock"></span></a>
                                <?php endif;?>
                            </td>
                            <td><div><span class="glyphicon glyphicon-hdd"></span><?=$v['name'];?></div></td>
                        </tr>
                    <?php endforeach;?>
                <?php endif;?>
                <?php if(! empty($tplData['syncConf']['noConfig'])):?>
                    <?php foreach($tplData['syncConf']['noConfig'] as $k=>$v):?>
                        <tr class="sync-row">
                            <td><div>no config file</div></td>
                            <td class="sync-btn-group">
                                <a class="ajax-container-request"
                                   data-request-status="left"
                                   data-act="createfileconf"
                                   data-val="<?=$v['name'];?>"
                                   data-key="syncname"><span class="glyphicon glyphicon-arrow-left"></span>toPHP</a>
                            </td>
                            <td><div><span class="glyphicon glyphicon-hdd"></span><?=$v['name'];?></div></td>
                        </tr>
                    <?php endforeach;?>
                <?php endif;?>
            </table>
        </div>
    </div>
</div>