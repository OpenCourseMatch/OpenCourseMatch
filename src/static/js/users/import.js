export const init = async () => {
    document.querySelectorAll("form").forEach((form) => {
        form.addEventListener("submit", (event) => {
            event.preventDefault();

            let formData = new FormData(form);
            formData.append("file", document.querySelector("#file").files[0]);
            formData.append("group", document.querySelector("#group").value);
            formData.append("password", document.querySelector("#password").value);

            fetch(form.getAttribute("action"), {
                method: "POST",
                body: formData
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
}

export default { init };
