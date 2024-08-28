import * as Modal from "../Modal.js";
import { t } from "../Translator.js";

export const init = async () => {
    Modal.init();

    const translations = await Promise.all([
        t("Delete group"),
        t("Do you really want to delete this group?"),
        t("Delete")
    ])

    $("#delete-group").on("click", () => {
        Modal.open({
            title: translations[0],
            text: translations[1],
            confirm: translations[2]
        }, (confirm) => {
            if(confirm) {
                window.location.href = $("#delete-group").data("delete-href");
            }
        });
    });
}

export default { init };
