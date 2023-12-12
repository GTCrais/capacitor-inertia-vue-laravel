import { defineConfig, loadEnv } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import path from "path";

export default ({ mode }) => {
	process.env = {...process.env, ...loadEnv(mode, process.cwd())};

	return defineConfig({
		resolve: {
			alias: {
				'@': path.resolve(__dirname, './resources')
			}
		},
		build: {
			rollupOptions: {
				output: {
					inlineDynamicImports: true
				},
			},
		},
		plugins: [
			laravel({
				input: [
					'resources/js/app.js',
				],
				refresh: true,
			}),
			vue({
				template: {
					transformAssetUrls: {
						// The Vue plugin will re-write asset URLs, when referenced
						// in Single File Components, to point to the Laravel web
						// server. Setting this to `null` allows the Laravel plugin
						// to instead re-write asset URLs to point to the Vite
						// server instead.
						base: null,

						// The Vue plugin will parse absolute URLs and treat them
						// as absolute paths to files on disk. Setting this to
						// `false` will leave absolute URLs un-touched so they can
						// reference assets in the public directory as expected.
						includeAbsolute: false,
					},
				},
			})
		],
		server: {
			https: {
				key: process.env.VITE_DEV_SERVER_KEY,
				cert: process.env.VITE_DEV_SERVER_CERT
			},
			host: process.env.VITE_DEV_SERVER_HOST
		},
	});
}