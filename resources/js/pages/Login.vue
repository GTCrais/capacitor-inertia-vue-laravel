<template>
	<Head>
		<title>Login</title>
		<meta type="description" content="Prijava keywords" head-key="description"/>
	</Head>

	<div class="grow mx-auto max-w-7xl flex w-full px-6">
		<div class="flex justify-center items-start w-full">
			<div class="sm:mx-auto sm:w-full sm:max-w-[480px] bg-white px-6 pt-8 pb-12 shadow sm:rounded-lg sm:px-12">
				<h2 class="text-center text-2xl font-semibold leading-9 tracking-tight text-gray-900 mb-6">Login</h2>

				<form @submit.prevent="login">
					<div class="mb-6">
						<div class="text-sm">
							test@example.com
						</div>

						<input
							id="email"
							name="email"
							type="text"
							placeholder="Email"
							class="block w-full rounded-md border-0 py-1.5 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400
								focus:ring-2 focus:ring-inset focus:ring-orange-300 sm:text-sm sm:leading-6 outline-0"
							maxlength="150"
							v-model="form.email"
						/>
					</div>

					<div class="mb-6">
						<div class="text-sm">
							password
						</div>

						<input
							id="password"
							name="password"
							type="password"
							placeholder="Password"
							class="block w-full rounded-md border-0 py-1.5 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400
								focus:ring-2 focus:ring-inset focus:ring-orange-300 sm:text-sm sm:leading-6 outline-0"
							maxlength="150"
							v-model="form.password"
						/>
					</div>

					<div v-if="form.errors.general" class="-mt-3 mb-3 text-red-500 text-center">
						{{ form.errors.general }}
					</div>

					<div class="flex items-center justify-between mb-6">
						<button
							type="submit"
							class="rounded-md bg-orange-400 hover:bg-orange-500 px-5 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm focus-visible:outline
							focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-orange-400"
						>
							Login
						</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</template>

<script>
	import { Head, useForm } from '@inertiajs/vue3'
	import { Capacitor } from '@capacitor/core';

	export default {
		components: {
			Head
		},

		props: {
			status: null
		},

		data: function() {
			return {
				form: useForm({
					email: null,
					password: null
				}),
				generalError: null
			};
		},

		created() {

		},

		mounted() {

		},

		methods: {
			login() {
				if (this.form.processing) {
					return;
				}

				this.form.clearErrors('general');

				if (Capacitor.isNativePlatform()) {
					this.form.processing = true;

					axios.post(window.app_base_url + '/mobile/login', { email: this.form.email, password: this.form.password})
						.then((token) => {
							console.error(token);
							this.form.processing = false;
						})
						.catch((error) => {
						    console.error(error);
							this.form.setError('general', 'Incorrect login credentials');
							this.form.processing = false;
						});
				} else {
					this.form.post(window.app_base_url + '/login', { preserveScroll: true });
				}
			}
		},

		computed: {

		}
	}
</script>