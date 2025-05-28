/**
 * PostCSS Configuration
 * This file configures the PostCSS plugins used in the project
 * @see https://postcss.org/
 */

export default {
    plugins: {
        // Process Tailwind CSS directives and utilities
        tailwindcss: {
            // Configuration is loaded from tailwind.config.js
        },
        
        // Add vendor prefixes to CSS rules
        autoprefixer: {
            // Supported browsers are configured in package.json browserslist
        },
    },
};
