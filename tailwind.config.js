import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
        "./resources/js/**/*.vue",
    ],

    theme: {
        extend: {
            fontFamily: {
                primary: ["Inter", "sans-serif"],
                secondary: ["Poppins", "sans-serif"],
                accent: ["Geologica", "sans-serif"],
                display: ["Montserrat", "sans-serif"],
                sans: ["Inter", "system-ui", "sans-serif"],
            },
            fontSize: {
                hero: ["clamp(2.5rem, 5vw, 4rem)", { lineHeight: "1.1" }],
            },
            letterSpacing: {
                tight: "-0.02em",
            },
        },
    },

    plugins: [forms],
};
