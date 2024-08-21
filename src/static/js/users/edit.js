import * as Modal from "../Modal.js";
import { t } from "../Translator.js";

export const UsersEdit = {
    init: async () => {
        Modal.init();

        const translations = await Promise.all([
            t("Delete user"),
            t("Do you really want to delete this user?"),
            t("Delete")
        ])

        $("#delete-user").on("click", () => {
            Modal.open({
                title: translations[0],
                text: translations[1],
                confirm: translations[2]
            }, (confirm) => {
                if(confirm) {
                    window.location.href = $("#delete-user").data("delete-href");
                }
            });
        });
    }
};

export default UsersEdit;
