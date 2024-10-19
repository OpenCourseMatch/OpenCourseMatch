import { t } from "../Translator.js";

let translations = [];

export const init = async () => {
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
        revealSubmit();
    });

    $("button").on("click", function() {
        const action = $(this).attr("data-action");

        if(action === "back") {
            previousChoice();
        } else if(action === "next") {
            nextChoice();
        }
    });

    translations = await Promise.all([
        t("Choice")
    ]);

    updateAvailableCourses();
    revealSubmit();
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

    // Set chosen courses to be unavailable
    chosen.forEach((courseId) => {
        setCourseUnavailable(courseId);
    });
}

const setCourseAvailable = (courseId) => {
    const choiceElement = $("[data-course-id=\"" + courseId + "\"]");
    const choiceNote = choiceElement.find("[data-choice-note]");

    choiceElement.removeAttr("data-chosen");
    choiceNote.hide();
    choiceNote.removeClass("hidden");
    choiceNote.text("");
}

const setCourseUnavailable = (courseId) => {
    const choiceElement = $("[data-course-id=\"" + courseId + "\"]");
    const choiceNote = choiceElement.find("[data-choice-note]");
    const choiceIndex = parseInt($("input[value=\"" + courseId + "\"]").attr("data-choice-index"));

    choiceElement.attr("data-chosen", "true");
    choiceNote.text(translations[0] + " " + (choiceIndex + 1)).show();
    choiceNote.show();
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

const revealSubmit = () => {
    const choiceInputs = $("input[name=\"choice[]\"]");
    let allChosen = true;
    choiceInputs.each((index, element) => {
        if($(element).val() === "") {
            allChosen = false;
        }
    });

    if(allChosen) {
        $("button[type=\"submit\"]").removeAttr("disabled");
    }
}

export default { init };
