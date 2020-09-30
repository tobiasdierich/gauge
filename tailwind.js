module.exports = {
    future: {
        removeDeprecatedGapUtilities: true,
        purgeLayersByDefault: true,
    },
    purge: [
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue'
    ],
    theme: {
        extend: {},
        fontFamily: {
            'sans': 'Inter, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji"'
        }
    },
    variants: {},
    plugins: [],
}
