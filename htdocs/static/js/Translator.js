var t;

const initTranslator = (translationAjax) => {
    t = async (message, variables) => {
        return await $.ajax({
            url: translationAjax,
            method: "POST",
            data: {
                message: message,
                variables: variables
            },
            success: (response) => {
                console.log(response);
                return response;
            }
        });
    };
}
