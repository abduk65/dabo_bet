module.exports = {
    plugins: [
        require('autoprefixer'),
        require('@fullhuman/postcss-purgecss')({
            content: [
                './resources/**/*.html',
                './resources/**/*.js',
                './resources/**/*.vue',
            ],
            safelist: ['html', 'body'] // Add any classes you want to keep
        }),
    ],
};
