let active = false;
let callback = (confirm) => {};

/**
 * Initialize the modal
 */
export const init = () => {
    $("#modal").on("close", () => {
        close(false);
    });

    $(".modal-abort-button").on("click", () => {
        close(false);
    });

    $(".modal-confirm-button").on("click", () => {
        close(true);
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
    $("#modal").get(0).close();
    $("#modal-content-title").text("");
    $("#modal-content-body").text("");
    $(".modal-content-abort").text("Abort");
    $(".modal-content-confirm").text("Confirm");
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
            $("#modal-content-title").text(content.title);
        }

        if(content.hasOwnProperty("text")) {
            $("#modal-content-body").html(content.text.replaceAll("\n", "<br>"));
        }

        if(content.hasOwnProperty("abort")) {
            $(".modal-content-abort").text(content.abort);
        }

        if(content.hasOwnProperty("confirm")) {
            $(".modal-content-confirm").text(content.confirm);
        }

        $("#modal").get(0).showModal();
    } else {
        throw new Error("Modal is already active");
    }
}

export default {
    init,
    open,
    close
};
