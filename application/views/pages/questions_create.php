<?php
/**
 * Created by PhpStorm.
 * User: Mr Heart
 * Date: 10/3/2016
 * Time: 3:04 PM
 */
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Questions
            <small>Create</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Questions</a></li>
            <li class="active">Create</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">

        <!-- Default box -->
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Create a new Question</h3>

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
                        <select class="form-control" name="subject">
                            <option>Subject</option>
                            <?php
                            foreach($subjects as $subject){

                                ?>
                                <option value="<?= $subject->id ?>"><?= $subject->name?></option>

                            <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div>
                        <label>Question:</label>
                        <textarea  class="form-control" placeholder="Question" name="question"></textarea>
                    </div>

                    <div>
                        <label>Option A:</label>
                        <input type="text" class="form-control" placeholder="Option A" name="a">
                    </div>

                    <div>
                        <label>Option B:</label>
                        <input type="text" class="form-control" placeholder="Option B" name="b">
                    </div>

                    <div>
                        <label>Option C:</label>
                        <input type="text" class="form-control" placeholder="Option C" name="c">
                    </div>

                    <div>
                        <label>Option D:</label>
                        <input type="text" class="form-control" placeholder="Option D" name="d">
                    </div>

                    <div>
                        <label>Answer:</label>
                        <select class="form-control" name="answer">
                            <option>A</option>
                            <option>B</option>
                            <option>C</option>
                            <option>D</option>
                        </select>
                    </div>

                        <input type="submit" class="btn btn-primary" value="Submit" name="submit">
                    </div>

                </form>
            </div>
            <!-- /.box-body -->

        </div>
        <!-- /.box -->

    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
