export const init = () => {
    $("[data-course-id]").on("click", function() {
        const choiceIndex = $(this).attr("data-choice-index");
        const courseId = $(this).attr("data-course-id");

        if($(this).attr("data-chosen") !== undefined) {
            return;
        }

        const inputForChoiceIndex = $("input[data-choice-index=\"" + choiceIndex + "\"]");
        inputForChoiceIndex.val(courseId);

        updateAvailableCourses();
        nextChoice();
    });

    $("button").on("click", function() {
        const action = $(this).attr("data-action");

        if(action === "back") {
            previousChoice();
        } else if(action === "next") {
            nextChoice();
        }
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

const nextChoice = () => {
    const currentChoice = $("[data-active]");
    const currentChoiceIndex = parseInt(currentChoice.attr("data-choice-index"))
    const nextChoiceIndex = currentChoiceIndex + 1;
    const nextChoice = $("[data-choice-index=\"" + nextChoiceIndex + "\"]");
    if(nextChoice.length !== 0) {
        currentChoice.removeAttr("data-active");
        nextChoice.attr("data-active", "true");
    }
}

const previousChoice = () => {
    const currentChoice = $("[data-active]");
    const currentChoiceIndex = parseInt(currentChoice.attr("data-choice-index"))
    const previousChoiceIndex = currentChoiceIndex - 1;
    const previousChoice = $("[data-choice-index=\"" + previousChoiceIndex + "\"]");
    if(previousChoice.length !== 0) {
        currentChoice.removeAttr("data-active");
        previousChoice.attr("data-active", "true");
    }
}

export default { init };
