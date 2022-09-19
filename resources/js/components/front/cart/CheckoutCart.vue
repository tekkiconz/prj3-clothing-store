<template>

    <div class="form product-info bg-white bg-shadow">
        <div class="heading  clearfix p10">
            <h4 class="color-black">Cart Summary</h4>
        </div>
        <small class="heading heading-solid center-block heading-width-100 border-light"></small>
        <div class="table-responsive" v-if="cartCount > 0">
            <table class="table-condensed">
                <tbody>
                    <tr v-for="(value,index) in cartItems" :key="index">
                        <td><a href="" @click.prevent="removeItem(value.rowId)">
                            <i class='lni lni-close remove_cart theme-hover-color'></i></a>
                        </td>
                        <td class="quantity-td">
                            <div class="quantity">
                                <input title="Remove one" @click.prevent="updateCart(value.rowId,'decrement')"
                                       type="button" value="-" class="minus">
                                <input type="text" :id="index" :value="value.qty" title="Qty" class="qty" size="4">
                                <input title="Add one more" @click.prevent="updateCart(value.rowId,'increment')"
                                       type="button" value="+" class="plus">
                            </div>
                        </td>
                        <td>
                            <cld-image :publicId="value.options.image" loading="lazy"
                                       class="img-fluid" alt="product-image"></cld-image>
                        </td>
                        <td>
                            <span class="title">{{ value.name }}</span>
                            <span class="price">{{ value.qty }} x {{ currency.symbol }}{{
                                    value.price | formatPrice
                                }}</span>
                            <span class="price discount-price" v-if="value.options.discount > 0">{{
                                    currency.symbol
                                }}{{ value.price + value.options.discount | formatPrice }}</span>
                        </td>
                        <td><span class="total">{{ currency.symbol }}{{ value.qty * value.price | formatPrice }}</span>
                        </td>

                    </tr>
                    <tr>
                        <td colspan="4"><span class="totitle">SubTotal</span></td>
                        <td><span class="total">{{ currency.symbol }}{{ cartTotal | formatPrice }}</span></td>
                    </tr>
                    <tr>
                        <td colspan="4"><span class="totitle"> Delivery Charge</span></td>
                        <td><span class="total">{{ currency.symbol }} {{ shippingCost }}</span></td>
                    </tr>
                    <tr>
                        <td colspan="4"><span class="totitle">Total</span></td>
                        <td><span class="total">{{
                                currency.symbol
                            }}{{ cartTotal + shippingCost | formatPrice }}</span></td>
                        <input type="hidden" name="delivery_cost" :value="shippingCost">

                        <input type="hidden" name="cart_total" :value="cartTotal">
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="text-center" v-else>
            <div class="cart-empty">
                <div class="cart-icon">
                    <i class='lni lni-shopping-basket theme-color'></i>
                </div>
                <span class="mt10">Your shopping bag is empty.</span>
                <a href="/" class="shopping-now theme-color">Start shopping now.</a>
            </div>
        </div>

    </div>

</template>

<script>
import {EventBus} from '../../../vue-assets';
import Mixin from '../../../mixin';

export default {
    mixins: [Mixin],
    props: ['currency'],
    data() {
        return {
            // search_keyword : {}
            url: base_url,
            shipping: {},
        }
    },

    mounted() {

        this.$store.dispatch("getCart");
        this.getShipping();

    },

    methods: {
        removeItem(id) {
            axios.get(base_url + 'cart/remove/' + id)
                .then(response => {
                    this.$store.dispatch("getCart");
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
        getShipping() {
            axios.get(base_url + 'get-shipping')
                .then(response => {
                    this.shipping = response.data;
                });
        }
    },

    computed:
        {

            cartCount() {

                return this.$store.getters.cart_count;
            },

            cartTotal() {

                return this.$store.getters.cart_total;
            },
            isLoading() {

                return this.$store.getters.cart_loading;
            },

            cartItems() {

                return this.$store.getters.cart_items;

            },

            shippingCost() {
                if (this.shipping.shipping_status == 1) {

                    if (this.shipping.discount_status == 1 && this.cartTotal > this.shipping.order_amount) {
                        return this.shipping.shipping_amount - this.shipping.discount_amount;
                    } else {
                        return this.shipping.shipping_amount;
                    }
                } else {
                    return 0;
                }
            }

        }

}
</script>



