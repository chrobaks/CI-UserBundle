<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta content="IE=edge" http-equiv="X-UA-Compatible">
    <meta content="width=device-width, initial-scale=1" name="viewport">
    <meta content="" name="description">
    <meta content="" name="author">
    <link rel="icon" href="<?=site_url();?>public/image/app/favicon.ico">
    <title><?=$title;?></title>
    <!-- start render link tags for stylesheets /-->

    <?php if( ! empty($stylesheet)): ?>
        <?=implode("\n",$stylesheet);?>
    <?php endif; ?>

    <!-- end render link tags for stylesheets /-->
</head>
<body>
<!-- start render top navigation /-->
<?php if($showNavigation == true):?>
<?php $this->load->view('base_navigation'); ?>
<?php endif;?>
<!-- end render top navigation /-->

<!-- start load content template /-->
<?php if(array_key_exists('sidebar', $navigation) && ! empty($navigation['sidebar'])):?>
    <div id="page-sidebar-container" data-service-controller="sideBarNavigation">
        <div id="page-sidebar-navi">
            <?php $this->load->view('base_sidebarnavi'); ?>
        </div>
    </div>
    <div id="page-wrapper">
    <?php $this->load->view($tpl); ?>
    </div>
<?php else: ?>
    <?php $this->load->view($tpl); ?>
<?php endif;?>
<!-- end load content template /-->
<!-- start render application dialog modal /-->

<?php $this->load->view('appDialogModal'); ?>

<!-- end render application dialog modal /-->
<!-- start render application dialog contact modal /-->

<?php $this->load->view('appDialogContactModal'); ?>

<!-- end render application dialog contact modal /-->

<?php if($devTool):?>
<?php $this->load->view('devTool'); ?>
<?php endif;?>

<!-- start render script tags for javascript /-->
<script type="text/javascript">
    var app = {
        siteUrl : '<?=site_url();?>',
        servicePath : 'public/app/service.',
        readyMessage : '<?php if(isset($readyMessage)){echo $readyMessage;};?>',
        ignoreKeys : ['<?=$csrf_token_name;?>','content'],
        token : {key:'<?=$csrf_token_name;?>', hash:'<?=$csrf_hash;?>'},
        controller : '<?=$viewController;?>',
        controllerMsg : {
            <?php if(isset($tplData['controllerMsg'])){echo $tplData['controllerMsg'];};?>
        }
    };
</script>
<?php if( ! empty($javascript)): ?>
<?=implode("\n",$javascript);?>
<?php endif; ?>

<!-- end render script tags for javascript /-->
</body>
</html>