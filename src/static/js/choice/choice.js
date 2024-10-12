export const init = () => {
    $("[data-course-id]").on("click", function() {
        const choiceIndex = $(this).attr("data-choice-index");
        const courseId = $(this).attr("data-course-id");

        const inputForChoiceIndex = $("input[data-choice-index=\"" + choiceIndex + "\"]");
        inputForChoiceIndex.val(courseId);

        updateAvailableCourses();
    });

    updateAvailableCourses();
}

const updateAvailableCourses = () => {
    const chosen = [];
    $("input[data-choice-index]").each((index, element) => {
        const courseId = $(element).val();
        if(courseId !== "") {
            chosen.push(courseId);
        }
    });

    // Set all courses to be available
    $("[data-course-id]").each((index, element) => {
        const courseId = $(element).attr("data-course-id");
        setCourseAvailable(courseId);
    });

    chosen.forEach((courseId) => {
        setCourseUnavailable(courseId);
    })
}

const setCourseAvailable = (courseId) => {
    $("[data-course-id=\"" + courseId + "\"]").removeAttr("data-chosen");
}

const setCourseUnavailable = (courseId) => {
    $("[data-course-id=\"" + courseId + "\"]").attr("data-chosen", "true");
}

export default { init };
