<?php include ('../gtm/src/templates/header.php');?>

<div class="row">
    <div class="col-3">
       <?php 
        if ($user->isAdmin()) {
            include('menuAdmin.php'); 
        } else {
            throw new \Gtm\Exceptions\NotAllowException();
        }
       ?>

    </div>
    <div class="col text-start">
      <div id="block"></div>
    </div>
</div>
<script src="../gtm/src/script/scriptAdmin.js"></script>
<?php include('../gtm/src/templates/footer.php');?>