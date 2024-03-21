@include('./layouts/web.header')
<h3>{{ 'Total Registrations' }}</h3>

<div class="row">
    <!-- Full Editor -->
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="demo-inline-spacing">
                    <button type="button" class="btn btn-label-success" id="exportExcelBtn"><i
                            class="ti ti-file-export me-sm-1"></i>Export
                        Excel</button>
                </div>
            </div>

            <div class="card-body">

                <div class="table-responsive">

                    <table id="datatable" class="display table table-responsive">
                        <thead>
                            <tr>
                                <th>id</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Profile Created On</th>
                            </tr>
                        </thead>
                        <tbody id="datatable-body">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
    <!-- /Full Editor -->
</div>
<!-- /Modals -->
<div class="modal fade" id="modalView" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalViewTitle">Feedback
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col mb-3">
                        <p>Cras mattis consectetur purus sit amet fermentum. Cras justo odio, dapibus ac facilisis
                            in, egestas
                            eget quam. Morbi leo risus, porta ac consectetur ac, vestibulum at eros.</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success">Approve</button>

            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modalDelete" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalDeleteTitle">Feedback Deletion
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col mb-3">
                        <h3>Are sure, you want to delete the feedback</h3>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success">Yes</button>

            </div>
        </div>
    </div>
</div>


<script>
    // $(document).ready(function() {
        // Initialize DataTable
        
        $.ajax({
            url: `http://localhost:8000/api/subscriberlogins/`,
            method: "GET",
            dataType: "json",
            success: function(response) {
                console.log(response);
                var tableBody = $('#datatable-body'); // Select the tbody element
                tableBody.empty(); // Clear previous data
                if (response.data.length > 0) {
                    $.each(response.data, function(index, row) {
                        var newRow = $('<tr>');
                        newRow.append('<td>' + row.id + '</td>');
                        newRow.append('<td>' + row.FirstName + '</td>');
                        newRow.append('<td>' + row.Email + '</td>');
                        newRow.append('<td>' + row.created_at + '</td>');
                        tableBody.append(newRow);
                    });
                } else {
                    // If no data found, display a message in a new row
                    var newRow = $('<tr>').html('<td colspan="4">No data available.</td>');
                    tableBody.append(newRow);
                }
                $('#datatable').DataTable();
            },
            error: function(xhr, status, error) {
                console.error("Error fetching data:", error);
                alert("An error has occurred");
            }
        });


        document.getElementById('exportExcelBtn').addEventListener('click', function() {
            fetch('/export-registrations', {
                    method: 'GET'
                })
                .then(response => {
                    if (response.ok) {
                        return response.blob();
                    }
                    throw new Error('Network response was not ok.');
                })
                .then(blob => {
                    const url = window.URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = 'registrations.xlsx';
                    document.body.appendChild(a);
                    a.click();
                    window.URL.revokeObjectURL(url);
                })
                .catch(error => {
                    console.error('Error exporting data:', error);
                });
        });
    // });
</script>
@include('./layouts/web.footer')
