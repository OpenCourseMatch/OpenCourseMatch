import * as Modal from "../Modal.js";
import { t } from "../Translator.js";
import * as ButtonLoad from "../ButtonLoad.js";
import InfoMessage from "../InfoMessage.js";

export const init = async () => {
    Modal.init();

    const translations = await Promise.all([
        t("Delete facilitator"),
        t("Do you really want to delete this facilitator?"),
        t("Delete"),
        t("An error has occurred whilst attempting to save the facilitator. Please try again later.")
    ]);

    const form = document.querySelector("form");
    if(form !== null) {
        const formSubmit = form.querySelector("button[type=\"submit\"]");

        form.addEventListener("submit", (event) => {
            event.preventDefault();

            const href = form.getAttribute("action");
            const formData = new FormData(form);

            const xhr = new XMLHttpRequest();
            xhr.open("POST", href);

            xhr.responseType = "blob";

            xhr.onload = async () => {
                const data = xhr.response;

                if(xhr.status >= 200 && xhr.status < 300) {
                    if(data.type === "application/pdf") {
                        download(data, "ocm-" + new Date().toLocaleString() + ".pdf", "application/pdf");
                    }

                    setTimeout(() => {
                        window.location = form.getAttribute("data-redirect");
                    }, 500);
                } else {
                    ButtonLoad.unload(formSubmit);

                    let errorShown = false;
                    if(data.type === "application/json") {
                        const textResponse = await data.text();
                        const jsonResponse = JSON.parse(textResponse);
                        if(jsonResponse.data !== undefined && jsonResponse.data.message !== undefined) {
                            InfoMessage.create(jsonResponse.data.message, InfoMessage.TYPE_ERROR);
                            return
                        }
                    }

                    InfoMessage.create(translations[3], InfoMessage.TYPE_ERROR);
                }
            };

            xhr.onerror = () => {
                InfoMessage.create(translations[3], InfoMessage.TYPE_ERROR);
            };

            xhr.send(formData);
        });
    }

    const deleteUser = document.querySelector("#delete-user");
    if(deleteUser !== null) {
        deleteUser.addEventListener("click", () => {
            Modal.open({
                title: translations[0],
                text: translations[1],
                confirm: translations[2]
            }, (confirm) => {
                if(confirm) {
                    ButtonLoad.load(deleteUser);
                    window.location.href = deleteUser.getAttribute("data-delete-href");
                }
            });
        });
    }
}

export default { init };
