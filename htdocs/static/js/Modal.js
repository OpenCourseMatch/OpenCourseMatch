export const Modal = {
    active: false,
    callback: (confirm) => {},

    /**
     * Initialize the modal
     */
    init: () => {
        $("#modal").on("close", () => {
            Modal.close(false);
        });

        $(".modal-abort-button").on("click", () => {
            Modal.close(false);
        });

        $(".modal-confirm-button").on("click", () => {
            Modal.close(true);
        });
    },

    /**
     * Close the modal, call the callback and reset the data
     * @param confirm
     */
    close: (confirm) => {
        Modal.active = false;
        Modal.callback(confirm);
        Modal.callback = (confirm) => {};
        $("#modal").get(0).close();
        $("#modal-content-title").text("");
        $("#modal-content-body").text("");
        $(".modal-content-abort").text("Abort");
        $(".modal-content-confirm").text("Confirm");
        console.log(Modal.active);
    },

    /**
     * Show a modal with the given content
     * @param content
     * @param callback
     */
    open: (content, callback) => {
        if(!(Modal.active)) {
            Modal.active = true;
            Modal.callback = callback;

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
};

export default Modal;
