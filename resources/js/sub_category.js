require('./vue-assets');
Vue.component('create-subcategory', require('./components/admin/subcategory/CreateSubCategory.vue').default);
Vue.component('view-subcategory', require('./components/admin/subcategory/ViewSubCategory.vue').default);

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

    el: '#wrapper'
});


