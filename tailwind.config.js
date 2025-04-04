/** @type {import('tailwindcss').Config} */
export default {
  content: [
    './resources/**/*.blade.php',
    './resources/**/*.js',
    './resources/**/*.vue',
  ],
  theme: {
    extend: {
      colors: {
        sage: {
          50: '#f8faf8',
          100: '#f0f4f1',
          200: '#dce5dd',
          300: '#bccebe',
          400: '#9ab39c',
          500: '#759378',
          600: '#557657',
          700: '#445e46',
          800: '#374b38',
          900: '#2e3e2f',
        },
      },
      fontFamily: {
        serif: ['Cormorant Garamond', 'serif'],
        sans: ['Montserrat', 'sans-serif'],
      },
      animation: {
        'float': 'float 6s ease-in-out infinite',
        'fade-up': 'fadeUp 0.5s ease-out',
      },
      keyframes: {
        float: {
          '0%, 100%': { transform: 'translateY(0)' },
          '50%': { transform: 'translateY(-20px)' },
        },
        fadeUp: {
          '0%': { opacity: '0', transform: 'translateY(20px)' },
          '100%': { opacity: '1', transform: 'translateY(0)' },
        }
      },
    },
  },
  plugins: [
    async () => {
      const forms = await import('@tailwindcss/forms');
      const aspectRatio = await import('@tailwindcss/aspect-ratio');
      return [forms.default, aspectRatio.default];
    },
  ],
}
