<?php //include ('../gtm/src/templates/header.php');?>
<?php include (__DIR__.'/../header.php');?>

<div class="row">
    <div class="col-2">
       <?php 
        if (!isset($user)){
            echo isset($error) && strlen($error)>0? $error.'<br>' : '';
        }
        elseif($user->isAdmin()) {
            include('menuAdmin.php'); 
        }
        else {
            throw new \Gtm\Exceptions\NotAllowException();
        }
       ?>

    </div>
    <div class="col text-start">
      <div id="block"></div>
    </div>
</div>
<!-- <script src="../gtm/src/script/scriptAdmin.js"></script> -->
<!-- <script src="<?=__DIR__.'/../../../../../../../gtm/src/script/scriptAdmin.js'?>"></script> -->

<?php //include('../gtm/src/templates/footer.php');?>
<?php include (__DIR__.'/../footer.php');?>