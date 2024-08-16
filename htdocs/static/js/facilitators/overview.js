export const UsersOverview = {
    init: () => {
        const table = new DataTable("#users-table", {
            dom: "ftp",
            language: {
                sSearch: "",
                sSearchPlaceholder: "Search...",
                sZeroRecords: "No entries",
                oPaginate: {
                    sPrevious: "Back",
                    sNext: "Next"
                }
            },
            order: [[1, "asc"]],
            ajax: {
                url: $("#users-table").data("table-ajax"),
                dataSrc: "",
                type: "POST"
            },
            autoWidth: false,
            columns: [
                { data: "firstName" },
                { data: "lastName" }
            ]
        });

        let filter = $("#users-table_filter input");
        filter.attr("type", "text");

        let filterContainer = $("#users-table_filter");
        let createButton = $("#create-user");
        filterContainer.append(createButton);

        $("#users-table tbody").on("click", "tr", function() {
            window.location.href = table.row(this).data().editHref;
        });
    }
};

export default UsersOverview;
