const { defineConfig } = require('@vue/cli-service')
module.exports = defineConfig({
  transpileDependencies: true,
  devServer: {
    proxy: {
      '^/api': {
        target: 'http://localhost:8000', // Your Laravel backend URL
        changeOrigin: true,
        secure: false,
        pathRewrite: { '^/api': '/api' }
      }
    }
  }
})
