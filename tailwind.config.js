/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        "./project/frontend/**/*.php"
    ],
    theme: {
        colors: {
            transparent: 'transparent',
            current: 'currentColor',
            "background": {
                DEFAULT: "#ffffff",
                "header": "#ffffff",
                "footer": "#ffffff",
            },
            "font": {
                DEFAULT: "#000000",
                "light": "#aaaaaa",
                "invert": "#ffffff",
                "header": "#000000",
                "footer": "#666666"
            },
            "gray": {
                DEFAULT: "#aaaaaa",
                "light": "#dddddd",
                "dark": "#666666",
                "effect": "#666666",
                "font": "#ffffff"
            },
            "primary": {
                DEFAULT: "#2f7cff",
                "effect": "#275fda",
                "font": "#ffffff"
            },
            "secondary": {
                DEFAULT: "#ff2f7c",
                "effect": "#da275f",
                "font": "#ffffff"
            },
            "danger": {
                DEFAULT: "#f45c4a",
                "effect": "#e14332",
                "font": "#ffffff"
            },
            "warning": {
                DEFAULT: "#f3c033",
                "effect": "#d9ac1e",
                "font": "#ffffff"
            },
            "safe": {
                DEFAULT: "#4dbb5f",
                "effect": "#13942c",
                "font": "#ffffff"
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
                    "border": "#f3c033ff",
                    "background": "#f3c03344"
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
        screens: {
            "sm": "640px",
            "md": "960px",
            "lg": "1440px"
        },
        extend: {
            spacing: {
                "content-padding-sm": "2.5%",
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
            pattern: /^(bg|border|text|fill)-(primary|secondary|gray|backgorund|danger|warning|safe|current)$/,
            variants: ["hover", "focus", "disabled", "disabled:hover"]
        },
        {
            pattern: /^(bg|border|text|fill)-(primary|secondary|gray|background|danger|warning|safe)-(DEFAULT|effect|font)$/,
            variants: ["hover", "focus", "disabled", "disabled:hover"]
        }
    ]
}
