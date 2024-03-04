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
                        <tbody>
                            <tr>
                                <td>Tulika</td>
                                <td>Mother</td>
                                <td>Deny</td>
                                <td>24-07-2024</td>
                                <td>25-07-2024</td>
                                <td>ADM-0285</td>
                                <td>
                                    <a href="{{ url('userProfile') }}"><button type="button" class="btn btn-primary">
                                        <span class="ti-xs ti ti-eye me-1"></span>View Profile
                                      </button></a>
                                    
                                </td>
                            </tr>
                            <tr>
                                <td>Anita</td>
                                <td>Mother</td>
                                <td>Deny</td>
                                <td>28-12-2024</td>
                                <td>30-12-2024</td>
                                <td>ADM-02875</td>
                                <td>
                                    <a href="{{ url('userProfile') }}"><button type="button" class="btn btn-primary">
                                        <span class="ti-xs ti ti-eye me-1"></span>View Profile
                                      </button></a>
                                    
                                </td>
                            </tr>
                            <tr>
                                <td>Vishal</td>
                                <td>Father</td>
                                <td>Approved</td>
                                <td>24-01-2025</td>
                                <td>30-01-2025</td>
                                <td>ADM-12285</td>
                                <td>
                                    <a href="{{ url('userProfile') }}"><button type="button" class="btn btn-primary">
                                        <span class="ti-xs ti ti-eye me-1"></span>View Profile
                                      </button></a>
                                    
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- /Full Editor -->
</div>



@include('./layouts/web.footer')
