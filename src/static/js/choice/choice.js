import { t } from "../Translator.js";

let translations = [];

export const init = async () => {
    document.querySelectorAll("[data-course-id]").forEach((element) => {
        element.addEventListener("click", () => {
            const courseId = element.getAttribute("data-course-id");
            const choiceIndex = parseInt(element.getAttribute("data-choice-index"));

            choose(courseId, choiceIndex);
        });
    });

    document.querySelectorAll("button").forEach((element) => {
        const action = element.getAttribute("data-action");

        element.addEventListener("click", () => {
            if(action === "back") {
                previousChoice();
            } else if(action === "next") {
                nextChoice();
            }
        });
    });

    translations = await Promise.all([
        t("Choice $$index$$")
    ]);

    updateChosenCourses();
    revealSubmit();
}

const choose = (courseId, choiceIndex) => {
    const inputForChoiceIndex = document.querySelector("input[data-choice-index=\"" + choiceIndex + "\"]");

    // Remove this course from all other choice indices
    document.querySelectorAll("input[data-choice-index][value=\"" + courseId + "\"]").forEach((element) => {
        element.value = "";
    });

    // Select the course
    inputForChoiceIndex.value = courseId;

    updateChosenCourses();
    nextChoice();
    revealSubmit();
}

const updateChosenCourses = () => {
    const chosen = [];
    document.querySelectorAll("input[data-choice-index]").forEach((element) => {
        const courseId = element.value;
        if(courseId !== "") {
            chosen.push(courseId);
        }
    });

    // Set all courses to be available
    document.querySelectorAll("[data-course-id]").forEach((element) => {
        const courseId = element.getAttribute("data-course-id");
        renderCourseUnchosen(courseId);
    });

    // Set chosen courses to be unavailable
    chosen.forEach((courseId) => {
        renderCourseChosen(courseId);
    });
}

const renderCourseUnchosen = (courseId) => {
    document.querySelectorAll("[data-course-id=\"" + courseId + "\"]").forEach((element) => {
        const choiceNote = element.querySelector("[data-choice-note]");
        element.removeAttribute("data-chosen");
        choiceNote.style.display = "none";
        choiceNote.classList.remove("hidden");
        choiceNote.textContent = "";
    });
}

const renderCourseChosen = (courseId) => {
    document.querySelectorAll("[data-course-id=\"" + courseId + "\"]").forEach((element) => {
        const choiceIndex = parseInt(document.querySelector("input[value=\"" + courseId + "\"]").getAttribute("data-choice-index"));
        const choiceNote = element.querySelector("[data-choice-note]");
        element.setAttribute("data-chosen", choiceIndex.toString());
        choiceNote.textContent = translations[0].replaceAll("$$index$$", choiceIndex + 1);
        choiceNote.style.display = "block";
        choiceNote.classList.remove("hidden");
    });
}

const nextChoice = () => {
    const currentChoice = document.querySelector(".choice-container[data-active]");
    const currentChoiceIndex = parseInt(currentChoice.getAttribute("data-choice-index"));
    const nextChoiceIndex = currentChoiceIndex + 1;
    const nextChoice = document.querySelector(".choice-container[data-choice-index=\"" + nextChoiceIndex + "\"]");
    if(nextChoice !== null) {
        currentChoice.removeAttribute("data-active");
        nextChoice.setAttribute("data-active", "true");
    }
}

const previousChoice = () => {
    const currentChoice = document.querySelector(".choice-container[data-active]");
    const currentChoiceIndex = parseInt(currentChoice.getAttribute("data-choice-index"));
    const previousChoiceIndex = currentChoiceIndex - 1;
    const previousChoice = document.querySelector(".choice-container[data-choice-index=\"" + previousChoiceIndex + "\"]");
    if(previousChoice !== null) {
        currentChoice.removeAttribute("data-active");
        previousChoice.setAttribute("data-active", "true");
    }
}

const revealSubmit = () => {
    const choiceInputs = document.querySelectorAll("input[name=\"choice[]\"]");
    let allChosen = true;
    choiceInputs.forEach((element) => {
        if(element.value === "") {
            allChosen = false;
        }
    });

    if(allChosen) {
        document.querySelector("button[type=\"submit\"]").removeAttribute("disabled");
    } else {
        document.querySelector("button[type=\"submit\"]").setAttribute("disabled", "disabled");
    }
}

export default { init };
