<!-- page content -->
<div class="right_col" role="main">
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>View Authors</h3>
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
                        <h2>All Authors</h2>
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
                       <div class="row">
                           <?php
                           foreach($authors as $author) {
                               if(!empty($author->avatar) && !is_null($author->avatar)){
                                   $avatar = $author->avatar;
                               }
                               else{
                                   $avatar = base_url()."assets/images/user.png";
                               }
                               ?>

                               <div class="col-md-55">
                                   <div class="thumbnail">
                                       <div class="image view view-first">
                                           <img style="width: 100%; display: block;"
                                                src="<?php echo $avatar ?>" alt="image"/>

                                           <div class="mask">
                                               <a href="<?php echo base_url() ?>Authors/View/<?php echo $author->Id ?>">
                                               <p><?php echo $author->first_name." ".$author->other_name." ".$author->last_name; ?></p>
                                               </a>

                                               <div class="tools tools-bottom">
                                                   <a href="<?php echo base_url() ?>Authors/View/<?php echo $author->Id ?>"><i class="fa fa-eye" title="View"></i></a>
                                                   <a href="#" onclick="author_edit(<?= $author->Id ?>)"><i class="fa fa-pencil" title="Edit"></i></a>
                                                   <a href="#" onclick="author_delete(<?= $author->Id ?>)"><i class="fa fa-times" title="Delete"></i></a>
                                               </div>
                                           </div>
                                       </div>
                                   </div>
                               </div>
                               <?php
                           }

                           ?>

                       </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /page content -->
