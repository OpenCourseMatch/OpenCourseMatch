import Modal from "../Modal.js";

export const FacilitatorsEdit = {
    init: () => {
        Modal.init();

        $("#delete-user").on("click", () => {
            Modal.open({
                title: "Delete facilitator",
                text: "Do you really want to delete this facilitator?",
                confirm: "Delete"
            }, (confirm) => {
                if(confirm) {
                    window.location.href = $("#delete-user").data("delete-href");
                }
            });
        });
    }
};

export default FacilitatorsEdit;
