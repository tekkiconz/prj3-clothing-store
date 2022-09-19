<template>
    <div class="offer">
        <div class="overlay_box">

            <a :href="url+'product/'+product.id+'/'+product.product_slug" class="single_image_box">
                <cld-image :publicId="product.feature_image" loading="lazy"
                           alt=".webp not supported in safari" class="img-fluid"></cld-image>
                <div class="image_overlay"><i class="lni lni-eye"></i></div>
            </a>
            <div class="content ">
                <a :href="url+'product/'+product.id+'/'+product.product_slug" class="name">{{
                        product.product_name
                    }}</a>
                <p class="qty_unit"><small>{{ product.quantity_unit }}</small></p>
                <span class="regular-price" v-if="product.discount_status == 1">{{
                        currency.symbol
                    }}{{ product.selling_price - product.discount_amount | formatPrice }}</span>
                <span class="regular-price" v-else>{{ currency.symbol }}{{ product.selling_price }}</span>

                <span class="discount-price" v-if="product.discount_status == 1 && product.discount_amount > 0">{{
                        currency.symbol
                    }}{{ product.selling_price | formatPrice }}</span>

            </div>
        </div>

        <div class="item-cart" v-if="havingProduct">

            <a title="Remove On"
               @click.prevent="updateCart(havingProduct.rowId,'decrement')"
               class="float-left  qty-minus">
                <strong><i class="lni lni-minus"></i></strong>
            </a>

            <div class="qty-text float-left">
                <strong>{{ havingProduct.qty }}</strong>
            </div>

            <a
                title="Add One More"
                @click.prevent="updateCart(havingProduct.rowId,'increment')"
                class="float-left  qty-plus">
                <strong><i class="lni lni-plus"></i></strong>
            </a>

        </div>

        <a v-else @click.prevent="addToCart(
            product.id,
            product.product_name,
            product.quantity_unit,
        1,
            product.current_quantity,
            product.discount_status == 1 && product.discount_amount > 0 ? product.selling_price - product.discount_amount : product.selling_price,
            product.feature_image,product.discount_status == 1 && product.discount_amount > 0 ? product.discount_amount : 0)"
            href="" class="button button-sm add_to_cart_button ">
            {{ cart_button }} <i class="lni-shopping-basket"></i></a>


    </div>
</template>

<script>
import {EventBus} from '../../../vue-assets';
import Mixin from '../../../mixin';

export default {
    props: ['currency', 'product'],
    mixins: [Mixin],
    data() {

        return {

            url: base_url,
            cart_button: 'Add to Bag',

        }

    },

    computed: {

        havingProduct() {
            return this.$store.getters.productWithId(this.product.id);
        }
    },

    methods: {
        addToCart(id, product_name, qty_unit, qty, current_qty, price, product_image, discount) {
            this.cart_button = 'Adding...';
            axios.post(base_url + 'add-to-cart', {
                'id': id,
                'product_name': product_name,
                'qty_unit': qty_unit,
                'qty': qty,
                'current_qty': current_qty,
                'price': price,
                'product_image': product_image,
                'discount': discount,
            })
                .then(response => {

                    if (response.data.status === 'success') {
                        // this.successMessage(response.data);
                        // dispatch a store commit
                        this.$store.dispatch("getCart");
                        this.cart_button = 'Add to Cart';
                    } else {
                        this.successMessage(response.data);
                        this.cart_button = 'Add to Cart';
                    }

                })


        },
        updateCart(id, status) {
            axios.get(base_url + 'cart/update/' + id + '/' + status)
                .then(response => {

                    if (response.data.status === 'success') {
                        this.$store.dispatch("getCart");
                    } else {
                        this.successMessage(response.data);
                    }

                })
        },

    }
}
</script>

<style scoped>
.overlay_box {
    position: relative;
}

.image_overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.3);
    z-index: 1;
    opacity: 0;
    transition: all 0.5s;
    display: grid;
    align-items: center;
}

.image_overlay .lni-eye:before {
    content: "\eac3";
    color: #FFF;
    border: 2px solid #FFF;
    border-radius: 4px;
    padding: 8px;
    font-size: 25px;
    font-weight: bolder;
}

.offer .button {
    width: 100% !important;
    border: 1px solid #e6e6e6;
    border-radius: 0 !important;
}

.overlay_box:hover .image_overlay {
    opacity: 1;
}

.offer:hover .button {
    width: 100% !important;
    border: 1px solid #345B2C !important;
}

</style>


