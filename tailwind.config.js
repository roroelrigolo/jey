/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./assets/**/*.js",
    "./templates/**/*.html.twig",
    "./src/Twig/Components/**/*.php",
    "./node_modules/flowbite/**/*.js"
  ],
  theme: {
    extend: {
      colors: {
        primary: "#2113B0",
        primaryhover: "rgba(33, 19, 176, 0.4)",
        secondary: "#FFD700",
        tertiary: "#398DFF",
        white: "#F5F5F5",
        fullwhite: "#ffffff",
        black: "#131313",
        gray: "#D3D3D3FF",
      },
    },
  },
  plugins: [
    require('flowbite/plugin')
  ]
}
