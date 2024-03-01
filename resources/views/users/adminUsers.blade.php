@include('./layouts/web.header')


<h3>{{ 'Admin Users' }}</h3>


<div class="row">

    <!-- Full Editor -->
    <div class="col-12">
        <div class="card">
            <h5 class="card-header">Enter your content here</h5>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="datatable" class="display table table-responsive">
                        <thead>
                            <tr>
                                <th>id</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Profile Created On</th>
                                <th class="sorting_disabled">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="datatable-body">
                            <!-- Data will be populated here -->
                        </tbody>
                    </table>
                    <script>
                        var url = "http://localhost:8000/api/adminUsers";

                        $.ajax({
                            url: url,
                            method: "GET",
                            dataType: "json",
                            success: function(response) {
                                // Check if the 'data' key exists in the response
                                if (response.data) {
                                    // Loop through the data and create table rows
                                    $.each(response.data, function(index, item) {
                                        const createdAtDate  = new Date(item.created_at);
                                        // Format the date components
                                        const formattedDate =
                                            `${createdAtDate.getFullYear()}-${(createdAtDate.getMonth() + 1).toString().padStart(2, '0')}-${createdAtDate.getDate().toString().padStart(2, '0')}`;
                                        // const formattedTime =
                                        //     `${createdAtDate.getHours().toString().padStart(2, '0')}:${createdAtDate.getMinutes().toString().padStart(2, '0')}:${createdAtDate.getSeconds().toString().padStart(2, '0')}`;

                                        // Combine the date and time components
                                        const formattedDateTime = `${formattedDate}`;
                                        const row = `
                                    <tr>
                                        <td>${index+1}</td>
                                        <td>${item.username}</td>
                                        <td>${item.email}</td>
                                        <td>${formattedDateTime}</td>
                                        <td>
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                                data-bs-target="#modalView">
                                                View
                                            </button>
                                            <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                                data-bs-target="#modalDelete">
                                                Delete
                                            </button>
                                        </td>   
                                    </tr>
                                    `;
                                        // Append the row to the table body
                                        $('#datatable-body').append(row);
                                    });
                                } else {
                                    console.error('Error: Unable to find "data" key in the API response');
                                }
                            },
                            error: function(xhr, status, error) {
                                console.error('Error fetching data from the API:', error);
                            }
                        });
                    </script>

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


@include('./layouts/web.footer')
