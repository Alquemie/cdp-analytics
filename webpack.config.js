// Require path.
const { CleanWebpackPlugin } = require('clean-webpack-plugin'); // installed via npm
const CopyPlugin = require('copy-webpack-plugin');
const webpack = require('webpack'); //to access built-in plugins
const path = require( 'path' );

// Configuration object.
const config = {
	// Create the entry points.
	// One for frontend and one for the admin area.
	entry: {
		// frontend and admin will replace the [name] portion of the output config below.
		public: './src/public/js/cdp-public.js',
		// ajs: './src/public/js/segment-ajs.js',
		admin: './src/admin/js/admin-cdp.js'
	},

	// Create the output files.
	// One for each of our entry points.
	output: {
		// [name] allows for the entry object keys to be used as file names.
		filename: 'js/[name].[contenthash].js', // .[contenthash]
		// Specify the path to the JS files.
		path: path.resolve( __dirname, 'dist' )
	},

	// Setup a loader to transpile down the latest and great JavaScript so older browsers
	// can understand it.
	module: {
		rules: [
			{
				// Look for any .js files.
				test: /\.js$/,
				// Exclude the node_modules folder.
				exclude: /node_modules/,
				// Use babel loader to transpile the JS files.
				loader: 'babel-loader'
			}
		]
	},
  plugins: [
    new webpack.ProgressPlugin(),
    new CleanWebpackPlugin({cleanOnceBeforeBuildPatterns: ['**/*.js','**/*.css','**/.DS_Store','!index.php'],}),
		new CopyPlugin({
      patterns: [
        { from: path.resolve( __dirname, 'src/static/' ), to: path.resolve( __dirname, 'dist/static/' ) },
      ],
    }),
    // new HtmlWebpackPlugin({ template: './src/index.html' }),
],
}

// Export the config object.
module.exports = config;
