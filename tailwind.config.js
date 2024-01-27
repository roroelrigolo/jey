/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./components/**/*.js",
    "./templates/**/*.html.twig",
  ],
  theme: {
    extend: {
      colors: {
        primary: "#2113B0",
        primaryhover: "rgba(33, 19, 176, 0.4)",
        secondary: "#FFD700",
        white: "#F5F5F5",
        black: "#131313",
        gray: "#D3D3D3FF",
      },
    },
  },
  plugins: [],
}
