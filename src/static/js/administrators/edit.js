import * as Modal from "../Modal.js";
import { t } from "../Translator.js";
import * as ButtonLoad from "../ButtonLoad.js";

export const init = async () => {
    Modal.init();

    const translations = await Promise.all([
        t("Delete administrator"),
        t("Do you really want to delete this administrator?"),
        t("Delete")
    ]);

    $("form").on("submit", function(event) {
        event.originalEvent.preventDefault();

        $.ajax({
            url: $(this).attr("action"),
            type: "POST",
            data: $(this).serialize(),
            xhr: function() {
                let xhr = new XMLHttpRequest()
                xhr.responseType = "blob";
                return xhr;
            }
        }).done((data) => {
            if(data.type === "application/pdf") {
                download(data, "ocm-" + new Date().toLocaleString() + ".pdf", "application/pdf");
            }

            setTimeout(() => {
                window.location = $(this).attr("data-redirect");
            }, 500);
        });
    });

    $("#delete-user").on("click", () => {
        Modal.open({
            title: translations[0],
            text: translations[1],
            confirm: translations[2]
        }, (confirm) => {
            if(confirm) {
                ButtonLoad.load($("#delete-user"));
                window.location.href = $("#delete-user").attr("data-delete-href");
            }
        });
    });
}

export default { init };
