export const init = async () => {
    $("form").on("submit", function(event) {
        event.originalEvent.preventDefault();

        let formData = new FormData();
        formData.append("file", $("#file")[0].files[0]);
        formData.append("group", $("#group").val());
        formData.append("password", $("#password").val());

        $.ajax({
            url: $(this).attr("action"),
            type: "POST",
            contentType: false,
            processData: false,
            data: formData,
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
