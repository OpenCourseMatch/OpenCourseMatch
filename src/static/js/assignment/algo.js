export const init = (statusLink, redirectLink) => {
    setInterval(async () => {
        if(await algorithmFinished(statusLink)) {
            window.location.href = redirectLink;
        }
    }, 5000);
}

const algorithmFinished = async (statusLink) => {
    let algorithmFinished = false;
    await $.ajax({
        url: statusLink,
        method: "POST",
        success: (response) => {
            algorithmFinished = !response.data.running || false;
        }
    });

    return algorithmFinished;
}
