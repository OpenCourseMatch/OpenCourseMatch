import * as Modal from "../Modal.js";
import { t } from "../Translator.js";
import * as ButtonLoad from "../ButtonLoad.js";

export const init = async () => {
    Modal.init();

    $("#resetPassword").on("change", function() {
        const checked = $(this).prop("checked");
        if(checked) {
            $("#new-password-input").removeClass("hidden");
        } else {
            $("#new-password-input").addClass("hidden");
            $("input[name=\"newPassword\"]").val("");
        }
    });

    $("#changeGroup").on("change", function() {
        const checked = $(this).prop("checked");
        if(checked) {
            $("#new-group-selection").removeClass("hidden");
        } else {
            $("#new-group-selection").addClass("hidden");
            $("select[name=\"newGruop\"]").val("");
        }
    });

    const translations = await Promise.all([
        t("Delete all users in group"),
        t("Do you really want to delete all users which are in this group?"),
        t("The group itself will not be deleted."),
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

    $("#delete-users").on("click", () => {
        Modal.open({
            title: translations[0],
            text: translations[1] + "\n" + translations[2],
            confirm: translations[3]
        }, (confirm) => {
            if(confirm) {
                ButtonLoad.load($("#delete-users"));
                $.ajax({
                    url: $("#delete-users").attr("data-delete-href"),
                    type: "POST",
                    data: $("form").serialize()
                }).done((data) => {
                    window.location = $("#delete-users").attr("data-redirect");
                });
            }
        });
    });
}

export default { init };
