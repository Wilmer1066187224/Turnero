import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'
import vue from '@vitejs/plugin-vue'

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/js/app.js'],
            refresh: true,
        }),
        vue(),
    ],
    server: {
        host: '192.168.101.70', // 🔥 TU IP
        port: 5173,
        strictPort: true,
        cors: true,
        hmr: {
            host: '192.168.101.70' // 🔥 ESTO ELIMINA [::1] si cambia la ip debo cambiar aqui tambien 
            
        }
    }
})