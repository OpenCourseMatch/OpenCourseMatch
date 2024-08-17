let translationAjax = null;

export const init = (translationAjaxUrl) => {
    translationAjax = translationAjaxUrl;
}

export const t = async (message, variables) => {
    return await $.ajax({
        url: translationAjax,
        method: "POST",
        data: {
            message: message,
            variables: variables
        },
        success: (response) => {
            return response;
        }
    });
}

export default { init, t };
