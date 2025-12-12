import * as DateFormatter from "./DateFormatter.js";

export const init = (
    elementId,
    tableAjax,
    columnDefs,
    translations
) => {
    let tableElement = document.querySelector("#" + elementId);
    let createButton = document.querySelector("#create");

    let columns = [];
    for(let data in columnDefs) {
        const render = columnDefs[data].render;
        columns.push({
            data: data,
            render: render ?? renderPlain
        });
    }

    const table = new DataTable(tableElement, {
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
        order: [[0, "asc"]],
        ajax: {
            url: tableAjax,
            dataSrc: "",
            type: "POST"
        },
        autoWidth: false,
        columns,
        rowCallback: (row, data) => {
            if(data.href) {
                row.dataset.href = data.href;
            }
        }
    });

    const search = document.querySelector("#table_wrapper .dt-search input");
    search.setAttribute("type", "text");

    if(createButton) {
        const searchLayoutRow = document.querySelector("#table_wrapper .dt-search").closest(".dt-layout-row");
        searchLayoutRow.append(createButton);
    }

    document.querySelector("#" + elementId + " tbody").addEventListener("click", (event) => {
        const clickedRow = event.target.closest("tr");
        const newTab = event.ctrlKey || event.metaKey;
        if(clickedRow) {
            if(table.row(clickedRow).data().href) {
                const href = table.row(clickedRow).data().href;
                if(newTab) {
                    window.open(href, "_blank");
                } else {
                    window.location.href = href;
                }
            }
        }
    });

    document.querySelector("#" + elementId + " tbody").addEventListener("auxclick", (event) => {
        const clickedRow = event.target.closest("tr");
        if(event.button === 1) { // Middle click
            if(clickedRow) {
                if(table.row(clickedRow).data().href) {
                    const href = table.row(clickedRow).data().href;
                    window.open(href, "_blank");
                }
            }
        }
    });
}

export const renderPlain = (data, type, row) => {
    return DataTable.util.escapeHtml(data);
}

export const renderBoolean = (data, type, row) => {
    if(data) {
        return "✅";
    } else {
        return "❌";
    }
}

export const renderDate = (data, type, row) => {
    if(!data) {
        return "-";
    }

    return DateFormatter.render(data, true, true,false, true, false);
}

export default { init, renderPlain, renderBoolean, renderDate };
