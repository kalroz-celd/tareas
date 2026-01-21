import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: "class",

    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
    ],
    safelist: [
        "bg-slate-100",
        "text-slate-700",
        "dark:bg-slate-800",
        "dark:text-slate-200",
        "bg-sky-100",
        "text-sky-700",
        "dark:bg-sky-900/40",
        "dark:text-sky-200",
        "bg-emerald-100",
        "text-emerald-700",
        "dark:bg-emerald-900/40",
        "dark:text-emerald-200",
        "bg-rose-100",
        "text-rose-700",
        "dark:bg-rose-900/40",
        "dark:text-rose-200",
        "bg-amber-100",
        "text-amber-800",
        "dark:bg-amber-900/30",
        "dark:text-amber-200",
        "bg-orange-100",
        "text-orange-800",
        "dark:bg-orange-900/30",
        "dark:text-orange-200",
        "bg-red-100",
        "text-red-800",
        "dark:bg-red-900/30",
        "dark:text-red-200",
        "bg-red-200",
        "text-red-900",
        "dark:bg-red-900/50",
        "dark:text-red-100",
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ["Figtree", ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],
};
