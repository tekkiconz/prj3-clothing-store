<template>
    <div class="container mt-5">

        <div class="row category">
            <div class="col-md-12 title_box">
                <div class="title text-center">
                    <h5>Products</h5>
                    <h4><span v-if="sub_category.sub_sub_category.length > 0">All</span>
                        {{ sub_category.sub_category_name }}</h4>
                </div>
                <div class="title_border"></div>
            </div>
        </div>
        <div class="row offers">
            <div class="col-lg-3 col-md-3 col-sm-6 col-xl-2" v-for="(value,index) in subCategoryProducts" :key="index">
                <single-product :currency="currency" :identifier="infiniteId" :product="value">
                </single-product>
            </div>

            <infinite-loading spinner="bubbles" @infinite="infiniteHandler">
                <div slot="spinner">
                    <div class="col-md-12 text-center">
                        <img :src="url+'images/loading.gif'">
                    </div>
                </div>
                <div slot="no-more"></div>
                <div slot="no-results"></div>
            </infinite-loading>

        </div>
    </div>

</template>

<script>
import {EventBus} from '../../../vue-assets';
import Mixin from '../../../mixin';
import SingleProduct from './SingleProduct';
import InfiniteLoading from 'vue-infinite-loading';

export default {
    props: ['currency', 'sub_category', 'brands'],
    mixins: [Mixin],
    components: {
        'single-product': SingleProduct,
        'infinite-loading': InfiniteLoading
    },
    data() {

        return {
            brand_id: '',
            subCategoryProducts: [],
            page: 1,
            lastPage: 0,
            infiniteId: +new Date(),
            url: base_url,
        }

    },

    mounted() {
        this.initialData();
    },
    methods: {
        fetchProduct: function () {

            return axios.get(base_url + 'sub-category-product-list/' + this.sub_category.id + '?page=' + this.page + '&brand_id=' + this.brand_id);
        },

        infiniteHandler: function ($state) {
            setTimeout(function () {
                this.fetchProduct()
                    .then(response => {
                        if (response.data.data.length > 0) {
                            this.lastPage = response.data.meta.last_page;
                            this.subCategoryProducts.push(...response.data.data);

                            if (this.page === this.lastPage) {
                                this.page = 1;
                                $state.complete();
                            } else {
                                this.page += 1;
                            }
                            $state.loaded();
                        } else {
                            this.page = 1;
                            $state.complete();
                        }
                    })
                    .catch(e => console.log(e));
            }.bind(this), 1000);
        },

        initialData() {
            this.fetchProduct()
                .then(response => {
                    if (response.data.data.length > 0) {
                        this.subCategoryProducts = response.data.data;
                        this.page += 1;
                    }
                })
                .catch(e => console.log(e))
        },

    },


}
</script>
<style scoped="">
.brand_active {

    border: 1px solid #E3106E !important;

}

@media (max-width: 1019px) {
    .col-md-3 {
        -ms-flex: 0 0 50% !important;
        flex: 0 0 50% !important;
        max-width: 50% !important;
    }
}

@media (max-width: 485px) {
    .col-sm-6 {
        -ms-flex: 0 0 50% !important;
        flex: 0 0 50% !important;
        max-width: 50% !important;
    }
}

</style>
