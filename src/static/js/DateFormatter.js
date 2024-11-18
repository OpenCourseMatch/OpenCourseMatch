export const init = () => {
    $("time").each((index, element) => {
        const date = new Date($(element).attr("datetime"));

        const showTime = $(element).attr("data-show-time") === "true";
        const showDate = $(element).attr("data-show-date") === "true";
        const monthAsText = $(element).attr("data-month-as-text") === "true";
        const hideSeconds = $(element).attr("data-hide-seconds") === "true";
        const showWeekday = $(element).attr("data-show-weekday") === "true";

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

        $(element).text(date.toLocaleString(undefined, options));
    });
}

export default { init };