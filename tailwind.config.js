/**
 * Tailwind CSS Configuration
 * This file customizes the Tailwind CSS framework for the project
 * @see https://tailwindcss.com/docs/configuration
 */

import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    // Configure the files Tailwind should scan for classes
    content: [
        // Laravel framework pagination views
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        // Compiled blade views
        './storage/framework/views/*.php',
        // Application blade views
        './resources/views/**/*.blade.php',
    ],

    // Theme customization
    theme: {
        extend: {
            // Custom font configuration
            fontFamily: {
                sans: [
                    'Figtree',     // Primary font
                    ...defaultTheme.fontFamily.sans  // Fallback fonts
                ],
            },
            // Add custom colors, spacing, or other theme values here
        },
    },

    // Active plugins
    plugins: [
        forms,  // Adds better base styles for form elements
    ],

    // Future configuration options
    future: {
        // Enable newer Tailwind features as they become available
        hoverOnlyWhenSupported: true,
    },
};
