@include('./layouts/web.header')

<h3>{{ 'About Page' }}</h3>
@php
$id = 1;
@endphp
<div class="row">
    <!-- Full Editor -->
    <div class="col-12">
        <div class="card">
            <h5 class="card-header">Enter your content here</h5>
            <div class="card-body">
                <form action="{{ url('/allPages/1/edit') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="_method" value="PUT">
                    <textarea id="editor" name="Pagecontent" class="form-control"></textarea>
                    <button type="submit" class="btn btn-primary mt-5">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {

// Fetch data for 'About Page' and initialize CKEditor for editor #editor
$.ajax({
    url: "http://localhost:8000/api/allPages/1",
    method: "GET",
    dataType: "json",
    success: function (response) {
        var pageContent = response.data.Pagecontent;
        ClassicEditor
            .create(document.querySelector('#editor'))
            .then(editor => {
                editor.setData(pageContent);
            })
            .catch(error => {
                console.error('Error initializing CKEditor:', error);
            });
    },
    error: function (xhr, status, error) {
        console.error("Error fetching data from the API:", error);
    },
});
    });

    </script>
@include('./layouts/web.footer')


{{-- <script>
    $(document).ready(function() {
        fetchData();
    });

    function fetchData() {
        var apiUrl = "";

        
    }
</script> --}}
