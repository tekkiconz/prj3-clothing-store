<template>
    <div class="container  mt-5">
        <div class="row category">
            <div class="col-md-12 title_box">
                <div class="title text-center">
                    <h5>Products</h5>
                    <h4>Hot <span>Deal</span></h4>
                </div>
                <div class="title_border"></div>
            </div>
        </div>
        <div class="row offers">
            <div class="col-lg-3 col-md-3 col-sm-6 col-xl-2" v-for="value in hotProducts" :key="value.id">
                <single-product :currency="currency" :product="value">
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
    props: ['currency'],
    mixins: [Mixin],
    components: {
        'single-product': SingleProduct,
        'infinite-loading': InfiniteLoading
    },
    data() {

        return {

            hotProducts: [],
            url: base_url,
            page: 1,
            lastPage: 0,

        }

    },

    mounted() {
        this.getDeals();
    },

    methods: {

        fetchProduct: function () {

            return axios.get(base_url + 'hot-deal?page=' + this.page);

        },

        getDeals() {
            this.fetchProduct()
                .then(response => {
                    if (response.data.data.length > 0) {
                        this.hotProducts = response.data.data;
                        this.page += 1;
                    } else {
                        console.log('Product not available');
                    }
                })
                .catch(e => console.log(e))
        },

        infiniteHandler: function ($state) {
            setTimeout(function () {
                this.fetchProduct()
                    .then(response => {
                        if (response.data.data.length > 0) {
                            this.lastPage = response.data.meta.last_page;
                            this.hotProducts.push(...response.data.data);

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
        }

    }
}
</script>
<style scoped>


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
