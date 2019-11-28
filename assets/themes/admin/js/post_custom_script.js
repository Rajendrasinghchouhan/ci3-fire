
var table;
// var csrf_token  = $(input[name="csrf_token"]);
//var csrf_token  = "";
//alert(csrf_token);
var csrfName = $('#csrf_hash').val();
var csrf_token = $('#csrf_token').val();
// alert(csrf_token);
$(document).ready(function() {
 
    //datatables
    table = $('#table').DataTable({ 
        
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.
            
        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": "post/post_list",
            "type": "POST",
            "data": {csrf_token:csrfName},
            },
 
        //Set column definition initialisation properties.
        "columnDefs": [
        { 
            "targets": [ 0,2,3,5,6 ], //first column / numbering column
            "orderable": false, //set not orderable
        },
        ],
 
    });
 
});