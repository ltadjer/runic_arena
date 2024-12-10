/** @type {import('tailwindcss').Config} */

const defaultTheme = require('tailwindcss/defaultTheme');
module.exports = {
  content: [
    "./assets/**/*.js",
    "./templates/**/*.html.twig",
  ],
  theme: {
    extend: {
      colors: {
        'black': '#242426',
        'dark': '#76290B',
        'medium': '#C2A896',
        'light': '#FFF3EA',
      },
      fontFamily: {
        'main': ["Edu AU VIC WA NT Pre", "cursive", defaultTheme.fontFamily.sans],
      },
      fontSize: {
        xxs: '.5rem',
        xs: '.75rem',
        sm: '1rem',
        base: '1.2rem',
        lg: '1.3rem',
        xl: '1.5rem',
        '2xl': '1.8rem',
        '3xl': '2rem',
        '4xl': '3rem',
        '5xl': '4rem',
        '6xl': '4.5rem',
      },
      boxShadow: {
        'default': "15px 15px 15px #00000025",
      },
    }
  },
  plugins: [
    require('@tailwindcss/forms'),
  ],
}
