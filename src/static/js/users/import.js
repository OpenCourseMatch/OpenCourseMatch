export const init = async () => {
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
}

export default { init };
