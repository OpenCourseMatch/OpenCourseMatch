import * as Modal from "../Modal.js";
import { t } from "../Translator.js";
import * as ButtonLoad from "../ButtonLoad.js";

export const init = async () => {
    Modal.init();

    document.querySelector("#resetPassword").addEventListener("change", () => {
        const checked = document.querySelector("#resetPassword").checked;
        if(checked) {
            document.querySelector("#new-password-input").classList.remove("hidden");
        } else {
            document.querySelector("#new-password-input").classList.add("hidden");
            document.querySelector("input[name=\"newPassword\"]").value = "";
        }
    });

    document.querySelector("#changeGroup").addEventListener("change", () => {
        const checked = document.querySelector("#changeGroup").checked;
        if(checked) {
            document.querySelector("#new-group-selection").classList.remove("hidden");
        } else {
            document.querySelector("#new-group-selection").classList.add("hidden");
            document.querySelector("select[name=\"newGruop\"]").value = "";
        }
    });

    const translations = await Promise.all([
        t("Delete all users in group"),
        t("Do you really want to delete all users which are in this group?"),
        t("The group itself will not be deleted."),
        t("Delete")
    ]);

    document.querySelectorAll("form").forEach((form) => {
        form.addEventListener("submit", (event) => {
            event.preventDefault();

            fetch(form.getAttribute("action"), {
                method: "POST",
                body: new FormData(form)
            }).then((response) => {
                return response.blob();
            }).then((data) => {
                if(data.type === "application/pdf") {
                    download(data, "ocm-" + new Date().toLocaleString() + ".pdf", "application/pdf");
                }

                setTimeout(() => {
                    window.location = form.getAttribute("data-redirect");
                }, 500);
            });
        });
    });

    document.querySelector("#delete-users").addEventListener("click", () => {
        Modal.open({
            title: translations[0],
            text: translations[1] + "\n" + translations[2],
            confirm: translations[3]
        }, (confirm) => {
            if(confirm) {
                ButtonLoad.load(document.querySelector("#delete-users"));
                fetch(document.querySelector("#delete-users").getAttribute("data-delete-href"), {
                    method: "POST",
                    body: new FormData(document.querySelector("form"))
                }).then((response) => {
                    window.location = document.querySelector("#delete-users").getAttribute("data-redirect");
                });
            }
        });
    });
}

export default { init };
