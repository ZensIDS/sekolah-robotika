/** @type {import('tailwindcss').Config} */
export default {
  content: ['./resources/**/*.blade.php', './resources/**/*.js'],
  theme: {
    extend: {
      colors: { brand: { navy: '#002c86', deep: '#001a52', sky: '#1ba8f0', sun: '#fccc0d' } },
      fontFamily: { sans: ['Poppins', 'ui-sans-serif', 'system-ui', 'sans-serif'] },
      keyframes: { float: { '0%,100%': { transform: 'translateY(0)' }, '50%': { transform: 'translateY(-14px)' } } },
      animation: { float: 'float 5s ease-in-out infinite' },
    },
  },
  plugins: [],
}