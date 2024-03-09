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
    
</script>