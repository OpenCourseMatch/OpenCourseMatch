/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        "./src/templates/**/*.php"
    ],
    theme: {
        colors: {
            transparent: 'transparent',
            current: 'currentColor',
            "background": {
                DEFAULT: "#f5fff9",
                "header": "#f5fff9",
                "footer": "#f5fff9",
            },
            "font": {
                DEFAULT: "#333333",
                "light": "#666666",
                "invert": "#fffdf7",
                "header": "#333333",
                "footer": "#666666"
            },
            "gray": {
                DEFAULT: "#999999",
                "light": "#dddddd",
                "dark": "#666666",
                "effect": "#666666",
                "font": "#fffdf7"
            },
            "primary": {
                DEFAULT: "#00a34c",
                "effect": "#007236",
                "font": "#f5fff9"
            },
            "secondary": {
                DEFAULT: "#f1c400",
                "effect": "#b89600",
                "font": "#333333"
            },
            "danger": {
                DEFAULT: "#f45c4a",
                "effect": "#e14332",
                "font": "#feeeec"
            },
            "warning": {
                DEFAULT: "#f1c400",
                "effect": "#b89600",
                "font": "#333333"
            },
            "safe": {
                DEFAULT: "#4dbb5f",
                "effect": "#13942c",
                "font": "#f0f9f2"
            },
            "info": {
                DEFAULT: "#275fda",
                "effect": "#1a3e8c",
                "font": "#f0f5ff"
            },
            "infomessage": {
                "none": {
                    "border": "#444444ff",
                    "background": "#44444444"
                },
                "info": {
                    "border": "#275fdaff",
                    "background": "#275fda33"
                },
                "warning": {
                    "border": "#f1c400",
                    "background": "#f1c40044"
                },
                "error": {
                    "border": "#f45c4aff",
                    "background": "#f45c4a44"
                },
                "success": {
                    "border": "#4dbb5fff",
                    "background": "#4dbb5f44"
                }
            }
        },
        fontFamily: {
            "sans": ["OpenSans", "Segoe UI", "Helvetica Neue", "ui-sans-serif", "system-ui", "sans-serif", "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji"],
            "mono": ["monospace"]
        },
        screens: {
            "sm": "640px",
            "md": "960px",
            "lg": "1440px"
        },
        extend: {
            spacing: {
                "content-padding-sm": "5%",
                "content-padding-md": "10%",
                "content-padding-lg": "15%",
                "header-logo-height": "5vh",
                "header-sidebar-width-sm": "80%",
                "header-sidebar-width-md": "55%",
                "header-sidebar-width-lg": "35%",
                "header-sidebar-padding": "2.5%",
            },
            zIndex: {
                "100": "100",
                "200": "200",
                "300": "300",
                "400": "400",
                "500": "500",
                "600": "600",
                "700": "700",
                "800": "800",
                "900": "900"
            }
        }
    },
    plugins: [],
    safelist: [
        "w-full", "h-full",
        "flex", "inline-flex", "justify-around", "items-center", "gap-1",
        "text-sm", "font-bold", "data-[required]:after:content-['*'] data-[required]:after:text-primary",
        "px-2", "py-1", "px-4", "py-2",
        "border", "outline-primary", "rounded", "placeholder:text-font-light",
        "disabled:opacity-75",
        {
            pattern: /^(bg|border)-infomessage-(info|warning|error|success)-(border|background)$/
        },
        {
            pattern: /^(bg|border|text|fill)-(primary|secondary|gray|backgorund|danger|warning|safe|info|current)$/,
            variants: ["hover", "focus", "disabled", "disabled:hover"]
        },
        {
            pattern: /^(bg|border|text|fill)-(primary|secondary|gray|background|danger|warning|safe|info)-(DEFAULT|effect|font)$/,
            variants: ["hover", "focus", "disabled", "disabled:hover"]
        },
        "dt-container", "dt-layout-row", "dt-search", "dt-paging", "dataTable",
        "dt-paging-button", "current", "disabled", "previous", "next"
    ]
}
