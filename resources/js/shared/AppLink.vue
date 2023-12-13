<template>
	<inertia-link :href="url(href)">
		<slot></slot>
	</inertia-link>
</template>

<script>
	import { Link as InertiaLink } from '@inertiajs/vue3'
	import UrlHelper from "@/js/mixins/url-helper";

	export default {
		mixins: [UrlHelper],

		components: {
			InertiaLink
		},

		props: {
		    href: {}
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

		}
	}
</script>