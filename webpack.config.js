const defaultConfig = require( '@wordpress/scripts/config/webpack.config' );
module.exports = {
	...defaultConfig,
	entry: {
		'oik-css': './src/oik-css',
		'oik-geshi': './src/oik-geshi'
	},
};
