export const ButtonLoad = {
    init: () => {
        ButtonLoad.setupListeners();
    },

    setupListeners: () => {
        $("form:not([data-noload])").on("submit", (event) => {
            $(event.target).find("button[type=\"submit\"]").each((index, element) => {
                ButtonLoad.load(element);
            });
        });

        $(window).on("unload", () => {
            $("button[type=\"submit\"]").each((index, element) => {
                ButtonLoad.unload(element);
            });
        });
    },

    load: (element) => {
        // Show loading spinner
        $(element).find(".buttonload").removeClass("hidden");

        // Disable button
        $(element).prop("disabled", true);
    },

    unload: (element) => {
        // Hide loading spinner
        $(element).find(".buttonload").addClass("hidden");

        // Enable button
        $(element).prop("disabled", false);
    }
}

export default ButtonLoad;
