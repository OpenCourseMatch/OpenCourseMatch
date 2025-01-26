import * as Modal from "../Modal.js";
import { t } from "../Translator.js";
import * as ButtonLoad from "../ButtonLoad.js";

export const init = async () => {
    Modal.init();

    const translations = await Promise.all([
        t("Delete group"),
        t("Do you really want to delete this group?"),
        t("Delete")
    ]);

    const deleteGroup = document.querySelector("#delete-group");
    if(deleteGroup !== null) {
        deleteGroup.addEventListener("click", () => {
            Modal.open({
                title: translations[0],
                text: translations[1],
                confirm: translations[2]
            }, (confirm) => {
                if(confirm) {
                    ButtonLoad.load(deleteGroup);
                    window.location.href = deleteGroup.getAttribute("data-delete-href");
                }
            });
        });
    }
}

export default { init };
