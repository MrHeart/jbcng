<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Users
            <small>Control panel</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Dashboard</li>
        </ol>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Surname</th>
                    <th>Other Names</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Addrerss</th>
                    <th>State</th>
                    <th>Lga</th>
                    <th>Action</th>

                </tr>
                </thead>
                <?php
                foreach($all_users as $all_users){
                    $userId = $all_users['id'];
                    $userSurname = $all_users['surname'];
                    $userOtherName = $all_users['other_names'];
                    $userEmail = $all_users['email'];
                    $userPhone = $all_users['phone'];
                    $userAddress = $all_users['address'];
                    $userState = $all_users['state'];
                    $userLga = $all_users['lga'];


                ?>


                    <tbody>

                    <tr>
                        <td><?php echo $userId; ?></td>
                        <td><?php echo $userSurname; ?></td>
                        <td><?php echo $userOtherName; ?></td>
                        <td><?php echo $userEmail; ?></td>
                        <td><?php echo $userPhone; ?></td>
                        <td><?php echo $userAddress; ?></td>
                        <td><?php echo $userState; ?></td>
                        <td><?php echo $userLga; ?></td>
                        <td><a href="<?= base_url('Users/ViewUser/'.$userId) ?>" class="btn btn-primary">View Profile</a></td>

                    </tr>
                    </tbody>
                <?php } ?>



            </table>
        </div>
</div>

</div>
</div>

</div>
<!-- /.content-wrapper -->