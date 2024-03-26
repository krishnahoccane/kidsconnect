var baseUrl = "/api/allBanners";
// $('#bannerData').html('<tr><td colspan="5"><div class="sk-wave sk-primary"><div class="sk-wave-rect"></div><div class="sk-wave-rect"></div><div class="sk-wave-rect"></div><div class="sk-wave-rect"></div><div class="sk-wave-rect"></div></div></td></tr>');
$.ajax({
    url: baseUrl,
    method: "GET",
    dataType: "json",
    success: function (response) {
        if (response.data) {
            $('#bannerData').empty();

            $.each(response.data, function (index, item) {
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
                    var status = "Visible";
                    var buttonName = "Off Visibility";
                    var statusId = 0;
                    var buttonColor = "primary";
                    var buttonBadgeColor = "success";
                } else {
                    var status = "Not Visible";
                    var statusId = 1;
                    var buttonName = "On Visibility";
                    var buttonColor = "success";
                    var buttonBadgeColor = "primary";
                }
                // Combine the date and time components
                const formattedDateTime = `${formattedDate}`;
                const row = `
                                    <tr>
                                        <td>${index + 1}</td>
                                        <td><img src="/${
                                            item.image
                                        }" class="img-fluid w-50 rounded" data-bs-toggle="modal" data-bs-target="#modals-transparent" id="bannerImage"/></td>
                                        <td>${formattedDateTime}</td>
                                        <td data-sid="${statusId}"><span class="badge bg-label-${buttonBadgeColor}">${status}</span></a></td>
                                        <td>
                                            <button type="button" class="btn btn-${buttonColor}" data-id="${
                    item.id
                }"  id="statusChangeButton">
                                                ${buttonName}
                                            </button>
                                            <button type="button" class="btn btn-danger" data-did="${
                                                item.id
                                            }"  id="bannerDelete">
                                                Delete
                                            </button>
                                        </td>   
                                    </tr>
                                    `;
                $("#bannerData").append(row);
            });
        } else {
            console.error(
                'Error: Unable to find "data" key in the API response'
            );
        }
    },
    error: function (xhr, status, error) {
        console.error("Error fetching data from the API:", error);
    },
});
$(document).on("click", "#statusChangeButton", function () {
    var itemId = $(this).data("id");
    var tE = $(this).closest("tr");
    var status = tE.find("td:nth-child(4)").data("sid");
    if (status != 0) {
        var titleName = "Now its visible";
    } else {
        var titleName = "Now its not visible";
    }
    Swal.fire({
        text: "would you like to make this banner visible on App?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes",
        customClass: {
            confirmButton: "btn btn-primary me-2 waves-effect waves-light",
            cancelButton: "btn btn-label-secondary waves-effect waves-light",
        },
        buttonsStyling: false,
    }).then(function (result) {
        if (result.value) {
            $.ajax({
                url: baseUrl + "/" + itemId + "/edit",
                method: "PUT",
                data: {
                    status: status,
                }, // Include the status in the data payload
                success: function (response) {
                    Swal.fire({
                        icon: "success",
                        title: titleName,
                        text: response.message,
                        customClass: {
                            confirmButton:
                                "btn btn-success waves-effect waves-light",
                        },
                    }).then(() => {
                        window.location.reload();
                    });
                },
                error: function (xhr, status, error) {
                    // If there's an error, show an error message
                    Swal.fire({
                        title: "Error",
                        text: "An error occurred while approving the account.",
                        icon: "error",
                        customClass: {
                            confirmButton:
                                "btn btn-success waves-effect waves-light",
                        },
                    });
                },
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

$(document).on("click", "#bannerDelete", function () {
    var bannerIdForDelete = $(this).data("did");

    Swal.fire({
        text: "Would like to delete this banner?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes",
        customClass: {
            confirmButton: "btn btn-primary me-2 waves-effect waves-light",
            cancelButton: "btn btn-label-secondary waves-effect waves-light",
        },
        buttonsStyling: false,
    }).then(function (result) {
        if (result.value) {
            $.ajax({
                url: baseUrl + "/" + bannerIdForDelete,
                method: "DELETE",
                success: function (response) {
                    Swal.fire({
                        icon: "success",
                        title: "DELETE",
                        text: response.message,
                        customClass: {
                            confirmButton:
                                "btn btn-success waves-effect waves-light",
                        },
                    }).then(() => {
                        window.location.reload();
                    });
                },
                error: function (xhr, status, error) {
                    // If there's an error, show an error message
                    Swal.fire({
                        title: "Error",
                        text: "An error occurred while approving the account.",
                        icon: "error",
                        customClass: {
                            confirmButton:
                                "btn btn-success waves-effect waves-light",
                        },
                    });
                },
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
$(document).on("click", "#bannerImage", function () {
    var imageSrc = $(this).attr("src");

    $("#modals-transparent")
        .find(".carousel-item.active img")
        .attr("src", imageSrc);
    $("#modals-transparent").modal("show");
});
