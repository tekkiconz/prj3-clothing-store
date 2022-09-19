<template>
    <div class="wrapper">
        <div class="row mt-4" v-if="!isLoading">


            <div class="col-lg-3 tooltip-demo">
                <a :href="url+'admin/customer'" data-toggle="tooltip" data-placement="bottom" title="Manage Customers">
                    <div class="p_new lazur-bg">
                        <div class="">
                            <i class="fa fa-group fa-2x"></i>
                            <h3 class="m-xs">{{ counter.customer }}</h3>
                            <h3 class="font-bold no-margins">
                                Customers
                            </h3>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-lg-3 tooltip-demo">
                <a :href="url+'admin/product'" data-toggle="tooltip" data-placement="left" title="Manage Product">
                    <div class="p_new lazur-bg">
                        <div class="">
                            <i class="fa fa-shopping-basket fa-2x"></i>
                            <h3 class="m-xs">{{ counter.product }}</h3>
                            <h3 class="font-bold no-margins">
                                Products
                            </h3>
                        </div>
                    </div>
                </a>
            </div>


            <div class="col-lg-3 tooltip-demo">
                <a :href="url+'admin/stock-report'" data-toggle="tooltip" data-placement="right" title="Show Stock">
                    <div class="p_new lazur-bg">
                        <div class="">
                            <i class="fa fa-superpowers fa-2x"></i>
                            <h3 class="m-xs">{{ counter.stock }}</h3>
                            <h3 class="font-bold no-margins">
                                Stock
                            </h3>
                        </div>
                    </div>
                </a>
            </div>


            <div class="col-lg-3 tooltip-demo">
                <a :href="url+'admin/order'" data-toggle="tooltip" data-placement="top" title="Manage Order">
                    <div class="p_new lazur-bg">
                        <div class="">
                            <i class="fa fa-first-order fa-2x"></i>
                            <h3 class="m-xs">{{ counter.order }}</h3>
                            <h3 class="font-bold no-margins">
                                Order
                            </h3>
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
            counter: {
                brand: '',
                category: '',
                product: '',
                order: ''
            },
            url: base_url,
            isLoading: false,
        }
    },
    mounted() {
        this.ListCounter();

    },

    methods: {
        ListCounter: function () {
            this.isLoading = true;
            axios.get(base_url + 'admin/dashboard/summary')
                .then(response => {
                    this.counter = response.data;
                    this.isLoading = false;
                });
        },
    },
}

</script>

