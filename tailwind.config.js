/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ['landing-page.html'],
  theme: {
    container: {
      center: true,
      padding: '16px',
    },
    extend: {
      colors: {
        merah: '#EB002B',
        dongker: '#142445',
        abu: '#AEAEB0',

      },
      screens: {
        '2xl' : '1320px',
      },
    },
  },
  plugins: [],
}

