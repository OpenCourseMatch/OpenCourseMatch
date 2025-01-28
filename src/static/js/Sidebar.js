let elements = {
    sidebar: null,
    burger: null,
    darkBackground: null
}
let shown = false;

/**
 * Initialize the sidebar
 */
export const init = () => {
    // Click on burger button
    document.getElementById("header-sidebar-open").addEventListener("click", () => {
        open();
    });

    // Click on close button
    document.getElementById("header-sidebar-close").addEventListener("click", () => {
        close();
    });

    // Click on sidebar background
    document.querySelector(".header-sidebar-background").addEventListener("click", () => {
        close();
    });
}

/**
 * Open the sidebar
 */
export const open = () => {
    // Show sidebar
    document.querySelector(".header-sidebar-popup").classList.remove("translate-x-full", "hidden");
    document.querySelector(".header-sidebar-background").classList.remove("hidden");

    // Disable scrolling
    document.querySelector("html").scrollTop = window.scrollY;
    document.body.style.overflow = "hidden";
    document.body.style.position = "relative";
    document.querySelector("html").style.overflow = "hidden";
    document.querySelector("html").style.position = "relative";
}

/**
 * Close the sidebar
 */
export const close = () => {
    // Hide sidebar
    document.querySelector(".header-sidebar-popup").classList.add("translate-x-full");
    setTimeout(() => {
        document.querySelector(".header-sidebar-popup").classList.add("hidden");
    }, 500);
    document.querySelector(".header-sidebar-background").classList.add("hidden");

    // Enable scrolling
    document.body.style.overflow = null;
    document.body.style.position = null;
    document.querySelector("html").style.overflow = null;
    document.querySelector("html").style.position = null;
}

export default { init, open, close };
