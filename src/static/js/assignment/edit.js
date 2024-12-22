let currentCourseId = null;

export const init = (courseIds, loadCourseOverviewLink) => {
    // Load first course overview
    if(courseIds.length > 0) {
        currentCourseId = courseIds[0];
        loadCourseOverview(currentCourseId, loadCourseOverviewLink);
        currentCourseId = courseIds[0];
    }

    // Setup next and previous course overview buttons
    $("#previous-course").on("click", () => {
        loadPreviousCourseOverview(courseIds, loadCourseOverviewLink);
    });
    $("#next-course").on("click", () => {
        loadNextCourseOverview(courseIds, loadCourseOverviewLink);
    });
}

const loadNextCourseOverview = (courseIds, loadCourseOverviewLink) => {
    const currentCourseIndex = courseIds.indexOf(currentCourseId);
    const nextCourseIndex = (currentCourseIndex + 1) % courseIds.length;
    loadCourseOverview(courseIds[nextCourseIndex], loadCourseOverviewLink);
    currentCourseId = courseIds[nextCourseIndex];
}

const loadPreviousCourseOverview = (courseIds, loadCourseOverviewLink) => {
    const currentCourseIndex = courseIds.indexOf(currentCourseId);
    const previousCourseIndex = (currentCourseIndex - 1 + courseIds.length) % courseIds.length;
    loadCourseOverview(courseIds[previousCourseIndex], loadCourseOverviewLink);
    currentCourseId = courseIds[previousCourseIndex];
}

const loadCourseOverview = (id, loadCourseOverviewLink) => {
    const courseOverview = $('#courseoverview');
    if($("#users-table").length > 0) {
        $("#users-table").DataTable().destroy();
    }
    courseOverview.html("");
    setLoadAnimationVisible(true);
    setLoadErrorVisible(false);

    $.ajax({
        url: loadCourseOverviewLink,
        method: "POST",
        data: {
            course: id
        }
    }).done((data) => {
        if(data.code === 200) {
            courseOverview.html(data.data.html);
        } else {
            setLoadErrorVisible(true)
        }

        setLoadAnimationVisible(false);
    });
}

const setLoadAnimationVisible = (visible) => {
    if(visible) {
        $("#loadanimation").show();
    } else {
        $("#loadanimation").hide();
    }
}

const setLoadErrorVisible = (visible) => {
    if(visible) {
        $("#loaderror").show();
    } else {
        $("#loaderror").hide();
    }
}

export const initTable = (translations) => {
    const table = new DataTable("#users-table", {
        layout: {
            topStart: "search",
            topEnd: null,
            bottomStart: null,
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
        paging: false,
        order: []
    });

    let search = $("#users-table_wrapper .dt-search input");
    search.attr("type", "text");

    let searchLayoutRow = $("#users-table_wrapper .dt-search").closest(".dt-layout-row");
    let tableActions = $("#table-actions");
    searchLayoutRow.append(tableActions);

    $("#users-table tbody").on("click", "tr", function() {
        window.location.href = table.row(this).data().editHref;
    });
}

export default { init, initTable };
