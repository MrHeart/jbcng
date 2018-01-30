<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Dashboard
            <small>Control panel</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Dashboard</li>
        </ol>
    </section>


    <?php  foreach($users as $users){
    $userSurname = $users['surname'];
    $userOtherName = $users['other_names'];
    $userEmail = $users['email'];
    $userPhone = $users['phone'];
    $userAddress = $users['address'];
    $userState = $users['state'];
    $userLga = $users['lga'];


    ?>



    <section class="content">

        <div class="row">
            <div class="col-md-3">

                <!-- Profile Image -->
                <div class="box box-primary">
                    <div class="box-body box-profile">
                        <img class="profile-user-img img-responsive img-circle" src="../../dist/img/user4-128x128.jpg" alt="User profile picture">
                        <h3 class="profile-username text-center"><?php echo $userSurname ?> <?php echo $userOtherName ?></h3>
                        <p class="text-muted text-center">Software Engineer</p>



                        <a href="#" class="btn btn-primary btn-block"><b>Block Student</b></a>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->

                <!-- About Me Box -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo $userSurname ?>'s Details</h3>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <strong><i class=""></i>  Email</strong>
                        <p class="text-muted">
                            <?php echo $userEmail ?>
                        </p>

                        <hr>

                        <strong><i class="fa fa-map-marker margin-r-5"></i> Phone Number</strong>
                        <p class="text-muted"><?php echo $userPhone ?></p>

                        <hr>
                        <strong><i class="fa fa-map-marker margin-r-5"></i> Contact Address</strong>
                        <p class="text-muted"><?php echo $userPhone ?></p>

                        <td><?php echo $userState ?></td>
                        <td><?php echo $userLga ?></td>

                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div><!-- /.col -->
            <div>
                <div class="col-md-9">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#activity" data-toggle="tab">Details</a></li>
                            <li><a href="#timeline" data-toggle="tab">Activity</a></li>
                            <li><a href="#settings" data-toggle="tab">Settings</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="active tab-pane" id="activity">
                                <!-- Post -->
                                <div class="container-fluid">



                                    <h1 class="text-center btn btn-primary" style="width: 100%;"><?php echo $userSurname ?>'s Details</h1>
                                    <table class="table table-bordered">
                                        <thead>
                                        <tr>
                                            <th>First Name</th>
                                            <td><?php echo $userSurname ?></td>
                                        </tr>
                                        <tr>
                                            <th>Other Names</th>
                                            <td><?php echo $userOtherName ?></td>
                                        </tr>
                                        <tr>
                                            <th>Email Address</th>
                                            <td><?php echo $userEmail ?></td>
                                        </tr>
                                        <tr>
                                            <th>Phone Number</th>
                                            <td><?php echo $userPhone ?></td>
                                        </tr>
                                        <tr>
                                            <th>Address</th>
                                            <td><?php echo $userAddress ?></td>
                                        </tr>
                                        <tr>
                                            <th>State</th>
                                            <td><?php echo $userState ?></td>
                                        </tr>
                                        <tr>
                                            <th>Local Government</th>
                                            <td><?php echo $userLga ?></td>
                                        </tr>
                                        </thead>
                                    </table>

                                </div>

                            </div><!-- /.tab-pane -->
                            <div class="tab-pane" id="timeline">
                                <!-- The timeline -->
                            </div><!-- /.tab-pane -->

                            <div class="tab-pane" id="settings">
                                <form class="form-horizontal">
                                    <div class="form-group">
                                        <label for="inputName" class="col-sm-2 control-label">Name</label>
                                        <div class="col-sm-10">
                                            <input type="email" class="form-control" id="inputName" placeholder="Name">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail" class="col-sm-2 control-label">Email</label>
                                        <div class="col-sm-10">
                                            <input type="email" class="form-control" id="inputEmail" placeholder="Email">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputName" class="col-sm-2 control-label">Name</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="inputName" placeholder="Name">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputExperience" class="col-sm-2 control-label">Experience</label>
                                        <div class="col-sm-10">
                                            <textarea class="form-control" id="inputExperience" placeholder="Experience"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputSkills" class="col-sm-2 control-label">Skills</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="inputSkills" placeholder="Skills">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-offset-2 col-sm-10">
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox"> I agree to the <a href="#">terms and conditions</a>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-offset-2 col-sm-10">
                                            <button type="submit" class="btn btn-danger">Submit</button>
                                        </div>
                                    </div>
                                </form>
                            </div><!-- /.tab-pane -->
                        </div><!-- /.tab-content -->
                    </div><!-- /.nav-tabs-custom -->
                </div><!-- /.col -->
            </div><!-- /.row -->


                <?php } ?>




            </div>

        </div><!-- /.row -->

    </section><!-- /.content -->
</div><!-- /.content-wrapper -->







<!-- /.content-wrapper -->