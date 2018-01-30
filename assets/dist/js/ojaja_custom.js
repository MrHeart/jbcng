/**
 * Created by Mr Heart on 10/7/2016.
 */
var base_url = "http://localhost:8080/ojajaquiz/index.php/";
function department_edit(id)
{
    save_method = 'update';
    $('#edit_form')[0].reset(); // reset form on modals

//Ajax Load data from ajax
    $.ajax({
        url : base_url + "/Departments/Edit/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {

            $.each(data,function(i,term){
                $('#name').val(term.name);
            });
            //$('#first_name').val(data.first_name);

            $('#edit_form').modal('show'); // show bootstrap modal when complete loaded

        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            console.log(textStatus + "and "+ errorThrown);
            alert('Error get data from ajax');
        }
    });
}

function author_update(){
    // ajax adding data to database
    $.ajax({
        url : base_url + "/Authors/Save",
        type: "POST",
        data: $('#author_edit_form').serialize(),
        dataType: "JSON",
        success: function(data){
            //display success message
            $("#edit_msg").html("Author details updated successfully.")
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error adding / update data');
        }
    });
}

$("#author_edit_btn").click(function(e){
    e.preventDefault();
    author_update();
});

function author_delete(id)
{
    if(confirm('Are you sure you want to delete this author?'))
    {
// ajax delete data to database
        $.ajax({
            url : base_url +"/Authors/Delete/" + id,
            type: "POST",
            dataType: "JSON",
            success: function(data)
            {
                //handle success
                alert('Author successfully deleted');
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error adding / update data');
            }
        });

    }
}
