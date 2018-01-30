<?php
/**
 * Created by PhpStorm.
 * User: Mr Heart
 * Date: 10/2/2016
 * Time: 9:21 PM
 */
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Subjects
            <small>Create</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Subjects</a></li>
            <li class="active">Create</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">

        <!-- Default box -->
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Create a new subject</h3>

                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                        <i class="fa fa-minus"></i></button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
                        <i class="fa fa-times"></i></button>
                </div>
            </div>
            <div class="box-body">
                <div> <i class="text-success"><?= @$message ?></i></div>
                <form method="post" action="">
                    <div>
                        <label>Name:</label>
                        <input type="text" class="form-control" placeholder="subject name" name="name">
                    </div>

                    <div>
                        <label>Department:</label> <br />
                        <select class="form-control" name="department">
                            <?php
                            foreach($departments as $department){
                                ?>
                                <option value="<?= $department->id ?>" ><?= $department->name ?></option>
                            <?php
                            }
                            ?>

                        </select>
                    </div>

                    <div>

                        <input type="submit" class="btn btn-primary" value="Submit" name="submit">
                    </div>

                </form>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                Footer
            </div>
            <!-- /.box-footer-->
        </div>
        <!-- /.box -->

    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

