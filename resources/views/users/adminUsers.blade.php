@include('./layouts/web.header')


<h3>{{ 'adminuser' }}</h3>


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
                                <td>Row 2 Data 2</td>
                                <td>Tulika</td>
                                <td>Row 2 Data 2</td>
                                <td>Tulika</td>
                                <td>Row 2 Data 2</td>
                                <td>Actions</td>
                            </tr>
                            <tr>
                                <td>Vishal</td>
                                <td>Row12</td>
                                <td>Soumya</td>
                                <td>Row3</td>
                                <td>Anita</td>
                                <td>Yo</td>
                                <td>Actions2</td>
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
