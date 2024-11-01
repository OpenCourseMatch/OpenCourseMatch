export const init = (translations) => {
    const table = new DataTable("#courses-table", {
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
            url: $("#courses-table").attr("data-table-ajax"),
            dataSrc: "",
            type: "POST"
        },
        autoWidth: false,
        columns: [
            { data: "title" }
        ]
    });

    let search = $("#courses-table_wrapper .dt-search input");
    search.attr("type", "text");

    let searchLayoutRow = $("#courses-table_wrapper .dt-search").closest(".dt-layout-row");
    let createButton = $("#create-course");
    searchLayoutRow.append(createButton);

    $("#courses-table tbody").on("click", "tr", function() {
        window.location.href = table.row(this).data().editHref;
    });
}

export default { init };
