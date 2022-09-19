<template>
    <div class="container">
        <div class="banner mt30 mb50 row">
            <div class="col-lg-12 mt20">
                <div class="bn text-center">
                    <a href="#" :title="campaignid.campaign_title">
                        <cld-image :publicId="campaignid.image" loading="lazy"
                                   alt="banner" class="img-fluid" :title="campaignid.title"></cld-image>
                    </a>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row offers" v-if="!isLoading">

                <div class="col-lg-3 col-sm-6" v-for="value in offerProduct" :key="value.id">
                    <single-product :currency="currency" :product="value">
                    </single-product>
                </div>

            </div>

            <div class="row" v-else>
                <div class="col-md-12 text-center">
                    <img :src="url+'images/loading.gif'">
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {EventBus} from '../../../vue-assets';
import Mixin from '../../../mixin';
import SingleProduct from '../product/SingleProduct';

export default {
    props: ['campaignid', 'currency'],
    components: {
        'single-product': SingleProduct,
    },
    data() {

        return {

            offerProduct: [],
            isLoading: false,
            url: base_url

        }

    },

    mounted() {
        this.getOfferProduct();
    },

    methods: {

        getOfferProduct() {
            // console.log(this.campaignid)
            this.isLoading = true;
            axios.get(base_url + 'offered-product/' + this.campaignid.id)
                .then(response => {
                    console.log(response.data)
                    this.offerProduct = response.data.data;
                    this.isLoading = false;
                });
        },

    }

}
</script>
