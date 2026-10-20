
<!DOCTYPE html>
<html>
<head>
	<title>DataTables AJAX pagination with Custom filter in CodeIgniter</title>

	<!-- Datatable CSS -->
	<link href='//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css' rel='stylesheet' type='text/css'>

	<!-- jQuery Library -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

	<!-- Datatable JS -->
	<script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>

</head>
<body>
<table id='userTable' class='display dataTable'>

<thead>
  <tr>
    <th>Name</th>
    <th>Email</th>
    <th>Gender</th>
    <th>City</th>
  </tr>
</thead>

</table>

<!-- Script -->
<script type="text/javascript">
$(document).ready(function(){
     var userDataTable = $('#userTable').DataTable({
        'processing': true,
        'serverSide': true,
        'serverMethod': 'post',
        //'searching': false, // Remove default Search Control
        'ajax': {
        'url':'<?=base_url()?>index.php/datatable/userList',
        'data': function(data){
                // data.searchCity = $('#sel_city').val();
                // data.searchGender = $('#sel_gender').val();
                // data.searchName = $('#searchName').val();
        }
        },
        'columns': [
           { data: 'name' },
           { data: 'email' },
           { data: 'gender' },
           { data: 'action' },

        ]
     });

    //  $('#sel_city,#sel_gender').change(function(){
    //      userDataTable.draw();
    //  });
    //  $('#searchName').keyup(function(){
    //      userDataTable.draw();
    //  });
});
</script>