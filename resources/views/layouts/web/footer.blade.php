<script src="{{ asset('ui/assets/vendor/libs/bootstrap5.3.0/bootstrap.bundle.min.js
') }}"></script>
<script src="{{ asset('ui/assets/vendor/libs/bootstrap5.3.0/dataTables.js
') }}"></script>
<script src="{{ asset('ui/assets/vendor/libs/bootstrap5.3.0/dataTables.bootstrap5.js
') }}"></script>

</div>

<footer class="content-footer footer bg-footer-theme">
    <div class="container-xxl">
        <div class="footer-container d-flex align-items-center justify-content-between py-2 flex-md-row flex-column">
            <div>
                ©
                <script>
                    document.write(new Date().getFullYear())
                </script>, made with ❤️ by <a href="#" target="_blank"
                    class="footer-link text-primary fw-medium">Pixinvent</a>
            </div>
            <div class="d-none d-lg-inline-block">

                <a href="#" class="footer-link me-4" target="_blank">License</a>
                <a href="#" target="_blank" class="footer-link me-4">More
                    Themes</a>

                <a href="#" target="_blank" class="footer-link me-4">Documentation</a>


                <a href="#" target="_blank" class="footer-link d-none d-sm-inline-block">Support</a>

            </div>
        </div>
    </div>
</footer>
<!-- / Footer -->


<div class="content-backdrop fade"></div>
</div>
<!-- Content wrapper -->
</div>
<!-- / Layout page -->
</div>



<!-- Overlay -->
<div class="layout-overlay layout-menu-toggle"></div>


<!-- Drag Target Area To SlideIn Menu On Small Screens -->
<div class="drag-target"></div>

</div>
<!-- / Layout wrapper -->
<!-- Core JS -->


<!-- build:js assets/vendor/js/core.js -->

<script src="{{ asset('ui/assets/vendor/libs/jquery/jquery.js') }}"></script>
<script src="{{ asset('ui/assets/vendor/libs/popper/popper.js') }}"></script>
<script src="{{ asset('ui/assets/vendor/js/bootstrap.js') }}"></script>
<script src="{{ asset('ui/assets/vendor/libs/node-waves/node-waves.js') }}"></script>
<script src="{{ asset('ui/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
<script src="{{ asset('ui/assets/vendor/libs/hammer/hammer.js') }}"></script>

{{-- <script src="{{ asset('ui/assets/vendor/libs/i18n/i18n.js') }}"></script> --}}
<script src="{{ asset('ui/assets/vendor/libs/typeahead-js/typeahead.js') }}"></script>
<script src="{{ asset('ui/assets/vendor/js/menu.js') }}"></script>

<!-- endbuild -->

<!-- Vendors JS -->
<script src="{{ asset('ui/assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>
<script src="{{ asset('ui/assets/vendor/libs/swiper/swiper.js') }}"></script>
<script src="{{ asset('ui/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>

<!-- Flat Picker -->
<script src="{{ asset('ui/assets/vendor/libs/moment/moment.js') }}"></script>
<script src="{{ asset('ui/assets/vendor/libs/flatpickr/flatpickr.js') }}"></script>

<!-- Main JS -->
<script src="{{ asset('ui/assets/js/main.js') }}"></script>


<!-- Page JS -->
<script src="{{ asset('ui/assets/js/dashboards-analytics.js') }}"></script>
<script src="{{ asset('ui/assets/js/app-logistics-dashboard.js') }}"></script>


<script src="{{ asset('ui/assets/vendor/libs/quill/katex.js') }}"></script>
<script src="{{ asset('ui/assets/vendor/libs/quill/quill.js') }}"></script>

<script src="{{ asset('ui/assets/js/forms-editors.js') }}"></script>
{{-- <script src="{{ asset('ui/assets/js/tables-datatables-advanced.js') }}"></script> --}}



</body>



<script>
    let table1 = new DataTable('#datatable', {
        select: true,
        "columnDefs": [{
                "orderable": false,
                "targets": -1
            } // Disables sorting for the last column (in this case, the "Actions" column)
        ]
    });

    // Define the API endpoint URL
    var url = "http://localhost:8000/api/subscriber";

    // Make an AJAX request using jQuery
    $.ajax({
        url: url,
        method: "GET",
        dataType: "json",
        success: function(data) {
            console.log(data);
        },
        error: function(xhr, status, error) {
            console.error('Error fetching data from the API:', error);
        }
    });
</script>

<!-- Mirrored from demos.pixinvent.com/vuexy-html-admin-template/html/vertical-menu-template/ by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 13 Feb 2024 15:06:10 GMT -->

</html>
