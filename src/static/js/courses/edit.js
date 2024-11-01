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

    $("#delete-course").on("click", () => {
        Modal.open({
            title: translations[0],
            text: translations[1],
            confirm: translations[2]
        }, (confirm) => {
            if(confirm) {
                ButtonLoad.load($("#delete-course"));
                window.location.href = $("#delete-course").attr("data-delete-href");
            }
        });
    });
}

export default { init };
