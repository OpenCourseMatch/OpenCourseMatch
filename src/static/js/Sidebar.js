let innerWidth = window.innerWidth;

/**
 * Initialize the sidebar
 */
export const init = (breakpoint = null) => {
    updateSidebarState(breakpoint);

    window.addEventListener("resize", () => {
        const previousActive = innerWidth >= breakpoint;
        const newActive = window.innerWidth >= breakpoint;
        innerWidth = window.innerWidth;
        if(previousActive !== newActive) {
            updateSidebarState(breakpoint);
        }
    });

    document.querySelectorAll(".sidebar-toggle").forEach((element) => {
        element.addEventListener("click", () => {
            toggle();
        });
    });

    window.addEventListener("beforeunload", () => {
        saveScrollPosition();
    });

    loadScrollPosition();
}

const updateSidebarState = (breakpoint = null) => {
    if(breakpoint !== null) {
        const sidebarActive = window.innerWidth >= breakpoint ? "true" : "false";
        document.querySelectorAll("[data-sidebar-active]").forEach((element) => {
            element.setAttribute("data-sidebar-active", sidebarActive);
        });
    }

    updateClasses();
}

const updateClasses = () => {
    const sidebar = document.querySelector("[data-sidebar-active]");
    const isActive = sidebar.hasAttribute("data-sidebar-active") && sidebar.getAttribute("data-sidebar-active") === "true";

    document.querySelectorAll("[data-sidebar-active-classes]").forEach((element) => {
        element.getAttribute("data-sidebar-active-classes").split(" ").forEach((cls) => {
            if(isActive) {
                element.classList.add(cls);
            } else {
                element.classList.remove(cls);
            }
        });
    });
    document.querySelectorAll("[data-sidebar-inactive-classes]").forEach((element) => {
        element.getAttribute("data-sidebar-inactive-classes").split(" ").forEach((cls) => {
            if(isActive) {
                element.classList.remove(cls);
            } else {
                element.classList.add(cls);
            }
        });
    });
}

export const toggle = () => {
    const sidebar = document.querySelector("[data-sidebar-active]");
    const isActive = sidebar.hasAttribute("data-sidebar-active") && sidebar.getAttribute("data-sidebar-active") === "true";

    if(isActive) {
        sidebar.setAttribute("data-sidebar-active", "false");
    } else {
        sidebar.setAttribute("data-sidebar-active", "true");
    }

    updateClasses();
}

const saveScrollPosition = () => {
    const sidebarContent = document.querySelector("[data-sidebar-active]");
    if(!sidebarContent) {
        return;
    }

    sessionStorage.setItem("__SIDEBAR__SCROLL_POS", sidebarContent.scrollTop.toString());
}

const loadScrollPosition = () => {
    const sidebarContent = document.querySelector("[data-sidebar-active]");
    if(!sidebarContent) {
        return;
    }

    const scrollPosition = sessionStorage.getItem("__SIDEBAR__SCROLL_POS");
    if(scrollPosition) {
        sidebarContent.scrollTop = parseInt(scrollPosition);
    }
}

export default { init, toggle };
