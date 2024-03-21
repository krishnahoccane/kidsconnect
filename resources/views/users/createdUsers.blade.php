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
                                <th>S.No</th>
                                <th>Role</th>
                                <th>Sub Name</th>
                                <th>Sub Mail</th>
                                {{-- <th>Status</th> --}}
                                <th>Created On</th>
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

<script>
    // $(document).ready(function() {
        
        function callRoles(roleName) {
            if (roleName === 'Father') {
                return '<span class="badge bg-label-danger">Father</span>';
            } else if (roleName === 'Mother') {
                return '<span class="badge bg-label-success">Mother</span>';
            } else {
                return '<span class="badge bg-label-info">Others</span>';
            }
        }
        
        async function fetchRoleName(roleID) {
            try {
                const response = await $.ajax({
                    url: `http://localhost:8000/api/roles/${roleID}`,
                    method: "GET",
                    dataType: "json"
                });
                return response.data.role; // Adjust according to your actual response structure
            } catch (error) {
                console.error("Error fetching role name:", error);
                return "Unknown"; // Fallback role name
            }
            callRoles();
        }
    
        async function populateTable() {
            try {
                const response = await $.ajax({
                    url: "http://localhost:8000/api/subscriberloginsCreateAccount",
                    method: "GET",
                    dataType: "json"
                });
    
                if (response.data && response.data.length > 0) {
                    let serialNo = 1; // Initialize serial number
                    for (let item of response.data) {
                        const roleName = await fetchRoleName(item.RoleId);
                        const fullName = `${item.FirstName} ${item.LastName}`;
                        const createdAtDate = new Date(item.created_at);
                        const formattedDateOfCreation = `${createdAtDate.getFullYear()}-${(createdAtDate.getMonth() + 1).toString().padStart(2, '0')}-${createdAtDate.getDate().toString().padStart(2, '0')}`;
                        
                        // Construct the table row
                        const row = `
                            <tr>
                                <td>${serialNo++}</td> <!-- Serial number -->
                                <td>${roleName}</td>
                                <td>${fullName}</td>
                                <td>${item.Email}</td>
                                <td>${formattedDateOfCreation}</td>
                                <td>
                                    <a href="/userProfile/${item.id}">
                                        <button type="button" class="btn btn-primary">
                                            <span class="ti-xs ti ti-eye me-1"></span>
                                        </button>
                                    </a>
                                </td>
                            </tr>`;
                        $("#datatable-subscriberLogin").append(row);
                       
                    }
                    // $('#datatable').DataTable();
                } else {
                    console.error('No data found.');
                }
            } catch (error) {
                console.error("Error fetching subscriber data:", error);
            }
        }
    
        populateTable(); // Call the function to populate the table
    // });
    </script>

@include('./layouts/web.footer')

    
    
   
    