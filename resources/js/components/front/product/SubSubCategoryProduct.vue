<template>
    <div class="container">
        <div class="row">
            <div class="col-md-12 offers">
                <div class="title text-center">
                    <h4>{{ sub_sub_category.sub_sub_category_name }}</h4>
                </div>
            </div>
        </div>
        <!-- <div class="row category-brand">
          <div class="col-lg-12 col-sm-12 ">
            <div class="brands clearfix ">
              <ul class="inline-links">
                <li :class="brand_id == '' ? 'brand_active' : ''">
                  <a href="" @click.prevent="filterProduct()">
                   ALL BRANDS
                 </a>
               </li>
               <li v-for="(brand,index) in brands" :class="brand_id == brand.id ? 'brand_active' : ''" :key="index">
                <a href="" @click.prevent="filterProduct(brand.id)" :title="brand.brand_name">
                  <img v-lazy="brand.image" alt="" class="img-fluid">
                </a>
              </li>
            </ul>
          </div>
        </div>
        </div> -->
        <div class="row offers">

            <div class="col-6 col-lg-3 col-sm-6" v-for="(value,index) in subSubCategoryProducts" :key="index">
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
    props: ['currency', 'sub_sub_category', 'brands'],
    mixins: [Mixin],
    components: {
        'single-product': SingleProduct,
        'infinite-loading': InfiniteLoading
    },
    data() {

        return {
            brand_id: '',
            subSubCategoryProducts: [],
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

            return axios.get(base_url + 'sub-sub-category-product-list/' + this.sub_sub_category.id + '?page=' + this.page + '&brand_id=' + this.brand_id);
        },

        infiniteHandler: function ($state) {
            setTimeout(function () {
                this.fetchProduct()
                    .then(response => {
                        if (response.data.data.length > 0) {
                            this.lastPage = response.data.meta.last_page;
                            this.subSubCategoryProducts.push(...response.data.data);

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
                        this.subSubCategoryProducts = response.data.data;
                        this.page += 1;
                    }
                })
                .catch(e => console.log(e))
        },

        filterProduct(brand_id = '') {

            this.page = 1;
            this.brand_id = brand_id;
            this.subSubCategoryProducts = [];
            this.infiniteId += 1;
            this.initialData();


        }
    },


}
</script>
<style scoped="">
.brand_active {

    border: 1px solid #E3106E !important;

}
</style>
