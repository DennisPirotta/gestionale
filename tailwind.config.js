/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
  ],
    safelist: [
        {
            pattern: /(bg|text)-(red|green|blue|yellow|indigo|orange|pink)-(100|300|400|800|900)/,
        }
    ],
  theme: {
    extend: {},
  },
  plugins: [require('flowbite/plugin')],
}
