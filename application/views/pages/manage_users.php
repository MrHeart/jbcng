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

    <div class="container">
        <div class="message"></div>
        <table class="table table-bordered" id="manageUserTable">
            <button class="btn btn-default pull pull-right" data-toggle="modal" data-target="#addUsers" onclick="addUserModel()">Add Student</button>

            <thead>
            <tr>
                <th>Id</th>
                <th>Surname</th>
                <th>Other Names</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Addrerss</th>
                <th>State</th>
                <th>Lga</th>
                <th>Options</th>
            </tr>
            </thead>



        </table>
    </div>

    <!-- model for adding admins -->
    <div class="modal fade" id="addUsers">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Add Student</h4>
      </div>
        <form method="post" action="<?php echo base_url('Users/createUser') ?>" id="FormAll">
      <div class="modal-body">
              <div class="form-group">
                  <label for="surname">Surname</label>
                  <input type="text" class="form-control" id="surname" name="surname" placeholder="Enter Surname">
              </div>
              <div class="form-group">
                  <label for="names">Other Names</label>
                  <input type="text" class="form-control" id="other_names" name="other_names" placeholder="Other Names">
              </div>
          <div class="form-group">
              <label for="email">Email</label>
              <input type="email" class="form-control" id="email" name="email" placeholder="Email">
          </div>
          <div class="form-group">
              <label for="phone">Phone</label>
              <input type="text" class="form-control" id="phone" name="phone" placeholder="phone">
          </div>
          <div class="form-group">
              <label for="exampleInputPassword1">Address</label>
              <input type="text" class="form-control" id="address" name="address" placeholder="Address">
          </div>
          <div class="form-group">
              <label for="lga">Lga</label>
              <input type="text" class="form-control" id="lga" name="lga" placeholder="Lga">
          </div>
          <div class="form-group">
              <label for="state">State</label>
              <input type="text" class="form-control" id="state" name="state" placeholder="State">
          </div>
          <div class="form-group">
              <label for="password">Password</label>
              <input type="password" class="form-control" id="password" name="password" placeholder="Password">
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit"  class="btn btn-primary">Save</button>
      </div>
        </form>
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
</div>

    <!--  This is the edit modal -->
    <div class="modal fade" id="editUserModel">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Edit Studentt</h4>
                </div>
                <form method="post" action="<?php echo base_url('Users/edit') ?>" id="editForm">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="edit_surname">Surname</label>
                            <input type="text" class="form-control" id="edit_surname" name="edit_surname" placeholder="Enter Surname">
                        </div>
                        <div class="form-group">
                            <label for="edit_names">Other Names</label>
                            <input type="text" class="form-control" id="edit_other_names" name="edit_other_names" placeholder="Other Names">
                        </div>
                        <div class="form-group">
                            <label for="edit_email">Email</label>
                            <input type="email" class="form-control" id=edit_"email" name=edit_"email" placeholder="Email">
                        </div>
                        <div class="form-group">
                            <label for="edit_phone">Phone</label>
                            <input type="text" class="form-control" id="edit_phone" name="edit_phone" placeholder="phone">
                        </div>
                        <div class="form-group">
                            <label for="edit_address">Address</label>
                            <input type="text" class="form-control" id="edit_address" name="edit_address" placeholder="Address">
                        </div>
                        <div class="form-group">
                            <label for="edit_lga">Lga</label>
                            <input type="text" class="form-control" id="edit_lga" name="edit_lga" placeholder="Lga">
                        </div>
                        <div class="form-group">
                            <label for="edit_state">State</label>
                            <input type="text" class="form-control" id="edit_state" name="edit_state" placeholder="State">
                        </div>
                        <div class="form-group">
                            <label for="edit_password">Password</label>
                            <input type="password" class="form-control" id="edit_password" name="edit_password" placeholder="Password">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit"  class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>



    <!-- closing div for the closing of the admin -->

</div>

<script src="<?= base_url()?>assets/js/jquery.min.js"></script>
<script type="text/javascript">
    var manageUserTable;
    $(document).ready(function(){
        manageUserTable = $("#manageUserTable").DataTable({
            'ajax': 'fetchUsers/'
        });
    });

    function addUserModel()
    {
        $("#FormAll")[0].reset();
        $(".text-danger").remove();
        $(".form-group").removeClass('has-error').removeClass('has-success');

        $("#FormAll").unbind('submit').bind('submit', function(){
            var form = $(this);

            $(".text-danger").remove();

            $.ajax({
                url: form.attr('action'),
                type: form.attr('method'),
                data: form.serialize(), // converting the data into array and sending it to UserController
                dataType: 'json',
                success: function(response){
                    if(response === true){
                        $(".message").html('<div class="alert alert-success alert-dismissible" role="alert">'+
                            '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+
                        '<strong><span class="glyphicon glyphicon-ok-sign"></span></strong>'+ response.message +
                        '</div>');
                        $("#addUsers").modal('hide');

                        manageUserTable.ajax.reload(null, false);
                    }else{
                        if(response.message instanceof Object){
                            $.each(response.message, function(index, value){

                                var id = $("#"+index);

                                id.closest('.form-group').removeClass('has-error').removeClass('has-success').addClass(value.length > 0 ? 'has-error' : 'has-success').after(value);
                            });
                        }else{
                            $(".message").html('<div class="alert alert-warning alert-dismissible" role="alert">'+
                                '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+
                                '<strong><span class="glyphicon glyphicon-exclamation-sign"></span></strong>'+ response.message +
                                '</div>');
                        }
                    }
                }
            });
            return false;
        });
    }

    $("#search").keyup(function(){
        var search_item = $(this).val();

        $.post('http://localhost:8080/OjajaQuiz/Users/search', {search_item: search_item}, function(data){

           $("#mySearch").html(data);

        } );

    });
    $(document).on("ready", main);

    function main()
    {
        $("form").submit(function(e){
            e.preventDefault();
            $.ajax({
                url: "http://localhost:8080/OjajaQuiz/Users/ManageUsers",
                type: "post",
                data: $("#formAll").serialize(),
                success: function(response){
                alert(response);
            }
        });
        });
    }
    function editUser(id = null)
    {
        if(id){

            $("#editForm")[0].reset();
            $('.form-group').removeClass('has-error').removeClass('has-success');
            $('.text-danger').remove();

            $.ajax({
                url: 'getSelectedUser/'+ id,
                post: 'post',
                dataType: 'json',
                success: function(response){
                    $("#edit_surname").val(response.surname);
                    $("#edit_other_names").val(response.other_names);
                    $("#edit_email").val(response.email);
                    $("#edit_phone").val(response.phone);
                    $("#edit_address").val(response.address);
                    $("#edit_state").val(response.state);
                    $("#edit_lga").val(response.lga);

                    $("#editForm").unbind('submit').bind('submit', function(){
                        var form = $(this);

                        $.ajax({
                            url: form.attr('action') + '/'+id,
                            type: 'post',
                            data: form.serialize(),
                            dataType: 'json',
                            success: function(response){

                                if(response === true){
                                    $(".message").html('<div class="alert alert-success alert-dismissible" role="alert">'+
                                        '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+
                                        '<strong><span class="glyphicon glyphicon-ok-sign"></span></strong>'+ response.message +
                                        '</div>');
                                    $("#editUserModel").modal('hide');

                                    manageUserTable.ajax.reload(null, false);
                                }else{
                                $('.text-danger').remove();
                                    if(response.message instanceof Object){
                                        $.each(response.message, function(index, value){

                                            var id = $("#"+index);

                                            id.closest('.form-group').removeClass('has-error').removeClass('has-success').addClass(value.length > 0 ? 'has-error' : 'has-success')
                                                .after(value);
                                        });
                                    }else{
                                        $(".message").html('<div class="alert alert-warning alert-dismissible" role="alert">'+
                                            '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+
                                            '<strong><span class="glyphicon glyphicon-exclamation-sign"></span></strong>'+ response.message +
                                            '</div>');
                                    }
                                }
                            }
                        });

                        return false;
                    });

                }
            });
        }else{
            alert('error');
        }
    }

</script>

<!-- /.content-wrapper -->