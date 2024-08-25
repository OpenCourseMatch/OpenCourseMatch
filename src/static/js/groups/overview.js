export const GroupsOverview = {
    init: (translations) => {
        const table = new DataTable("#groups-table", {
            layout: {
                topStart: "search",
                topEnd: null,
                bottomStart: "paging",
                bottomEnd: null
            },
            language: {
                sSearch: "",
                sSearchPlaceholder: translations["Search..."],
                sZeroRecords: translations["No entries"],
                emptyTable: translations["No entries"],
                oPaginate: {
                    sPrevious: translations["Back"],
                    sNext: translations["Next"]
                },
                loadingRecords: translations["Loading..."]
            },
            pagingType: "simple_numbers",
            order: [[1, "asc"]],
            ajax: {
                url: $("#groups-table").data("table-ajax"),
                dataSrc: "",
                type: "POST"
            },
            autoWidth: false,
            columns: [
                { data: "name" },
                { data: "clearance" }
            ]
        });

        let search = $("#groups-table_wrapper .dt-search input");
        search.attr("type", "text");

        let searchLayoutRow = $("#groups-table_wrapper .dt-search").closest(".dt-layout-row");
        let createButton = $("#create-group");
        searchLayoutRow.append(createButton);

        $("#groups-table tbody").on("click", "tr", function() {
            window.location.href = table.row(this).data().editHref;
        });
    }
};

export default GroupsOverview;
