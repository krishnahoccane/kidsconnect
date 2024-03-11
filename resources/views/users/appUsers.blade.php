@include('./layouts/web.header')


<h3>{{ 'App User Management' }}</h3>


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
                                <th>Type</th>
                                <th>Profile Status</th>
                                <th>Profile Created On</th>
                                <th>Approved Date</th>
                                <th>Approved By</th>
                                <th class="sorting_disabled">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="datatable-subscriberLogin">
                           

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- /Full Editor -->
</div>



@include('./layouts/web.footer')
<script>
    // subscribers Data
$.ajax({
    url: "http://localhost:8000/api/subscriberlogins",
    method: "GET",
    dataType: "json",
    success: function (response) {
        // Check if the 'data' key exists in the response
        
        if (response.data) {
            // Loop through the data and create table rows

            $.each(response.data, function (index, item) {
                const createdAtDate = new Date(item.created_at);
                const approvedAtDate = new Date(item.ApprovedOn);
                // console.log(createdAtDate);
                // Format the date components
                const formattedDateOfCreation = `${createdAtDate.getFullYear()}-${(
                    createdAtDate.getMonth() + 1
                )
                    .toString()
                    .padStart(2, "0")}-${createdAtDate
                    .getDate()
                    .toString()
                    .padStart(2, "0")}`;
                const formattedDateOfApprove = `${approvedAtDate.getFullYear()}-${(
                    approvedAtDate.getMonth() + 1
                )
                    .toString()
                    .padStart(2, "0")}-${approvedAtDate
                    .getDate()
                    .toString()
                    .padStart(2, "0")}`;

                const row = `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${item.RoleId}</td>
                        <td>${item.Email}</td>
                        <td>${formattedDateOfCreation}</td>
                        <td>${formattedDateOfApprove}</td>
                        <td>${item.ApprovedBy}</td>
                        <td>
                            <a href="/userProfile/${item.id}"><button type="button" class="btn btn-primary">
                                    <span class="ti-xs ti ti-eye me-1"></span>View Profile
                                </button></a>

                        </td>
                    </tr> `;
                // Append the row to the table body
                $("#datatable-subscriberLogin").append(row);
                
            });
        } else {
            console.error(
                'Error: Unable to find "data" key in the API response'
            );
        }
    },
    error: function (xhr, status, error) {
        console.error("Error fetching data from the API:", error);
    },
});
</script>