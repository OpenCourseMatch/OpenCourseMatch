import * as ButtonLoad from "../ButtonLoad.js";

let currentCourseId = null;
let modalOpened = false;
let movingUser = false;

let courseOverviewLink = null;

export const init = (courseIds, loadCourseOverviewLink) => {
    courseOverviewLink = loadCourseOverviewLink;

    // Load first course overview
    if(courseIds.length > 0) {
        currentCourseId = courseIds[0];
        loadCourseOverview(currentCourseId);
        currentCourseId = courseIds[0];
    }

    // Setup next and previous course overview buttons
    $("#previous-course").on("click", () => {
        loadPreviousCourseOverview(courseIds);
    });
    $("#next-course").on("click", () => {
        loadNextCourseOverview(courseIds);
    });

    // Setup move modal events
    $("#moveaway-modal").on("close", () => {
        closeMoveAwayModal();
    });

    $(".moveaway-modal-abort-button").on("click", () => {
        closeMoveAwayModal();
    });
}

const loadNextCourseOverview = (courseIds) => {
    const currentCourseIndex = courseIds.indexOf(currentCourseId);
    const nextCourseIndex = (currentCourseIndex + 1) % courseIds.length;
    loadCourseOverview(courseIds[nextCourseIndex]);
    currentCourseId = courseIds[nextCourseIndex];
}

const loadPreviousCourseOverview = (courseIds) => {
    const currentCourseIndex = courseIds.indexOf(currentCourseId);
    const previousCourseIndex = (currentCourseIndex - 1 + courseIds.length) % courseIds.length;
    loadCourseOverview(courseIds[previousCourseIndex]);
    currentCourseId = courseIds[previousCourseIndex];
}

const loadCourseOverview = (id) => {
    const courseOverview = $("#courseoverview");
    if($("#users-table").length > 0) {
        $("#users-table").DataTable().destroy();
    }
    courseOverview.html("");
    setLoadAnimationVisible(true);
    setLoadErrorVisible(false);

    $.ajax({
        url: courseOverviewLink,
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

export const initCourseOverview = (translations, loadMoveAwayModalLink, loadMoveHereModalLink) => {
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

    const searchLayoutRow = document.querySelector("#users-table_wrapper .dt-search").closest(".dt-layout-row");
    const moveHereButton = document.querySelector("#move-here");
    if(moveHereButton !== null) {
        searchLayoutRow.append(moveHereButton);
    }

    $("#users-table tbody").on("click", "tr", function() {
        openMoveAwayModal(loadMoveAwayModalLink, table.row(this).data().id);
    });

    if(moveHereButton !== null) {
        moveHereButton.addEventListener("click", () => {
            // TODO: Open modal
        });
    }
}

export const initMoveAwayModal = (moveUserLink) => {
    $("#moveaway-modal-content-body button").on("click", function() {
        if(movingUser) {
            return;
        }

        const courseId = $(this).attr("data-course");

        let data = {};
        if(courseId !== "") {
            data.course = courseId;
        }

        ButtonLoad.load(this);
        movingUser = true;

        $.ajax({
            url: moveUserLink,
            method: "POST",
            data: data
        }).done((data) => {
            movingUser = false;
            if(data.code === 200) {
                closeMoveAwayModal();
                loadCourseOverview(currentCourseId);
            }
        });
    });
}

const openMoveAwayModal = (loadMoveModalLink, userId) => {
    if(modalOpened) {
        throw new Error("Modal is already active");
    }

    modalOpened = true;

    // Show modal
    $("#moveaway-modal").get(0).showModal();
    $("#moveaway-modal").get(0).classList.remove("hidden");

    // Load modal content
    $.ajax({
        url: loadMoveModalLink,
        method: "POST",
        data: {
            user: userId
        }
    }).done((data) => {
        if(data.code === 200) {
            $("#moveaway-modal-loading").get(0).classList.add("hidden");
            $("#moveaway-modal-content-body").get(0).classList.remove("hidden");
            $("#moveaway-modal-content-body").html(data.data.html);
        } else {
            closeMoveAwayModal();
        }
    });
}

export const closeMoveAwayModal = () => {
    $("#moveaway-modal").get(0).classList.add("hidden");
    $("#moveaway-modal").get(0).close();

    modalOpened = false;

    // Reset modal content
    $("#moveaway-modal-loading").get(0).classList.remove("hidden");
    $("#moveaway-modal-content-body").get(0).classList.add("hidden");
    $("#moveaway-modal-content-body").html("");
}

export default { init, initCourseOverview, initMoveAwayModal, closeMoveAwayModal };
