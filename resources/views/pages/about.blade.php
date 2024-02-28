@include('./layouts/web.header')



<h3>{{ 'About Page' }}</h3>

<div class="row">
    
    <!-- Full Editor -->
    <div class="col-12">
        <div class="card">
            <h5 class="card-header">Enter your content here</h5>
            <div class="card-body">
                <div id="full-editor">
                    {{-- <h6>Quill Rich Text Editor</h6>
                    <p> Cupcake ipsum dolor sit amet. Halvah cheesecake chocolate bar gummi bears cupcake. Pie macaroon
                        bear claw. Soufflé I love candy canes I love cotton candy I love. </p> --}}
                </div>
            </div>
        </div>
    </div>
    <!-- /Full Editor -->
</div>


@include('./layouts/web.footer')
