import Modal from "../Modal.js";

export const UserEdit = {
    init: () => {
        Modal.init();

        $("#delete-user").on("click", () => {
            Modal.open({
                title: "Delete user",
                text: "Do you really want to delete this user?",
                confirm: "Delete"
            }, (confirm) => {
                if(confirm) {
                    window.location.href = $("#delete-user").data("delete-href");
                }
            });
        });
    }
};

export default UserEdit;
