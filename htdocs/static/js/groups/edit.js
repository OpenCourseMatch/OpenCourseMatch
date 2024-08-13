import Modal from "../Modal.js";

export const GroupsEdit = {
    init: () => {
        Modal.init();

        $("#delete-group").on("click", () => {
            Modal.open({
                title: "Delete group",
                text: "Do you really want to delete this group?",
                confirm: "Delete"
            }, (confirm) => {
                if(confirm) {
                    window.location.href = $("#delete-group").data("delete-href");
                }
            });
        });
    }
};

export default GroupsEdit;
