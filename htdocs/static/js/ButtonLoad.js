export const init = () => {
    $("form:not([data-noload])").on("submit", (event) => {
        $(event.target).find("button[type=\"submit\"]").each((index, element) => {
            load(element);
        });
    });

    $(window).on("unload", () => {
        $("button[type=\"submit\"]").each((index, element) => {
            unload(element);
        });
    });
};
export const load = (element) => {
    // Show loading spinner
    $(element).find(".buttonload").removeClass("hidden");

    // Disable button
    $(element).prop("disabled", true);
};
export const unload = (element) => {
    // Hide loading spinner
    $(element).find(".buttonload").addClass("hidden");

    // Enable button
    $(element).prop("disabled", false);
};

export default { init, load, unload };
