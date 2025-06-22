// Call the dataTables jQuery plugin
$(document).ready(function() {
  $('#dataTable').DataTable(
	  	{
            dom: 'Bfrtip',
                responsive: true,
                pageLength: 25,
                // lengthMenu: [0, 5, 10, 20, 50, 100, 200, 500],

                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ]


	    }
  	);
});
