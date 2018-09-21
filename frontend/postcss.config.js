const tailwindcss = require('tailwindcss')
const autoprefixer = require('autoprefixer')
const purgecss = require('@fullhuman/postcss-purgecss')

class TailwindExtractor {
  static extract(content) {
    return content.match(/[A-Za-z0-9-_:\/]+/g) || [];
  }
}

module.exports = {
    plugins: [
    tailwindcss('./src/tailwind.js'),
    autoprefixer(),
    process.env.NODE_ENV === 'production'
        ? purgecss({
            content: [
                './src/**/*.html',
                './src/**/*.vue',
                './public/index.html',
            ],

            extractors: [{
                extractor: TailwindExtractor,
                extensions: ['html', 'vue']
            }]
        }) : null
    ]
}
