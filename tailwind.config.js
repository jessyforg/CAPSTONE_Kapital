/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ["./index.html", "./src/**/*.{js,ts,jsx,tsx}"],
  mode: "jit",
  theme: {
    extend: {
      animation: {
        marquee: "marquee 8s linear infinite",
        marquee2: "marquee2 8s linear infinite",
      },
      keyframes: {
        marquee: {
          "0%": { transform: "translateX(0%)" },
          "100%": { transform: "translateX(-100%)" },
        },
        marquee2: {
          "0%": { transform: "translateX(100%)" },
          "100%": { transform: "translateX(0%)" },
        },
      },
      screens: {
        phone: "360px",
        tablet: "768px",
        "tablet-m": "1024px",
        "laptop-s": "1280px",
        "laptop-m": "1440px",
        "desktop-s": "1600px",
        "desktop-m": "1920px",
      },
      colors: {
        trkblack: "#151515",
      },
    },
    fontFamily: {
      poppins: ["Poppins", "sans-serif"],
      satoshi: ["Satoshi", "sans-serif"],
    },
  },
  plugins: [],
};
