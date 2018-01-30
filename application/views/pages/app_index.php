<?php
/**
 * Created by PhpStorm.
 * User: Mr Heart
 * Date: 10/4/2016
 * Time: 4:49 AM
 */
?>
<div class="modal" id="welcomeModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
                <div class="container">

                    <div class="row">
                        <div class="col-md-2"></div>
                        <div class="col-md-8">
                            <h2 class="text-center">Welcome</h2>
                            <div>
                                <img id="welcomeImage" src="<?= base_url() ?>assets/dist/img/user2-160x160.jpg" class="img-circle pull-left" alt="User Image" /> <br />
                                <div>
                                    <div>
                                        <label>Name: </label> Ojabo John Heart
                                    </div>
                                    <div>
                                        <label>School: </label> Mafogram
                                    </div>
                                    <div>
                                        <label>Department: </label> Science
                                    </div>

                                    <div>
                                        <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Ok, I got it</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2"></div>

                    </div>
                </div>
            </div>
            <div class="modal-footer">

            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div class="col-md-2"></div>

<div class="col-md-8">
<?php
foreach($subjects as $subject) {
    ?>
    <div id="flipcard" class="col-md-4 ">
        <div class="front alert alert-danger">
            <h2><?= $subject->name ?></h2>
        </div>
        <div class="back alert alert-success">
            <h2><?= $subject->name ?></h2>
        </div>
    </div>
    <?php
}
    ?>

</div>

<div class="col-md-2"></div>
