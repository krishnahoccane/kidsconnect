@include('./layouts/web.header')



<h3>{{ 'FAQs' }}</h3>


<div class="row">
    <!-- Full Editor -->
    <div class="col-12">
        <div class="card">
            <h5 class="card-header">Enter your content here</h5>
            <div class="card-body">
                <form action="{{ url('/allPages/3/edit') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="_method" value="PUT">
                    <textarea id="editor3" name="Pagecontent" class="form-control text"></textarea>
                    <button type="submit" class="btn btn-primary mt-5">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
// Fetch data for 'FAQ' and initialize CKEditor for editor #editor3
document.addEventListener('DOMContentLoaded', function() {

$.ajax({
        url: "http://localhost:8000/api/allPages/3",
        method: "GET",
        dataType: "json",
        success: function (response) {
            var pageContent = response.data.Pagecontent;
            ClassicEditor
                .create(document.querySelector('#editor3'))
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
