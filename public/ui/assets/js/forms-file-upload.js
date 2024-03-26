$(document).ready(function() {
    // Preview images before upload
    $('#imageUpload').change(function() {
        $('#preview').html('');
        var files = $(this)[0].files;
        a = document.querySelector("#dropzone-basic");
        if (files.length > 0) {
            for (var i = 0; i < files.length; i++) {
                var file = files[i];
                var reader = new FileReader();

                reader.onload = (function(file) {
                    return function(e) {
                        $('#preview').append(
                            '<div class="dz-preview dz-file-preview">' +
                            '<div class="dz-details">' +
                            '<div class="dz-thumbnail">' +
                            '<img data-dz-thumbnail class="img-thumbnail m-2 align-items-center" src="' +
                            e.target.result + '">' +
                            '<div class="dz-success-mark"></div>' +
                            '<div class="dz-error-mark"></div>' +
                            '<div class="dz-error-message"><span data-dz-errormessage></span></div>' +
                            '<div class="progress-bar progress-bar-primary" role="progressbar" aria-valuemin="0" aria-valuemax="100" data-dz-uploadprogress></div>' +
                            '</div>' +
                            '</div>' +
                            '<div class="dz-filename" data-dz-name>' + file.name +
                            '</div>' +
                            '<div class="dz-size" data-dz-size>' + file.size +
                            ' bytes</div>' +
                            '</div>' +
                            '</div>');
                    };
                })(file);

                reader.readAsDataURL(file);
            }
        }
    });
});