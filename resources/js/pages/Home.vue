<template>
	<Head>
		<meta type="description" content="Home keywords" head-key="description"/>
	</Head>

	<div class="grow mx-auto max-w-7xl flex w-full px-6">
		Homepage<br>
		User: {{ email }}
	</div>
</template>

<script>
	import { Head } from "@inertiajs/vue3";

	export default {
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

		},

		computed: {
			email() {
				return (this.user?.email || 'Not Authenticated');
			}
		}
	}

</script>