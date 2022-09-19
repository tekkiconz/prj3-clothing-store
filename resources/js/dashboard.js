require('./vue-assets');
Vue.component('dashboard-summary', require('./components/admin/dashboard/dashboard.vue').default);
Vue.component('chart-summary', require('./components/admin/dashboard/chart.vue').default);

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
