<?php
/**
 * Created by PhpStorm.
 * User: Mr Heart
 * Date: 10/7/2016
 * Time: 4:05 AM
 */
?>
<div class="modal" id="editModal">
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
                            <form method="post" action="" id="edit_form">
                                <input type="text" name="name" id="name" class="form-control">
                                <input type="submit" value="Submit" class="btn btn-primary">

                            </form>
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
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Departments
            <small>Manage</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Departments</a></li>
            <li class="active">Manage</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">

        <!-- Default box -->
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Manage Departments</h3>

                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                        <i class="fa fa-minus"></i></button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
                        <i class="fa fa-times"></i></button>
                </div>
            </div>
            <div class="box-body">
                <div> <i class="text-success"><?= @$message ?></i></div>
                <table class="table" id="dataTable">
                    <thead>
                    <th>Name</th>
                    <th>Action</th>
                    </thead>
                    <tbody>
                    <?php
                    foreach($departments as $department){

?>
                    <tr>
                        <td><?= $department->name ?></td>
                        <th>
                            <button class="btn btn-primary" title="Edit" onclick="department_edit(<?= $department->id ?>)"> <i class="glyphicon glyphicon-pencil"></i> </button>
                            <button class="btn btn-danger" title="Delete"> <i class="glyphicon glyphicon-remove"></i> </button>
                        </th>

                    </tr>
                    <?php
                    }

?>
                    </tbody>
                </table>


            </div>
            <!-- /.box-body -->

        </div>
        <!-- /.box -->

    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->


