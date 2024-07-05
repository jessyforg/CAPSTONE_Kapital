/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ["./index.html", "./src/**/*.{js,ts,jsx,tsx}"],
  mode: "jit",
  theme: {
    extend: {
      animation: {
        marquee: "marquee 14s linear infinite",
        marquee2: "marquee2 14s linear infinite",
        marquee3: "marquee2 14s linear infinite",
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
        marquee3: {
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
        "laptop-l": "1536px",
        "desktop-s": "1600px",
        "desktop-m": "1920px",
      },
      colors: {
        trkblack: "#151515",
        ucgreen: "#173218",
        upred: "#751618",
        slublue: "#073066",
        bsuyellow: "#F5D10B",
        ifsugreen: "#083D05",
        dentasync: "#039BE5",
        parapo: "#234182",
        qrx: "#16B2AF",
      },
    },
    fontFamily: {
      poppins: ["Poppins", "sans-serif"],
      satoshi: ["Satoshi", "sans-serif"],
    },
  },
  variants: {
    extend: {
      transform: ["hover", "focus"],
      scale: ["hover", "focus"],
      translate: ["hover", "focus"],
      width: ["hover", "focus"],
      height: ["hover", "focus"],
    },
  },
  plugins: [],
};
