
import './bootstrap';
import '../css/app.scss';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';

import DefaultLayout from "@/js/layouts/DefaultLayout.vue";
import { Link } from '@inertiajs/vue3'

createInertiaApp({
	resolve: async (name) => {
		const page = await resolvePageComponent(`./pages/${name}.vue`, import.meta.glob('./pages/**/*.vue'));

		page.default.layout = page.default.layout || DefaultLayout;

		return page;
	},
	setup({ el, App, props, plugin }) {
		createApp({ render: () => h(App, props) })
			.component('i-link', Link)
			.use(plugin)
			.mount(el);
	},

	title: (title) => 'Capacitor App'
});