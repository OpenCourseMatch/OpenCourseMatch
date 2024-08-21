class InfoMessage {
    static TYPE_INFO = 0;
    static TYPE_WARNING = 1;
    static TYPE_ERROR = 2;
    static TYPE_SUCCESS = 3;

    constructor(message, type) {
        let messageElement = document.createElement("div");
        messageElement.classList.add("inline-block");
        messageElement.classList.add("w-full");
        messageElement.classList.add("p-4");
        messageElement.classList.add("mb-4");
        messageElement.classList.add("border");
        messageElement.classList.add("rounded");
        switch(type) {
            case InfoMessage.TYPE_INFO: {
                messageElement.classList.add("bg-infomessage-info-background");
                messageElement.classList.add("border-infomessage-info-border");
                messageElement.setAttribute("message-type", "info");
                break;
            }
            case InfoMessage.TYPE_WARNING: {
                messageElement.classList.add("bg-infomessage-warning-background");
                messageElement.classList.add("border-infomessage-warning-border");
                messageElement.setAttribute("message-type", "warning");
                break;
            }
            case InfoMessage.TYPE_ERROR: {
                messageElement.classList.add("bg-infomessage-error-background");
                messageElement.classList.add("border-infomessage-error-border");
                messageElement.setAttribute("message-type", "error");
                break;
            }
            case InfoMessage.TYPE_SUCCESS: {
                messageElement.classList.add("bg-infomessage-success-background");
                messageElement.classList.add("border-infomessage-success-border");
                messageElement.setAttribute("message-type", "success");
                break;
            }
        }
        messageElement.innerText = message;

        let messageTypes = [
            {
                messages: document.querySelectorAll(".infomessageslist div[message-type=\"info\"]").length,
                firstMessage: document.querySelector(".infomessage-list div[message-type=\"info\"]")
            },
            {
                messages: document.querySelectorAll(".infomessage-list div[message-type=\"warning\"]").length,
                firstMessage: document.querySelector(".infomessage-list div[message-type=\"warning\"]")
            },
            {
                messages: document.querySelectorAll(".infomessage-list div[message-type=\"error\"]").length,
                firstMessage: document.querySelector(".infomessage-list div[message-type=\"error\"]")
            },
            {
                messages: document.querySelectorAll(".infomessage-list div[message-type=\"success\"]").length,
                firstMessage: document.querySelector(".infomessage-list div[message-type=\"success\"]")
            }
        ];

        let inserted = false;
        let messageList = document.querySelector(".infomessage-list");
        for(let i = type; i >= 0; i--) {
            if(messageTypes[i].messages > 0) {
                messageList.insertBefore(messageElement, messageTypes[i].firstMessage)
                inserted = true;
                break;
            }
        }

        if(!(inserted)) {
            messageList.appendChild(messageElement);
        }
    }

    static clearMessages() {
        let messagesList = document.querySelector(".infomessage-list");
        messagesList.innerHTML = "";
    }
}
