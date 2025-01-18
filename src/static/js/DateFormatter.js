export const init = () => {
    document.querySelectorAll("time").forEach((element) => {
        const date = new Date(element.getAttribute("datetime"));

        const showTime = element.getAttribute("data-show-time") === "true";
        const showDate = element.getAttribute("data-show-date") === "true";
        const monthAsText = element.getAttribute("data-month-as-text") === "true";
        const hideSeconds = element.getAttribute("data-hide-seconds") === "true";
        const showWeekday = element.getAttribute("data-show-weekday") === "true";

        const options = {};

        if(showTime) {
            options.hour = "2-digit";
            options.minute = "2-digit";
            if(!hideSeconds) {
                options.second = "2-digit";
            }
        }
        if(showDate) {
            options.year = "numeric";
            if(monthAsText) {
                options.month = "long";
            } else {
                options.month = "numeric";
            }
            options.day = "numeric";
            if(showWeekday) {
                options.weekday = "long";
            }
        }

        element.textContent = date.toLocaleString(undefined, options);
    });
}

export default { init };