export const GroupsOverview = {
    init: () => {
        const table = new DataTable("#groups-table", {
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
        });

        let filter = $("#groups-table_filter input");
        filter.attr("type", "text");

        let filterContainer = $("#groups-table_filter");
        let createButton = $("#create-group");
        filterContainer.append(createButton);

        $("#groups-table tbody").on("click", "tr", function() {
            window.location.href = $(this).data("href");
        });
    }
};

export default GroupsOverview;
