@include('./layouts/web.header')
@php
    if ($sub_login['RoleId'] === null) {
        $role = 'Father';
    } else {
        $role = 'Mother';
    }

    if ($sub_login['IsApproved'] == 0) {
        $pStatus = 'Approve';
        $pStatus_btn = 'primary';
        $btn_status = '';
    } elseif ($sub_login['IsApproved'] == 1) {
        $pStatus = 'Approved';
        $pStatus_btn = 'success';
        $btn_status = 'disabled';
    } else {
        $pStatus = 'Pending';
        $btn_status = '';
    }

    if ($sub_login['LoginType'] == 1) {
        $loginType = 'Google Login';
    } else {
        $loginType = 'Manual';
    }

    $userId = $sub_login['id'];

@endphp
<!-- Header -->
<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            {{-- <div class="user-profile-header-banner">
                <img src="{{asset('ui/assets/img/pages/profile-banner.png')}}" alt="Banner image" class="rounded-top">
            </div> --}}
            <div class="user-profile-header d-flex flex-column flex-sm-row text-sm-start text-center mb-4">
                <div class="flex-shrink-0 mt-n2 mx-sm-0 mx-auto">
                    <img src="{{ asset('ui/assets/img/avatars/14.png') }}" alt="user image"
                        class="d-block h-auto ms-0 ms-sm-4 rounded user-profile-img">
                </div>
                <div class="flex-grow-1 mt-3 mt-sm-5">
                    <div
                        class="d-flex align-items-md-end align-items-sm-start align-items-center justify-content-md-between justify-content-start mx-4 flex-md-row flex-column gap-4">
                        <div class="user-profile-info">
                            <h4 id="sub_name">
                                {{ $sub_login['FirstName'] . ' ' . $sub_login['LastName'] }}
                            </h4>
                            <ul
                                class="list-inline mb-0 d-flex align-items-center flex-wrap justify-content-sm-start justify-content-center gap-2">
                                <li class="list-inline-item d-flex gap-1">
                                    <i class='ti ti-user-question'></i>

                                    {{ $role }}

                                </li>
                                <li class="list-inline-item d-flex gap-1">
                                    <i class='ti ti-map-pin'></i> {{ $sub_login['Address'] }}
                                </li>
                                <li class="list-inline-item d-flex gap-1">
                                    <i class='ti ti-calendar'></i>
                                    {{ date('d-m-Y', strtotime($sub_login['created_at'])) }}
                                </li>
                            </ul>
                        </div>
                        <div>
                            <button class="btn btn-{{ $pStatus_btn }}"
                                data-approve-url="/userProfile/{{ $sub_login['id'] }}/approve" id="approveBtn"
                                {{ $btn_status }}>
                                {{ $pStatus }}
                            </button>
                            <button class="btn btn-danger" data-deny-url="/userProfile/{{ $sub_login['id'] }}/deny"
                                id="denyBtn">
                                Deny
                            </button>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-12">
        <h6 class="text-muted">Info</h6>
        <div class="nav-align-top mb-4">
            <ul class="nav nav-pills mb-3" role="tablist">
                <li class="nav-item">
                    <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab"
                        data-bs-target="#navs-pills-top-home" aria-controls="navs-pills-top-home"
                        aria-selected="true">Profile</button>
                </li>
                <li class="nav-item">
                    <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                        data-bs-target="#navs-pills-top-profile" aria-controls="navs-pills-top-profile"
                        aria-selected="false">Circles</button>
                </li>
                {{-- <li class="nav-item">
                    <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                        data-bs-target="#navs-pills-top-messages" aria-controls="navs-pills-top-messages"
                        aria-selected="false">Messages</button>
                </li> --}}
            </ul>
            <div class="tab-content">
                <div class="tab-pane fade show active" id="navs-pills-top-home" role="tabpanel">
                    <div class="row">
                        <div class="col-xl-4 col-lg-5 col-md-5">
                            <!-- About User -->
                            <div class="card mb-4">
                                <div class="card-body">
                                    <small class="card-text text-uppercase">About</small>
                                    <ul class="list-unstyled mb-4 mt-3">
                                        <li class="d-flex align-items-center mb-3"><i
                                                class="ti ti-user text-heading"></i><span
                                                class="fw-medium mx-2 text-heading">Full Name:</span>
                                            <span>{{ $sub_login['FirstName'] . ' ' . $sub_login['LastName'] }}</span>
                                        </li>
                                        <li class="d-flex align-items-center mb-3"><i
                                                class="ti ti-check text-heading"></i><span
                                                class="fw-medium mx-2 text-heading">Status:</span>
                                            <span>{{ $pStatus }}</span>
                                        </li>

                                        <li class="d-flex align-items-center mb-3"><i
                                                class="ti ti-credit-card text-heading"></i><span
                                                class="fw-medium mx-2 text-heading">SSN:</span>
                                            <span>{{ $sub_login['SSN'] }}</span>
                                        </li>
                                        <li class="d-flex align-items-center mb-3"><i
                                                class="ti ti-login text-heading"></i><span
                                                class="fw-medium mx-2 text-heading">Login type:</span>
                                            <span>{{ $loginType }}</span>
                                        </li>
                                    </ul>
                                    <small class="card-text text-uppercase">Contacts</small>
                                    <ul class="list-unstyled mb-4 mt-3">
                                        <li class="d-flex align-items-center mb-3"><i class="ti ti-phone-call"></i><span
                                                class="fw-medium mx-2 text-heading">Contact:</span>
                                            <span>{{ $sub_login['PhoneNumber'] }}</span>
                                        </li>

                                        <li class="d-flex align-items-center mb-3"><i class="ti ti-mail"></i><span
                                                class="fw-medium mx-2 text-heading">Email:</span>
                                            <span>{{ $sub_login['Email'] }}</span>
                                        </li>
                                    </ul>
                                    <small class="card-text text-uppercase">Teams</small>
                                    <ul class="list-unstyled mb-0 mt-3">
                                        <li class="d-flex align-items-center mb-3"><i
                                                class="ti ti-brand-angular text-danger me-2"></i>
                                            <div class="d-flex flex-wrap"><span
                                                    class="fw-medium me-2 text-heading">Backend
                                                    Developer</span><span>(126 Members)</span></div>
                                        </li>
                                        <li class="d-flex align-items-center"><i
                                                class="ti ti-brand-react-native text-info me-2"></i>
                                            <div class="d-flex flex-wrap"><span
                                                    class="fw-medium me-2 text-heading">React
                                                    Developer</span><span>(98 Members)</span></div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <!--/ About User -->
                            <!-- Profile Overview -->
                            {{-- <div class="card mb-4">
                                <div class="card-body">
                                    <p class="card-text text-uppercase">Overview</p>
                                    <ul class="list-unstyled mb-0">
                                        <li class="d-flex align-items-center mb-3"><i class="ti ti-check"></i><span
                                                class="fw-medium mx-2">Task Compiled:</span> <span>13.5k</span></li>
                                        <li class="d-flex align-items-center mb-3"><i
                                                class="ti ti-layout-grid"></i><span class="fw-medium mx-2">Projects
                                                Compiled:</span> <span>146</span></li>
                                        <li class="d-flex align-items-center"><i class="ti ti-users"></i><span
                                                class="fw-medium mx-2">Connections:</span> <span>897</span></li>
                                    </ul>
                                </div>
                            </div> --}}
                            <!--/ Profile Overview -->
                        </div>
                        <div class="col-xl-8 col-lg-7 col-md-7">
                            <div class="row">
                                <!-- Teams -->
                                <div class="col-lg-12">
                                    <div class="card card-action mb-4">
                                        <div class="card-header align-items-center">
                                            <h5 class="card-action-title mb-0">Family</h5>
                                            <div class="card-action-element">
                                                <div class="dropdown">
                                                    <button type="button" class="btn dropdown-toggle hide-arrow p-0"
                                                        data-bs-toggle="dropdown" aria-expanded="false"><i
                                                            class="ti ti-dots-vertical text-muted"></i></button>
                                                    <ul class="dropdown-menu dropdown-menu-end">
                                                        <li><a class="dropdown-item" href="javascript:void(0);">Share
                                                                teams</a></li>
                                                        <li><a class="dropdown-item"
                                                                href="javascript:void(0);">Suggest edits</a></li>
                                                        <li>
                                                            <hr class="dropdown-divider">
                                                        </li>
                                                        <li><a class="dropdown-item" href="javascript:void(0);">Report
                                                                bug</a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--/ Teams -->
                            </div>

                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="navs-pills-top-profile" role="tabpanel">
                    <div class="row g-4">
                        <div class="col-xl-4 col-lg-6 col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <a href="javascript:;" class="d-flex align-items-center">
                                            <div class="avatar me-2">
                                                <img src="{{ asset('ui/assets/img/icons/brands/react-label.png') }}"
                                                    alt="Avatar" class="rounded-circle">
                                            </div>
                                            <div class="me-2 text-body h5 mb-0">
                                                CircleName1
                                            </div>
                                        </a>
                                        <div class="ms-auto">
                                            <ul class="list-inline mb-0 d-flex align-items-center">

                                                <li class="list-inline-item">
                                                    <div class="dropdown">
                                                        <button type="button"
                                                            class="btn dropdown-toggle hide-arrow p-0"
                                                            data-bs-toggle="dropdown" aria-expanded="false"><i
                                                                class="ti ti-dots-vertical text-muted"></i></button>
                                                        <ul class="dropdown-menu dropdown-menu-end">
                                                            <li><a class="dropdown-item"
                                                                    href="javascript:void(0);">Rename Team</a></li>
                                                            <li><a class="dropdown-item"
                                                                    href="javascript:void(0);">View Details</a></li>
                                                            <li><a class="dropdown-item"
                                                                    href="javascript:void(0);">Add to favorites</a>
                                                            </li>
                                                            <li>
                                                                <hr class="dropdown-divider">
                                                            </li>
                                                            <li><a class="dropdown-item text-danger"
                                                                    href="javascript:void(0);">Delete Team</a></li>
                                                        </ul>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <p class="mb-3">We don’t make assumptions about the rest of your technology
                                        stack, so you can develop new features in React.</p>
                                    <div class="d-flex align-items-center pt-1">
                                        <div class="d-flex align-items-center">
                                            <ul class="list-unstyled d-flex align-items-center avatar-group mb-0">
                                                <li data-bs-toggle="tooltip" data-popup="tooltip-custom"
                                                    data-bs-placement="top" title="Vinnie Mostowy"
                                                    class="avatar avatar-sm pull-up">
                                                    <img class="rounded-circle"
                                                        src="{{ asset('ui/assets/img/avatars/5.png') }}"
                                                        alt="Avatar">
                                                </li>
                                                <li data-bs-toggle="tooltip" data-popup="tooltip-custom"
                                                    data-bs-placement="top" title="Allen Rieske"
                                                    class="avatar avatar-sm pull-up">
                                                    <img class="rounded-circle"
                                                        src="{{ asset('ui/assets/img/avatars/12.png') }}"
                                                        alt="Avatar">
                                                </li>
                                                <li data-bs-toggle="tooltip" data-popup="tooltip-custom"
                                                    data-bs-placement="top" title="Julee Rossignol"
                                                    class="avatar avatar-sm pull-up">
                                                    <img class="rounded-circle"
                                                        src="{{ asset('ui/assets/img/avatars/6.png') }}"
                                                        alt="Avatar">
                                                </li>
                                                <li class="avatar avatar-sm">
                                                    <span class="avatar-initial rounded-circle pull-up"
                                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                                        title="8 more">+8</span>
                                                </li>
                                            </ul>
                                        </div>
                                        {{-- <div class="ms-auto">
                                            <a href="javascript:;" class="me-2"><span
                                                    class="badge bg-label-primary">React</span></a>
                                            <a href="javascript:;"><span
                                                    class="badge bg-label-warning">Vue.JS</span></a>
                                        </div> --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-6 col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <a href="javascript:;" class="d-flex align-items-center">
                                            <div class="avatar me-2">
                                                <img src="{{ asset('ui/assets/img/icons/brands/vue-label.png') }}"
                                                    alt="Avatar" class="rounded-circle">
                                            </div>
                                            <div class="me-2 text-body h5 mb-0">
                                                CircleName2
                                            </div>
                                        </a>
                                        <div class="ms-auto">
                                            <ul class="list-inline mb-0 d-flex align-items-center">

                                                <li class="list-inline-item">
                                                    <div class="dropdown">
                                                        <button type="button"
                                                            class="btn dropdown-toggle hide-arrow p-0"
                                                            data-bs-toggle="dropdown" aria-expanded="false"><i
                                                                class="ti ti-dots-vertical text-muted"></i></button>
                                                        <ul class="dropdown-menu dropdown-menu-end">
                                                            <li><a class="dropdown-item"
                                                                    href="javascript:void(0);">Rename Team</a></li>
                                                            <li><a class="dropdown-item"
                                                                    href="javascript:void(0);">View Details</a></li>
                                                            <li><a class="dropdown-item"
                                                                    href="javascript:void(0);">Add to favorites</a>
                                                            </li>
                                                            <li>
                                                                <hr class="dropdown-divider">
                                                            </li>
                                                            <li><a class="dropdown-item text-danger"
                                                                    href="javascript:void(0);">Delete Team</a></li>
                                                        </ul>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <p class="mb-3">The development of Vue and its ecosystem is guided by an
                                        international team, some of whom have chosen to be featured below.</p>
                                    <div class="d-flex align-items-center pt-1">
                                        <div class="d-flex align-items-center">
                                            <ul class="list-unstyled d-flex align-items-center avatar-group mb-0">
                                                <li data-bs-toggle="tooltip" data-popup="tooltip-custom"
                                                    data-bs-placement="top" title="Kaith D'souza"
                                                    class="avatar avatar-sm pull-up">
                                                    <img class="rounded-circle"
                                                        src="{{ asset('ui/assets/img/avatars/5.png') }}"
                                                        alt="Avatar">
                                                </li>
                                                <li data-bs-toggle="tooltip" data-popup="tooltip-custom"
                                                    data-bs-placement="top" title="John Doe"
                                                    class="avatar avatar-sm pull-up">
                                                    <img class="rounded-circle"
                                                        src="{{ asset('ui/assets/img/avatars/1.png') }}"
                                                        alt="Avatar">
                                                </li>
                                                <li data-bs-toggle="tooltip" data-popup="tooltip-custom"
                                                    data-bs-placement="top" title="Alan Walker"
                                                    class="avatar avatar-sm pull-up">
                                                    <img class="rounded-circle"
                                                        src="{{ asset('ui/assets/img/avatars/6.png') }}"
                                                        alt="Avatar">
                                                </li>
                                                <li class="avatar avatar-sm">
                                                    <span class="avatar-initial rounded-circle pull-up"
                                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                                        title="14 more">+14</span>
                                                </li>
                                            </ul>
                                        </div>
                                        {{-- <div class="ms-auto">
                                            <a href="javascript:;"><span
                                                    class="badge bg-label-danger">Developer</span></a>
                                        </div> --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-6 col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <a href="javascript:;" class="d-flex align-items-center">
                                            <div class="avatar me-2">
                                                <img src="{{ asset('ui/assets/img/icons/brands/xd-label.png') }}"
                                                    alt="Avatar" class="rounded-circle">
                                            </div>
                                            <div class="me-2 text-body h5 mb-0">
                                                CircleName3
                                            </div>
                                        </a>
                                        <div class="ms-auto">
                                            <ul class="list-inline mb-0 d-flex align-items-center">

                                                <li class="list-inline-item">
                                                    <div class="dropdown">
                                                        <button type="button"
                                                            class="btn dropdown-toggle hide-arrow p-0"
                                                            data-bs-toggle="dropdown" aria-expanded="false"><i
                                                                class="ti ti-dots-vertical text-muted"></i></button>
                                                        <ul class="dropdown-menu dropdown-menu-end">
                                                            <li><a class="dropdown-item"
                                                                    href="javascript:void(0);">Rename Team</a></li>
                                                            <li><a class="dropdown-item"
                                                                    href="javascript:void(0);">View Details</a></li>
                                                            <li><a class="dropdown-item"
                                                                    href="javascript:void(0);">Add to favorites</a>
                                                            </li>
                                                            <li>
                                                                <hr class="dropdown-divider">
                                                            </li>
                                                            <li><a class="dropdown-item text-danger"
                                                                    href="javascript:void(0);">Delete Team</a></li>
                                                        </ul>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <p class="mb-3">A design or product team is more than just the people on it. A
                                        team includes the people, the roles they play.</p>
                                    <div class="d-flex align-items-center pt-1">
                                        <div class="d-flex align-items-center">
                                            <ul class="list-unstyled d-flex align-items-center avatar-group mb-0">
                                                <li data-bs-toggle="tooltip" data-popup="tooltip-custom"
                                                    data-bs-placement="top" title="Jimmy Ressula"
                                                    class="avatar avatar-sm pull-up">
                                                    <img class="rounded-circle"
                                                        src="{{ asset('ui/assets/img/avatars/4.png') }}"
                                                        alt="Avatar">
                                                </li>
                                                <li data-bs-toggle="tooltip" data-popup="tooltip-custom"
                                                    data-bs-placement="top" title="Kristi Lawker"
                                                    class="avatar avatar-sm pull-up">
                                                    <img class="rounded-circle"
                                                        src="{{ asset('ui/assets/img/avatars/2.png') }}"
                                                        alt="Avatar">
                                                </li>
                                                <li data-bs-toggle="tooltip" data-popup="tooltip-custom"
                                                    data-bs-placement="top" title="Danny Paul"
                                                    class="avatar avatar-sm pull-up">
                                                    <img class="rounded-circle"
                                                        src="{{ asset('ui/assets/img/avatars/7.png') }}"
                                                        alt="Avatar">
                                                </li>
                                                <li class="avatar avatar-sm">
                                                    <span class="avatar-initial rounded-circle pull-up"
                                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                                        title="19 more">+19</span>
                                                </li>
                                            </ul>
                                        </div>
                                        {{-- <div class="ms-auto">
                                            <a href="javascript:;" class="me-2"><span
                                                    class="badge bg-label-warning">Sketch</span></a>
                                            <a href="javascript:;"><span class="badge bg-label-danger">XD</span></a>
                                        </div> --}}
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@include('./layouts/web.footer')
<script>
    document.getElementById('approveBtn').addEventListener('click', function(event) {
        event.preventDefault(); // Prevent the default behavior of the button

        var approveUrl = this.getAttribute('data-approve-url');

        Swal.fire({
            text: "Are you sure you would like to Approve this account?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes",
            customClass: {
                confirmButton: "btn btn-primary me-2 waves-effect waves-light",
                cancelButton: "btn btn-label-secondary waves-effect waves-light",
            },
            buttonsStyling: false,
        }).then(function(result) {
            if (result.value) {
                $.ajax({
                    url: approveUrl,
                    method: 'GET',
                    success: function(response) {
                        Swal.fire({
                            icon: "success",
                            title: "Approved",
                            text: response.message,
                            customClass: {
                                confirmButton: "btn btn-success waves-effect waves-light",
                            },
                        }).then(() => {
                            window.location.reload();
                        });
                    },
                    error: function(xhr, status, error) {
                        // If there's an error, show an error message
                        Swal.fire({
                            title: "Error",
                            text: "An error occurred while approving the account.",
                            icon: "error",
                            customClass: {
                                confirmButton: "btn btn-success waves-effect waves-light",
                            },
                        });
                    }
                });

            } else if (result.dismiss === Swal.DismissReason.cancel) {
                // If user cancels, show cancelled message
                Swal.fire({
                    title: "Cancelled",
                    text: "Approve Cancelled!!",
                    icon: "error",
                    customClass: {
                        confirmButton: "btn btn-success waves-effect waves-light",
                    },
                });
            }
        });
    });


    document.getElementById('denyBtn').addEventListener('click', function(event) {
        event.preventDefault(); // Prevent the default behavior of the link
        var denyUrl = this.getAttribute('data-deny-url');
        Swal.fire({
            text: "Are you sure you would like to Deny this account?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes",
            customClass: {
                confirmButton: "btn btn-primary me-2 waves-effect waves-light",
                cancelButton: "btn btn-label-secondary waves-effect waves-light",
            },
            buttonsStyling: false,
        }).then(function(result) {
            if (result.value) {
                $.ajax({
                    url: denyUrl,
                    method: 'GET',
                    success: function(response) {
                        Swal.fire({
                            icon: "success",
                            title: "Denied",
                            text: response.message,
                            customClass: {
                                confirmButton: "btn btn-success waves-effect waves-light",
                            },
                        }).then(() => {
                            window.location.reload();
                        });
                    },
                    error: function(xhr, status, error) {
                        // If there's an error, show an error message
                        Swal.fire({
                            title: "Error",
                            text: "An error occurred while approving the account.",
                            icon: "error",
                            customClass: {
                                confirmButton: "btn btn-success waves-effect waves-light",
                            },
                        });
                    }
                });
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                // If user cancels, show cancelled message
                Swal.fire({
                    title: "Cancelled",
                    text: "Denied Cancelled!!",
                    icon: "error",
                    customClass: {
                        confirmButton: "btn btn-success waves-effect waves-light",
                    },
                });
            }
        });
    });

    // @php $userId = 1; @endphp
    // Function to generate role badge based on roleId
    function callRoles(roleId) {
        if (roleId === 1) {
            return '<span class="badge bg-label-danger">Father</span>';
        } else if (roleId === 2) {
            return '<span class="badge bg-label-success">Mother</span>';
        } else {
            return '<span class="badge bg-label-info">Others</span>';
        }
    }


    // Fetch family members based on userId
    // var userId = "{{ $userId }}"; // Make sure $userId is properly assigned in your Blade template
    var url = window.location.href;

    // Split the URL by '/'
    var parts = url.split('/');

    // Get the last part of the URL, which is the ID
    var userId = parts[parts.length - 1];
    $.ajax({
        url: `/api/subscriberlogins/${userId}/family-members`,
        method: "GET",
        dataType: "json",
        success: function(response) {
            // Check if data is available
            if (response.data && response.data.length > 0) {
                // Iterate through family members and dynamically populate the list
                response.data.forEach(member => {
                    // Create HTML for each family member
                    const memberHTML = `
                    <li class="mb-3">
                        <div class="d-flex align-items-center">
                            <div class="d-flex align-items-start">
                                <div class="avatar me-2">
                                    <img src="${member.ProfileImage}" alt="Avatar" class="rounded-circle" />
                                </div>
                                <div class="me-2 ms-1">
                                    <a href="/userProfile/${member.id}"><h6 class="mb-0">${member.FirstName} ${member.LastName}</h6></a>
                                </div>
                            </div>
                            <div class="ms-auto">
                                ${callRoles(member.RoleId)} <!-- Use callRoles function here -->
                            </div>
                        </div>
                    </li>
                    `;
                    // Append the member HTML to the family members list
                    $('#familyMembers').append(memberHTML);
                });
            } else {
                // Handle case when no family members are available
                $('#familyMembers').append('<li>No family members found.</li>');
            }
        },
        error: function(xhr, status, error) {
            console.error("Error fetching family members:", error);
            // Handle error case
            $('#familyMembers').append('<li>Error fetching family members.</li>');
        }
    });
</script>
