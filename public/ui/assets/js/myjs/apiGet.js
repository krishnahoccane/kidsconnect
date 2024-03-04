// admin users data
    $.ajax({
        url: "http://localhost:8000/api/adminUsers",
        method: "GET",
        dataType: "json",
        success: function (response) {
            // Check if the 'data' key exists in the response
            if (response.data) {
                // Loop through the data and create table rows
                $.each(response.data, function (index, item) {
                    const createdAtDate = new Date(item.created_at);
                    // Format the date components
                    const formattedDate = `${createdAtDate.getFullYear()}-${(
                        createdAtDate.getMonth() + 1
                    )
                        .toString()
                        .padStart(2, "0")}-${createdAtDate
                        .getDate()
                        .toString()
                        .padStart(2, "0")}`;
                    // const formattedTime =
                    //     `${createdAtDate.getHours().toString().padStart(2, '0')}:${createdAtDate.getMinutes().toString().padStart(2, '0')}:${createdAtDate.getSeconds().toString().padStart(2, '0')}`;

                    // Combine the date and time components
                    const formattedDateTime = `${formattedDate}`;
                    const row = `
                                        <tr>
                                            <td>${index + 1}</td>
                                            <td>${item.username}</td>
                                            <td>${item.email}</td>
                                            <td>${formattedDateTime}</td>
                                            <td>
                                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                                    data-bs-target="#modalView">
                                                    View
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
        error: function (xhr, status, error) {
            console.error("Error fetching data from the API:", error);
        },
    });

