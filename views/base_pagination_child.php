<?php
/**
 * Created by PhpStorm
 * Date: 23.09.2016
 */
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="row">
    <div class="col-md-12 group-filter" data-dependence-child="topfilter">
        <div class="pagiantion-container">
            <ul class="listrow clearfix">
                <li>
                    <div class="well well-sm">
                        <?=lang('label_max_entries');?> :
                        <?=bootstrapSelect(
                            $tplData['pagination']['selection'],
                            ['data-filter-module'=>'group-filter','data-filter'=>'pages'],
                            $tplData['pagination']['actualLimit'],
                            true,
                            false,
                            ['val'=>lang('label_no_limit'),'key'=>'all']
                        );?>
                    </div>
                </li>
                <?php if($tplData['pagination']['data'] != ''):?>
                    <li>
                        <div class="well well-sm" data-filter-module="pagination">
                            <?=lang('label_page');?> :
                            <?=$tplData['pagination']['data'];?>
                            <input
                                type="hidden"
                                value="0"
                                data-filter-module="group-filter"
                                data-filter="page">
                        </div>
                    </li>
                <?php endif;?>
            </ul>
        </div>
    </div>
</div>
