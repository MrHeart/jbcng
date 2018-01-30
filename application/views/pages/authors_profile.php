<!-- page content -->
<div class="right_col" role="main">
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>Author Profile</h3>
            </div>

            <div class="title_right">
                <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Search for...">
                    <span class="input-group-btn">
                      <button class="btn btn-default" type="button">Go!</button>
                    </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="clearfix"></div>

        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>User Report <small>Activity report</small></h2>
                        <ul class="nav navbar-right panel_toolbox">
                            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                            </li>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="#">Settings 1</a>
                                    </li>
                                    <li><a href="#">Settings 2</a>
                                    </li>
                                </ul>
                            </li>
                            <li><a class="close-link"><i class="fa fa-close"></i></a>
                            </li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>

                    <?php
                    foreach($authors as $author) {
                    if(!empty($author->avatar) && !is_null($author->avatar)){
                        $avatar = $author->avatar;
                    }
                    else{
                        $avatar = base_url()."assets/images/user.png";
                    }
                    ?>
                    <div class="x_content">
                        <div class="col-md-3 col-sm-3 col-xs-12 profile_left">
                            <div class="profile_img">
                                <div id="crop-avatar">
                                    <!-- Current avatar -->
                                    <img class="img-responsive avatar-view" src="<?php echo $avatar ?>" alt="Avatar" title="Change the avatar">
                                </div>
                            </div>
                            <h3><?php echo $author->first_name." ".$author->other_name." ".$author->last_name; ?></h3>

                            <ul class="list-unstyled user_data">
                                <li><i class="fa fa-map-marker user-profile-icon"></i> <?php echo $author->email ?>
                                </li>

                                <li>
                                    <i class="fa fa-briefcase user-profile-icon"></i> <?php echo ucfirst($author->role) ?>
                                </li>

                                <li class="m-top-xs">
                                    <i class="fa fa-external-link user-profile-icon"></i>
                                    <a href="http://www.kimlabs.com/profile/" target="_blank"><?=$author->phone ?></a>
                                </li>
                            </ul>

                            <a class="btn btn-success" data-toggle="modal" onclick="author_edit(<?= $author->Id ?>)"><i class="fa fa-edit m-right-xs"></i>Edit Profile</a>
                            <br />
                            <!-- start Books -->
                            <h4>Books</h4>
                            <?php
                            foreach($author_books as $book){
                                ?>

                            <ul class="list-unstyled user_data">
                                <li>
                                    <p><?= $book->book_title ?></p>
                                    <div class="progress progress_sm">
                                        <div class="progress-bar bg-green" role="progressbar" data-transitiongoal="100"></div>
                                    </div>
                                </li>
                            </ul>
                            <!-- end of Books -->
                            <?php } ?>

                        </div>
                        <div class="col-md-9 col-sm-9 col-xs-12">

                            <div class="" role="tabpanel" data-example-id="togglable-tabs">
                                <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                                    <li role="presentation" class="active"><a href="#tab_content1" id="home-tab" role="tab" data-toggle="tab" aria-expanded="true">Recent Activity</a>
                                    </li>
                                    <li role="presentation" class=""><a href="#tab_content2" role="tab" id="profile-tab" data-toggle="tab" aria-expanded="false">Books Worked on</a>
                                    </li>
                                    <li role="presentation" class=""><a href="#tab_content3" role="tab" id="profile-tab2" data-toggle="tab" aria-expanded="false">Profile</a>
                                    </li>
                                </ul>
                                <div id="myTabContent" class="tab-content">
                                    <div role="tabpanel" class="tab-pane fade active in" id="tab_content1" aria-labelledby="home-tab">

                                        <?php
                                        foreach($author_activities as $activity){
                                            $time = new DateTime($activity->date);
                                            $day = $time->format("d");
                                            $month = $time->format("M");
                                        ?>
                                        <!-- start recent activity -->
                                        <ul class="messages">
                                            <li>
                                                <img src="<?= $avatar ?>" class="avatar" alt="Avatar">
                                                <div class="message_date">
                                                    <h3 class="date text-info"><?= $day ?></h3>
                                                    <p class="month"><?= $month ?></p>
                                                </div>
                                                <div class="message_wrapper">
                                                    <h4 class="heading"><?= $author->first_name." ".$author->last_name; ?></h4>
                                                    <blockquote class="message"><?= $activity->activity ?></blockquote>
                                                    <br />
                                                    <p class="url">
                                                        <span class="fs1 text-info" aria-hidden="true" data-icon="?"></span>
                                                        <a href="#"><span class="timeago fa fa-paperclip" title="<?= $activity->date ?>">  <?= $activity->date ?></span>  </a>
                                                    </p>
                                                </div>
                                            </li>

                                        </ul>
                                        <!-- end recent activity -->
                                        <?php } ?>

                                    </div>
                                    <div role="tabpanel" class="tab-pane fade" id="tab_content2" aria-labelledby="profile-tab">

                                        <!-- start user projects -->
                                        <table class="data table table-striped no-margin">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Book Name</th>
                                                <th>Book Type</th>
                                                <th class="hidden-phone">Published By</th>
                                                <th>Date Created</th>
                                                <th>Status</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            $sn = 0;
                                            foreach($author_books as $book){
                                                $sn++;
                                            ?>
                                            <tr>
                                                <td><?= $sn?></td>
                                                <td><?=$book->book_title ?></td>
                                                <td><?= $book->book_type ?></td>
                                                <td class="hidden-phone"><?= $book->published_by ?></td>
                                                <th><?= $book->date_added ?></th>
                                                <td class="vertical-align-mid">
                                                    <div class="progress">
                                                        <div class="progress-bar progress-bar-success" data-transitiongoal="100"></div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php } ?>
                                            </tbody>
                                        </table>
                                        <!-- end user projects -->

                                    </div>
                                    <div role="tabpanel" class="tab-pane fade" id="tab_content3" aria-labelledby="profile-tab">
                                        <p>
                                        <table class="data table table-striped no-margin">
                                            <thead>
                                            <tr>
                                                <th></th>
                                                <th></th>

                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            foreach($authors as $author){
                                            ?>
                                            <tr>
                                                <td>First Name</td>
                                                <td><?= $author->first_name?></td>
                                                </tr>
                                                <tr>
                                                <td>Other Name</td>
                                                <td><?= $author->other_name?></td>
                                                    </tr>
                                                    <tr>
                                                <td>Last Name</td>
                                                <td><?= $author->last_name?></td>
                                                        </tr>
                                                        <tr>
                                                <td>Gender</td>
                                                <td><?= $author->gender ?></td>
                                                            </tr>
                                                            <tr>
                                                <td>Email</td>
                                                <td><?= $author->email?></td>
                                                                </tr>
                                                                <tr>
                                                <td>Phone</td>
                                                <td><?= $author->phone?></td>
                                            </tr>
                                                <tr>
                                                    <td>Role</td>
                                                    <td><?= $author->role?></td>
                                                </tr>
                                                <tr>
                                                    <td>Signup Date</td>
                                                    <td><?= $author->signup_date?></td>
                                                </tr>
                                                <tr>
                                                    <td>Last seen</td>
                                                    <td><?= $author->last_login?></td>
                                                </tr>
                                            <?php } ?>
                                            </tbody>
                                            </table>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /page content -->