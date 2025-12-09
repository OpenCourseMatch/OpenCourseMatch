export const init = () => {
    document.querySelectorAll("time").forEach((element) => {
        initElement(element);
    });
}

const initElement = (element) => {
    const timestamp = element.getAttribute("datetime");
    const showTime = element.getAttribute("data-show-time") === "true";
    const showDate = element.getAttribute("data-show-date") === "true";
    const monthAsText = element.getAttribute("data-month-as-text") === "true";
    const hideSeconds = element.getAttribute("data-hide-seconds") === "true";
    const showWeekday = element.getAttribute("data-show-weekday") === "true";

    element.textContent = formatDate(timestamp, showTime, showDate, monthAsText, hideSeconds, showWeekday);
}

const formatDate = (timestamp, showTime, showDate, monthAsText, hideSeconds, showWeekday) => {
    const date = new Date(timestamp);
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

    return date.toLocaleString(undefined, options);
}

export const render = (timestamp, showTime, showDate, monthAsText, hideSeconds, showWeekday) => {
    let element = `<time datetime="${timestamp}" `;

    if(!showTime) {
        element += `data-show-time="false" `;
    } else {
        element += `data-show-time="true" `;
    }

    if(!showDate) {
        element += `data-show-date="false" `;
    } else {
        element += `data-show-date="true" `;
    }

    if(monthAsText) {
        element += `data-month-as-text="true" `;
    } else {
        element += `data-month-as-text="false" `;
    }

    if(!hideSeconds) {
        element += `data-hide-seconds="false" `;
    } else {
        element += `data-hide-seconds="true" `;
    }

    if(showWeekday) {
        element += `data-show-weekday="true" `;
    } else {
        element += `data-show-weekday="false" `;
    }

    element += `>${formatDate(timestamp, showTime, showDate, monthAsText, hideSeconds, showWeekday)}</time>`;

    return element;
}

export default { init, render };
