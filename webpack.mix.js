const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js')
	mix.js('resources/js/category.js', 'public/js')
	mix.js('resources/js/sub_category.js', 'public/js')
	mix.js('resources/js/sub_sub_category.js', 'public/js')
	mix.js('resources/js/brand.js', 'public/js')
	mix.js('resources/js/product.js', 'public/js')
	mix.js('resources/js/campaign.js', 'public/js')
	mix.js('resources/js/currency.js', 'public/js')
	mix.js('resources/js/payment.js', 'public/js')
	mix.js('resources/js/social_setting.js', 'public/js')
	mix.js('resources/js/shop_setting.js', 'public/js')
	mix.js('resources/js/pages.js', 'public/js')
	mix.js('resources/js/messenger.js', 'public/js')
	mix.js('resources/js/role.js', 'public/js')
	mix.js('resources/js/shipping.js', 'public/js')
	mix.js('resources/js/email.js', 'public/js')
	mix.js('resources/js/admin.js', 'public/js')
	mix.js('resources/js/order.js', 'public/js')
	mix.js('resources/js/dashboard.js', 'public/js')
	mix.js('resources/js/customer.js', 'public/js')
	mix.js('resources/js/report.js', 'public/js')
	mix.js('resources/js/front.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css');
