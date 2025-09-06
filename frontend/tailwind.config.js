module.exports = {
    content: [
      "./src/**/*.{html,js,vue}",
    ],
    theme: {
      extend: {
        colors: {
          'beautiful-purple': '#7B61FF',
          'light-purple': '#A78BFA',
          'light-bg': '#F3F4F6',
          'dark-gray': '#111827',
          'secondary-gray': '#4B5563',
          'success-green': '#10B981',
          'warning-red': '#EF4444',
        },
        backgroundImage: {
          'gradient-card': 'linear-gradient(145deg, hsl(240 10% 7% / 0.8), hsl(240 10% 10% / 0.9))',
        },
      },
    },
    plugins: [],
    darkMode: 'class',
  }