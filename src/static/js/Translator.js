let translationAjax = null;

export const init = (translationAjaxUrl) => {
    translationAjax = translationAjaxUrl;
}

export const t = async (message, variables) => {
    const response = await fetch(translationAjax, {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({
            message: message,
            variables: variables
        })
    });
    return await response.text();
}

export default { init, t };
