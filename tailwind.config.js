/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./assets/**/*.js",
    "./templates/**/*.html.twig",
    "./src/Twig/Components/**/*.php",
    "./src/Controller/**/*.php",
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
        red: "#ff0000",
        reddark: "#850000",
        green: "#00c500",
        greendark: "#008100",
        orange: "#ff8000",
        orangedark: "#9d4c00",
      },
    },
  },
  plugins: [
    require('flowbite/plugin')
  ]
}
