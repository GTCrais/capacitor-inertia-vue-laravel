<template>
	<Head>
		<meta type="description" content="Default keywords" head-key="description"/>
	</Head>

	<div class="main-container relative overflow-hidden flex flex-col min-h-screen">
		<div class="bg-gray-100 mb-6">
			<div class="max-w-7xl flex justify-between items-center px-6 mx-auto py-4">
				<div>
					<app-link href="/">App</app-link>
				</div>

				<div class="flex gap-x-8">
					<app-link href="/">Home</app-link>
					<app-link v-if="!user" href="/login">Login</app-link>
					<app-link v-if="user" href="/account">Account</app-link>
					<a v-if="user" href="#" @click.prevent="logout">Logout</a>
				</div>
			</div>
		</div>

		<div class="flex-grow flex">
			<slot></slot>
		</div>
	</div>
</template>

<script>

	import { Head } from "@inertiajs/vue3";
	import { Capacitor } from "@capacitor/core";
	import UrlHelper from "@/js/mixins/url-helper";
	import { Preferences } from "@capacitor/preferences";

	export default {
		mixins: [
			UrlHelper
		],

		components: {
			Head
		},

		props: {
			user: {}
		},

		data: function() {
			return {

			}
		},

		mounted() {

		},

		methods: {
			logout() {
				if (Capacitor.isNativePlatform()) {
					axios.post(this.url('mobile/logout'))
						.then((response) => {
							window.axios.defaults.headers.common['Authorization'] = '';
							Preferences.set({ key: 'auth_token', value: '' });
							this.$inertia.get(this.url(response.data));
						})
						.catch((error) => {
							console.error(error);
							window.axios.defaults.headers.common['Authorization'] = '';
							Preferences.set({ key: 'auth_token', value: '' });
							this.$inertia.get(this.url());
						});
				} else {
					this.$inertia.post(this.url('logout'));
				}
			}
		},

		computed: {

		}
	}
</script>