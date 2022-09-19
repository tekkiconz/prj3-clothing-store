<template>
    <div class="container mt-5">
        <div class="row category">
            <div class="col-md-12 title_box">
                <div class="title text-center">
                    <h5>Categories</h5>
                    <h4>Product <span>Categories</span></h4>
                </div>
                <div class="title_border"></div>
            </div>
        </div>
        <div class="row category home-category" v-if="!isLoading">
            <!-- end service 1-->
            <div class="col-lg-3 col-sm-6" v-for="(value,index) in categories" :key="index">
                <a :href="url+'product/sub-category/'+value.id+'/'+value.sub_category_slug">
                    <div class="box">
                        <div class="content d-flex justify-content-between">
                            <h3>{{ value.sub_category_name }}</h3>
                            <cld-image :publicId="value.image" loading="lazy"
                                       alt="icon" class="img-fluid"></cld-image>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <div class="row" v-else>
            <div class="col-md-12 text-center">
                <img :src="url+'images/loading.gif'">
            </div>
        </div>
    </div>
</template>

<script>
import {EventBus} from '../../../vue-assets';
import Mixin from '../../../mixin';

export default {
    mixins: [Mixin],
    data() {
        return {

            categories: [],
            isLoading: false,
            url: base_url,

        }
    },

    mounted() {

        this.getCategoryList();

    },

    methods: {

        getCategoryList() {
            this.isLoading = true;
            axios.get(base_url + 'viewlist')
                .then(response => {

                    this.categories = response.data.data;
                    this.isLoading = false;
                })

        },

    }

}
</script>
