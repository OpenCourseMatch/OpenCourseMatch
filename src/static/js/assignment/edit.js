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
