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
                    <textarea id="director1" name="Pagecontent" class="form-control text"></textarea>
                    <button type="submit" class="btn btn-primary mt-5">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>

@include('./layouts/web.footer')

{{-- <script>
    $(document).ready(function() {
        fetchData();
    });

    function fetchData() {
        var apiUrl = "";

        
    }
</script> --}}
