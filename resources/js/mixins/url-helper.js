export default {
	methods: {
		url(path = null) {
			// Remove leading / trailing slashes
			let url = window.app_base_url.replace(/^\/|\/$/g, '');

			if (path) {
				// Remove leading / trailing slashes, then concatenate base url with path using '/' as glue
				url += '/' + path.replace(/^\/|\/$/g, '');
			}

			return url;
		}
	}
}