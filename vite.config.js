import { defineConfig } from 'vite';
import path from 'path';

export default defineConfig({
    build: {
        // Output compiled assets to standard CodeIgniter assets directory
        outDir: 'assets/build',
        // Generate manifest.json so we can potentially parse it in PHP (optional but good practice)
        manifest: true,
        rollupOptions: {
            input: 'src/main.js',
            output: {
                entryFileNames: `[name].js`,
                chunkFileNames: `[name].js`,
                assetFileNames: `[name].[ext]`
            }
        },
        // Don't empty the dir completely if it deletes other things, but here it is a specific build dir
        emptyOutDir: true,
    },
    server: {
        // For dev mode, we might want to proxy or just run on a different port
        port: 3000,
        strictPort: true,
    }
});
