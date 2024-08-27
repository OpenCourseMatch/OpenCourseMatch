export const AdministratorsOverview = {
    init: (translations) => {
        const table = new DataTable("#users-table", {
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
                url: $("#users-table").data("table-ajax"),
                dataSrc: "",
                type: "POST"
            },
            autoWidth: false,
            columns: [
                { data: "username" },
                { data: "firstName" },
                { data: "lastName" }
            ]
        });

        let search = $("#users-table_wrapper .dt-search input");
        search.attr("type", "text");

        let searchLayoutRow = $("#users-table_wrapper .dt-search").closest(".dt-layout-row");
        let createButton = $("#create-user");
        searchLayoutRow.append(createButton);

        $("#users-table tbody").on("click", "tr", function() {
            window.location.href = table.row(this).data().editHref;
        });
    }
};

export default AdministratorsOverview;
