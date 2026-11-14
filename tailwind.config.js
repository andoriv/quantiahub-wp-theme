/** @type {import('tailwindcss').Config} */
export default {
  content: [
    './*.php',
    './app/**/*.php',
    './inc/**/*.php',
    './features/**/*.php',
    './template-parts/**/*.php',
    './page-templates/**/*.php',
    './assets/js/**/*.js',
    './src/**/*.{js,ts}',
  ],
  theme: {
    extend: {
      colors: {
        qh: {
          DEFAULT: "#00AC69",
          50: "#EAFBF3",
          100: "#D2F6E6",
          200: "#A5EDCD",
          300: "#6FE0AF",
          400: "#2ECB8B",
          500: "#00AC69",
          600: "#028B56",
          700: "#046B44",
          800: "#054F33",
          900: "#04321F",
        },
        ink: "#0B1F17",
        mist: "#F4F8F6",
      },
      fontSize: {
        "2xl": [
          "clamp(1.25rem, 1.1rem + 0.45vw, 1.5rem)",
          { lineHeight: "1.3" },
        ],
        "3xl": [
          "clamp(1.5rem, 1.25rem + 0.75vw, 1.875rem)",
          { lineHeight: "1.2" },
        ],
        "4xl": [
          "clamp(1.75rem, 1.35rem + 1.25vw, 2.25rem)",
          { lineHeight: "1.12" },
        ],
        "5xl": ["clamp(2rem, 1.35rem + 2vw, 3rem)", { lineHeight: "1" }],
        "6xl": [
          "clamp(2.25rem, 1.35rem + 2.8vw, 3.75rem)",
          { lineHeight: "1" },
        ],
        "7xl": ["clamp(2.5rem, 1.25rem + 3.9vw, 4.5rem)", { lineHeight: "1" }],
        "8xl": ["clamp(3rem, 1.25rem + 5.4vw, 6rem)", { lineHeight: "1" }],
      },
      fontFamily: {
        display: ["Poppins", "system-ui", "sans-serif"],
        sans: ["Ubuntu", "system-ui", "sans-serif"],
      },
    },
  },
  plugins: [],
};
