require('./vue-assets');
require('drift-zoom/dist/drift-basic.min.css')

// start vuex

import Vuex from 'vuex';

Vue.use(Vuex);

import storeData from "./store/index";

const store = new Vuex.Store(
    storeData
);

// end vuex
Vue.component('home-slider', require('./components/front/slider/Slider.vue').default);
Vue.component('home-category', require('./components/front/category/HomeCategory.vue').default);
Vue.component('category-subcategory', require('./components/front/category/CategorySubCategory.vue').default);
Vue.component('category-product', require('./components/front/category/CategoryProduct.vue').default);
Vue.component('home-offers', require('./components/front/offer/HomeOffer.vue').default);
Vue.component('hot-deal', require('./components/front/product/HotDeal.vue').default);
// cart
Vue.component('cart', require('./components/front/cart/Cart.vue').default);
Vue.component('checkout-cart', require('./components/front/cart/CheckoutCart.vue').default);

Vue.component('search-box', require('./components/front/search/SearchBox.vue').default);
Vue.component('search-product', require('./components/front/product/SearchProduct.vue').default);

Vue.component('user-subscribe', require('./components/front/subscribe/Subscribe.vue').default);
Vue.component('all-offers', require('./components/front/offer/AllOffer.vue').default);
Vue.component('offers-product', require('./components/front/offer/OfferProduct.vue').default);
Vue.component('level-three-category', require('./components/front/category/LevelThreeCategory.vue').default);
Vue.component('sub-category-product', require('./components/front/product/SubCategoryProduct.vue').default);
Vue.component('sub-sub-category-product', require('./components/front/product/SubSubCategoryProduct.vue').default);
Vue.component('product-details', require('./components/front/product/ProductDetails.vue').default);


// profile
Vue.component('order-history', require('./components/front/user/Orders.vue').default);
Vue.component('order-tracking', require('./components/front/user/OrderTrack.vue').default);

Vue.component('profile-update', require('./components/front/profile/ProfileUpdate.vue').default);
Vue.component('user-dashboard', require('./components/front/profile/UserDashboard.vue').default);

import VueLazyload from 'vue-lazyload';
import Cloudinary, {CldImage, CldTransformation} from "cloudinary-vue";

Vue.use(VueLazyload, {

    loading: base_url + 'images/loading.gif',

});

Vue.use(Cloudinary, {
    configuration: {
        cloudName: 'ditgrfuov',
        secure: true
    },
    components: [
        CldImage,
        CldTransformation
    ]
});

var app = new Vue({

    el: '#front-wrapper',
    store
});
