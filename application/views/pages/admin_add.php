<!-- page content -->
<div class="right_col" role="main">
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>Admins</h3>
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
                        <h2>Add Admin User</h2>
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
                    <div class="x_content">
                        <form method="post" action="" class="col-md-6 col-xs-12">
                            <h2>Login Details</h2>
                            <hr>
                            <h2><?= @$message; ?></h2>
                            <div>
                                <label>Login Username</label>
                                <input type="text" placeholder="Login Username" class="form-control" name="name">
                            </div>

                            <div class="clearfix"></div>

                            <div>
                                <label>Password</label>
                                <input type="password" placeholder="Login Password" class="form-control" name="password">
                            </div>

                            <div>
                                <label>Confirm Password</label>
                                <input type="password" placeholder="Confirm Password" class="form-control" name="confirm_password">
                            </div>

                            <div>
                                <label>Full name</label>
                                <input type="text" placeholder="Full name" class="form-control" name="fullname">
                            </div>
                            <p></p> <br />

                            <h2>Access Rights</h2>
                            <hr>

                            <div>
                                <input type="checkbox" data-switchery="true" class="js-switch" name="authors" value="authors">
                                <label>Authors</label>
                            </div>

                            <div>
                                <input type="checkbox" data-switchery="true" class="js-switch" name="narrators">
                                <label>Narrators</label>
                            </div>

                            <div>
                                <input type="checkbox" data-switchery="true" class="js-switch" name="payments">
                                <label>Payments</label>
                            </div>

                            <div>
                                <input type="checkbox" data-switchery="true" class="js-switch" name="support">
                                <label>Support</label>
                            </div>

                            <div>
                                <input type="checkbox" data-switchery="true" class="js-switch" name="editor">
                                <label>Editor</label>
                            </div>

                            <div>
                                <input type="checkbox" data-switchery="true" class="js-switch" name="settings">
                                <label>Settings</label>
                            </div>
                            <br />
                            <div>
                                <input type="submit" class="btn btn-primary" value="Create" name="create">
                            </div>


                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /page content -->
