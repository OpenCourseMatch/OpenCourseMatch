let currentCourseId = null;
let modalOpened = false;

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

    // Setup move modal events
    $("#movepopup-modal").on("close", () => {
        closeMoveModal();
    });

    $(".movepopup-modal-abort-button").on("click", () => {
        closeMoveModal();
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

export const initCourseOverview = (translations, loadMoveModalLink) => {
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
        order: [],
        ajax: {
            url: $("#users-table").attr("data-table-ajax"),
            dataSrc: "",
            type: "GET"
        },
        autoWidth: false,
        columns: [
            {
                data: "isCourseLeader",
                render: (data) => {
                    if(data) {
                        return $("#course-leader-icon").html();
                    } else {
                        return "";
                    }
                }
            },
            { data: "firstName" },
            { data: "lastName" },
            { data: "group" }
        ],
        columnDefs: [{
            defaultContent: "-",
            targets: "_all"
        }],
        createdRow: function(row, data) {
            if(data.highlighting === 1) {
                $(row).addClass("bg-info").addClass("text-info-font");
            } else if(data.highlighting === 2) {
                $(row).addClass("bg-warning").addClass("text-warning-font");
            }
        }
    });

    let search = $("#users-table_wrapper .dt-search input");
    search.attr("type", "text");

    let searchLayoutRow = $("#users-table_wrapper .dt-search").closest(".dt-layout-row");
    let tableActions = $("#table-actions");
    searchLayoutRow.append(tableActions);

    $("#users-table tbody").on("click", "tr", function() {
        openMoveModal(loadMoveModalLink, table.row(this).data().id);
    });
}

export const initMovePopup = (moveUserLink) => {
    $("#movepopup-modal-content-body button").on("click", function() {
        const courseId = $(this).attr("data-course");

        let data = {};
        if(courseId !== "") {
            data.course = courseId;
        }

        // TODO: Add loading animation

        $.ajax({
            url: moveUserLink,
            method: "POST",
            data: data
        }).done((data) => {
            if(data.code === 200) {
                closeMoveModal();
                $("#users-table").DataTable().ajax.reload();
            }
        });
    });
}

const openMoveModal = (loadMoveModalLink, userId) => {
    if(modalOpened) {
        throw new Error("Modal is already active");
    }

    modalOpened = true;

    // Show modal
    $("#movepopup-modal").get(0).showModal();
    $("#movepopup-modal").get(0).classList.remove("hidden");

    // Load modal content
    $.ajax({
        url: loadMoveModalLink,
        method: "POST",
        data: {
            user: userId
        }
    }).done((data) => {
        if(data.code === 200) {
            $("#movepopup-modal-loading").get(0).classList.add("hidden");
            $("#movepopup-modal-content-body").get(0).classList.remove("hidden");
            $("#movepopup-modal-content-body").html(data.data.html);
        } else {
            closeMoveModal();
        }
    });
}

export const closeMoveModal = () => {
    $("#movepopup-modal").get(0).classList.add("hidden");
    $("#movepopup-modal").get(0).close();

    modalOpened = false;

    // Reset modal content
    $("#movepopup-modal-loading").get(0).classList.remove("hidden");
    $("#movepopup-modal-content-body").get(0).classList.add("hidden");
    $("#movepopup-modal-content-body").html("");
}

export default { init, initCourseOverview, initMovePopup, closeMoveModal };
