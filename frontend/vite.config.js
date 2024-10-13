import { fileURLToPath, URL } from 'node:url'

import { defineConfig, loadEnv } from 'vite'
import vue from '@vitejs/plugin-vue'
import AutoImport from 'unplugin-auto-import/vite'
import Components from 'unplugin-vue-components/vite'
import { ElementPlusResolver } from 'unplugin-vue-components/resolvers'

const env = loadEnv('all', process.cwd());

// https://vitejs.dev/config/
export default defineConfig({
  build: {
    outDir: '../public',
  },
  plugins: [
    vue(),
    AutoImport({
      resolvers: [ElementPlusResolver()],
    }),
    Components({
      resolvers: [ElementPlusResolver()],
    }),
  ],
  resolve: {
    alias: {
      '@': fileURLToPath(new URL('./src', import.meta.url))
    }
  },
  server: {
    host: true,
    port: env.VITE_ASSET_PORT,
    strictPort: true,
    hmr: {
      host: env.VITE_ASSET_HOST,
      port: env.VITE_ASSET_PORT,
    },
  },
})
