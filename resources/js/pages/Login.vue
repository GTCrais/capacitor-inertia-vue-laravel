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
							@input="resetError('email')"
						/>

						<div class="text-sm text-red-500 -mb-3" v-if="form.errors.email">
							{{ form.errors.email }}
						</div>
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
							@input="resetError('password')"
						/>

						<div class="text-sm text-red-500 -mb-3" v-if="form.errors.password">
							{{ form.errors.password }}
						</div>
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
				})
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

				this.form.post(window.app_base_url + '/login', { preserveScroll: true });
			},

			resetError(field) {
				this.form.clearErrors(field, 'general');
			}
		},

		computed: {

		}
	}
</script>