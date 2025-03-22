export const init = () => {
    // Start loading animation when submitting a form
    document.querySelectorAll("form:not([data-noload])").forEach((form) => {
        document.addEventListener("submit", () => {
            form.querySelectorAll("button[type=\"submit\"]").forEach((submitButton) => {
                load(submitButton);
            });
        });
    });

    // Stop loading animation when leaving the page
    window.addEventListener("unload", () => {
        document.querySelectorAll("button[type=\"submit\"]").forEach((submitButton) => {
            unload(submitButton);
        });
    });
};
export const load = (element) => {
    // Show loading spinner
    element.querySelectorAll(".buttonload").forEach((loadingAnimation) => {
        loadingAnimation.classList.remove("hidden");
    });

    // Disable button
    element.disabled = true;
};
export const unload = (element) => {
    // Hide loading spinner
    element.querySelectorAll(".buttonload").forEach((loadingAnimation) => {
        loadingAnimation.classList.add("hidden");
    });

    // Enable button
    element.disabled = false;
};

export default { init, load, unload };
