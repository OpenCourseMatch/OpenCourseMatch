import * as Modal from "../Modal.js";
import { t } from "../Translator.js";
import * as ButtonLoad from "../ButtonLoad.js";

export const init = async () => {
    Modal.init();

    const translations = await Promise.all([
        t("Delete course"),
        t("Do you really want to delete this course?"),
        t("Delete")
    ]);

    const deleteCourse = document.querySelector("#delete-course");
    if(deleteCourse !== null) {
        deleteCourse.addEventListener("click", () => {
            Modal.open({
                title: translations[0],
                text: translations[1],
                confirm: translations[2]
            }, (confirm) => {
                if(confirm) {
                    ButtonLoad.load(deleteCourse);
                    window.location.href = deleteCourse.getAttribute("data-delete-href");
                }
            });
        });
    }
}

export default { init };
