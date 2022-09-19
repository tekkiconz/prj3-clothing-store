require('./vue-assets');
Vue.component('seo-setting', require('./components/admin/setting/seo/SeoSetting.vue').default);
Vue.component('shop-setting', require('./components/admin/setting/shop/ShopSetting.vue').default);

import Cloudinary, {CldImage, CldTransformation} from "cloudinary-vue";

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

    el: '#wrapper'
});


