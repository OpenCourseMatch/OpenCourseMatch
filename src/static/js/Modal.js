let active = false;
let callback = (confirm) => {};

/**
 * Initialize the modal
 */
export const init = () => {
    document.getElementById("modal").addEventListener("close", () => {
        close(false);
    });

    document.querySelectorAll(".modal-abort-button").forEach((element) => {
        element.addEventListener("click", () => {
            close(false);
        });
    });

    document.querySelectorAll(".modal-confirm-button").forEach((element) => {
        element.addEventListener("click", () => {
            close(true);
        });
    });
}

/**
 * Close the modal, call the callback and reset the data
 * @param confirm
 */
export const close = (confirm) => {
    active = false;
    callback(confirm);
    callback = (confirm) => {};
    document.getElementById("modal").close();
    document.getElementById("modal-content-title").innerHTML = "";
    document.getElementById("modal-content-body").innerText = "";
    document.querySelectorAll(".modal-content-abort").forEach((element) => {
        element.innerText = "Abort";
    });
    document.querySelectorAll(".modal-content-confirm").forEach((element) => {
        element.innerText = "Confirm";
    });
}

/**
 * Show a modal with the given content
 * @param content
 * @param newCallback
 */
export const open = (content, newCallback) => {
    if(!(active)) {
        active = true;
        callback = newCallback;

        if(content.hasOwnProperty("title")) {
            document.getElementById("modal-content-title").innerText = content.title;
        }

        if(content.hasOwnProperty("text")) {
            document.getElementById("modal-content-body").innerHTML = content.text.replaceAll("\n", "<br>");
        }

        if(content.hasOwnProperty("abort")) {
            document.querySelectorAll(".modal-content-abort").forEach((element) => {
                element.innerText = content.abort;
            });
        }

        if(content.hasOwnProperty("confirm")) {
            document.querySelectorAll(".modal-content-confirm").forEach((element) => {
                element.innerText = content.confirm;
            });
        }

        document.getElementById("modal").showModal();
    } else {
        throw new Error("Modal is already active");
    }
}

export default {
    init,
    open,
    close
};
