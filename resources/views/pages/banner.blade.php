@include('./layouts/web.header')

<h3>{{ 'Banner Page' }}</h3>

<!-- Multi  -->
<div class="col-12">
    <div class="card">
        <div class="card-header">

            <div class="d-flex justify-content-between align-items-center">
                <h5 class="m-0">Upload Baner images</h5>
                <ul class="alert alert-warning">
                    @error('image')
                        {{ $message }}
                    @enderror
                </ul>
            </div>
        </div>


        <div class="card-body">
            <form id="imageUploadForm" action="{{ url('api/allBanners') }}" method="POST" enctype="multipart/form-data">
                <div class="mb-3" class="dropzone needsclick">
                    @csrf
                    <label for="imageUpload" class="form-label">Select Images:</label>
                    <input type="file" class="form-control" id="imageUpload" name="images[]" multiple>
                </div>
                <button type="submit" class="btn btn-primary">Upload</button>
            </form>
            <div id="preview"></div>
        </div>
    </div>
</div>
<!-- Multi  -->











<div class="row mt-3">

    <!-- Full Editor -->
    <div class="col-12">
        <div class="card">
            <h5 class="card-header">List Of Banners</h5>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="datatable" class="display table table-responsive">
                        <thead>
                            <tr>
                                <th>id</th>
                                <th class="w-25">Image</th>
                                <th>Uploaded On</th>
                                <th>Visisbility On App</th>
                                <th class="sorting_disabled">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="datatable-body">
                            <!-- Data will be populated here -->
                        </tbody>
                    </table>
                    <script>
                        var baseUrl = "{{ config('app.url') }}/api/allBanners";
                        // var url = "http://localhost:8000/api/allBanners";

                        $.ajax({
                            url: baseUrl,
                            method: "GET",
                            dataType: "json",
                            success: function(response) {
                                // Check if the 'data' key exists in the response
                                if (response.data) {
                                    // Loop through the data and create table rows
                                    $.each(response.data, function(index, item) {
                                        const createdAtDate = new Date(item.created_at);
                                        const formattedDate = `${createdAtDate.getFullYear()}-${(
                                            createdAtDate.getMonth() + 1
                                        )
                                            .toString()
                                            .padStart(2, "0")}-${createdAtDate
                                            .getDate()
                                            .toString()
                                            .padStart(2, "0")}`;
                                        const bannerStatus = item.status;
                                        if (bannerStatus != 0) {
                                            var status = "Active";
                                            var buttonName = "Inactive";
                                        } else {
                                            var status = "In Active";
                                            var buttonName = "Active";
                                        }
                                        // Combine the date and time components
                                        const formattedDateTime = `${formattedDate}`;
                                        const row = `
                                    <tr>
                                        <td>${index + 1}</td>
                                        <td><img src="{{ config('app.url') }}/${item.image}" class="img-fluid w-50 rounded" data-bs-toggle="modal" data-bs-target="#modals-transparent" id="bannerImage"/></td>
                                        <td>${formattedDateTime}</td>
                                        <td>${status}</td>
                                        <td>
                                            <button type="button" class="btn btn-primary" id="confirm-text">
                                                ${buttonName}
                                            </button>
                                            <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                                data-bs-target="#modalDelete">
                                                Delete
                                            </button>
                                        </td>   
                                    </tr>
                                    `;
                                        // Append the row to the table body
                                        $("#datatable-body").append(row);
                                    });
                                } else {
                                    console.error(
                                        'Error: Unable to find "data" key in the API response'
                                    );
                                }
                            },
                            error: function(xhr, status, error) {
                                console.error("Error fetching data from the API:", error);
                            },
                        });
                        B = document.querySelector("#confirm-text");
                        B.onclick = function() {
                            Swal.fire({
                                title: "Are you sure?",
                                text: "You won't be able to revert this!",
                                icon: "warning",
                                showCancelButton: !0,
                                confirmButtonText: "Yes, delete it!",
                                customClass: {
                                    confirmButton: "btn btn-primary me-3 waves-effect waves-light",
                                    cancelButton: "btn btn-label-secondary waves-effect waves-light",
                                },
                                buttonsStyling: !1,
                            }).then(function(t) {
                                t.value &&
                                    Swal.fire({
                                        icon: "success",
                                        title: "Deleted!",
                                        text: "Your file has been deleted.",
                                        customClass: {
                                            confirmButton: "btn btn-success waves-effect waves-light",
                                        },
                                    });
                            });
                        }
                        $(document).on("click", "#bannerImage", function() {
                            var imageSrc = $(this).attr('src');

                            $('#modals-transparent').find('.carousel-item.active img').attr('src', imageSrc);
                        });
                    </script>
                    <!-- slider modal -->
                    <div class="modal modal-transparent fade" id="modals-transparent" tabindex="-1">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content text-center">
                                <div class="modal-header border-0">
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                    </button>
                                </div>
                                <div class="carousel-inner">
                                    <div class="carousel-item active">
                                        <div class="onboarding-media">
                                            <div class="mx-2">
                                                <img src="" alt="girl-with-laptop-light"
                                                    class="img-fluid w-100">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!--/ slider modal -->
                </div>
            </div>
        </div>

    </div>
    <!-- /Full Editor -->

</div>

@include('./layouts/web.footer')
