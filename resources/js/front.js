require('./vue-assets');
require('drift-zoom/dist/drift-basic.min.css')

// start vuex

import Vuex from 'vuex';

Vue.use(Vuex);

import storeData from "./store/index";

const store = new Vuex.Store(
    storeData
);

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
