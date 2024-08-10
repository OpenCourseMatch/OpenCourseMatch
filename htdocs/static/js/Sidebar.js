export const Sidebar = {
    elements: {
        sidebar: null,
        burger: null,
        darkBackground: null
    },
    shown: false,

    /**
     * Initialize the sidebar
     */
    init: () => {
        Sidebar.setupListeners();
    },

    /**
     * Set up the event listeners for the sidebar
     */
    setupListeners: () => {
        // Click on burger button
        $("#header-sidebar-open").on("click", () => {
            Sidebar.openSidebar();
        });

        // Click on close button
        $("#header-sidebar-close").on("click", () => {
            Sidebar.closeSidebar();
        });

        // Click on sidebar backgrund
        $(".header-sidebar-background").on("click", () => {
            Sidebar.closeSidebar();
        });
    },

    openSidebar: () => {
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
    },

    closeSidebar: () => {
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
}

export default Sidebar;
