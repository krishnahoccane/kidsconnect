@include('./layouts/web.header')

<h3>{{ 'Banner Page' }}</h3>
<div class="row">

    <!-- Multi  -->
    <div class="col-12">
      <div class="card">
        <h5 class="card-header">Multiple</h5>
        <div class="card-body">
            <form action="{{ url('banners') }}" enctype="multipart/form-data" class="dropzone needsclick" id="dropzone-multi">
                <div class="dz-message needsclick">
                    Drop files here or click to upload
                    <span class="note needsclick">(This is just a demo dropzone. Selected files are <span class="fw-medium">not</span> actually uploaded.)</span>
                </div>
                <div class="fallback">
                    <input name="file" type="file" />
                </div>
                <!-- Submit button -->
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
            
        </div>
      </div>
    </div>
    <!-- Multi  -->
  </div>
  
  <script src="{{ asset('ui/assets/vendor/libs/dropzone/dropzone.js') }}"></script>
  <script>
    // Send image data to server using AJAX when the form is submitted
    $('#dropzone-multi').on('submit', function (e) {
        e.preventDefault(); // Prevent default form submission

        // Get form data
        var formData = new FormData($(this)[0]);
        console.log(formData)
        // Make AJAX call
        $.ajax({
            url: 'http://localhost:8000/api/banners', // Replace with your API URL
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                // Handle success
                console.log(response);
            },
            error: function (xhr, status, error) {
                // Handle error
                console.error(error);
            }
        });
    });
</script>

@include('./layouts/web.footer')
