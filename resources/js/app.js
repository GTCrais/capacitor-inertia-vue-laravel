
import '../css/app.scss';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import Bootstrap from "@/js/bootstrap";

import DefaultLayout from "@/js/layouts/DefaultLayout.vue";
import AppLink from "@/js/shared/AppLink.vue";

Bootstrap.setupAxios()
	.then(() => {
		createInertiaApp({
			resolve: async (name) => {
				const page = await resolvePageComponent(`./pages/${name}.vue`, import.meta.glob('./pages/**/*.vue'));

				page.default.layout = page.default.layout || DefaultLayout;

				return page;
			},
			setup({ el, App, props, plugin }) {
				createApp({ render: () => h(App, props) })
					.component('app-link', AppLink)
					.use(plugin)
					.mount(el);
			},

			title: (title) => 'Capacitor App'
		});
	});