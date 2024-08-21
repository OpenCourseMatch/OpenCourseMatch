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
    $("#header-sidebar-open").on("click", () => {
        open();
    });

    // Click on close button
    $("#header-sidebar-close").on("click", () => {
        close();
    });

    // Click on sidebar backgrund
    $(".header-sidebar-background").on("click", () => {
        close();
    });
}

/**
 * Open the sidebar
 */
export const open = () => {
    // Show sidebar
    $(".header-sidebar-popup").removeClass("translate-x-full");
    $(".header-sidebar-popup").removeClass("hidden");
    $(".header-sidebar-background").removeClass("hidden");

    // Disable scrolling
    document.querySelector('html').scrollTop = window.scrollY;
    document.body.style.overflow = 'hidden';
    document.body.style.position = 'relative';
    document.querySelector('html').style.overflow = 'hidden';
    document.querySelector('html').style.position = 'relative';
}

/**
 * Close the sidebar
 */
export const close = () => {
    // Hide sidebar
    $(".header-sidebar-popup").addClass("translate-x-full");
    setTimeout(() => {
        $(".header-sidebar-popup").addClass("hidden");
    }, 500);
    $(".header-sidebar-background").addClass("hidden");

    // Enable scrolling
    document.body.style.overflow = null;
    document.body.style.position = null;
    document.querySelector('html').style.overflow = null;
    document.querySelector('html').style.position = null;
}

export default {
    init,
    open,
    close
};
