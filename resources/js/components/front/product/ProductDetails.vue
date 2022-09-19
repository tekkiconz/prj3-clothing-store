<template>
    <div>
        <section class="product-details mt30">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6  col-sm-12">
                        <div class="products-slider" style="overflow:hidden;">
                            <div class="big-img">
                                <div class="zoom-left">
                                    <img :src="previewImg" id="product_zoom"
                                         :data-zoom="previewLargeImg"
                                         draggable="false"
                                         class="img-fluid text-center" width="500"/>

                                    <div id='product_small' class="mt10" v-if="mounted && thumbs.length > 1">
                                        <a href="#" aria-label="Previous" @click.prevent="moveThumbs('backward')">
                                            <i class='lni lni-chevron-left'></i>
                                        </a>
                                        <img v-for="(value,index) in thumbs" :key="index" href="#"
                                           :class="{'active': value.id === choosedThumb.id}"
                                           v-show="index < 4" :src="value.dataImage"
                                           draggable="false"
                                           width="100" class="image"
                                           @click.prevent="chooseThumb(value)"
                                        />
                                        <a href="#" aria-label="Next" @click.prevent="moveThumbs('forward')">
                                            <i class='lni lni-chevron-right'></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <!-- left product end-->

                    <div class="col-lg-6  col-sm-12">
                        <div class="pro-det-right">
                            <h3>{{ product.product_name }}</h3>
                            <div class="pro-items qty_unit_details">
                                <span class="item-number">{{ product.quantity_unit }}</span>
                            </div>

                            <div class="pro-avai">
                                <span class="item-name">AVAILABILITY: </span>
                                <span class="item-val" v-if="product.current_quantity > 0">YES</span>
                                <span class="item-val" v-else>NO</span>
                            </div>

                            <div class="pro-avai">
                                <span class="item-name">Brand: </span>
                                <span class="item-val">{{ product.brand.brand_name }}</span>
                            </div>

                            <div class="pro-items">
                                <span class="item-name">ITEM NO.: </span>
                                <span class="item-number">ITM-#{{ product.id }}</span>
                            </div>
                            <div class="price">
                                <!-- <span class="price-name">PRICE:</span> -->
                                <span class="price-number" v-if="product.discount_status == 1">{{
                                        currency.symbol
                                    }}{{ product.selling_price - product.discount_amount | formatPrice }}</span>

                                <span class="price-number" v-else>{{
                                        currency.symbol
                                    }}{{ product.selling_price | formatPrice }}</span>

                                <span class="discount-price"
                                      v-if="product.discount_status == 1 && product.discount_amount > 0">{{
                                        currency.symbol
                                    }}{{ product.selling_price | formatPrice }}</span>
                            </div>


                            <div class="cart-product-quantity" v-if="havingProduct" style="margin-top:15px">
                                <div class="quantity mt-3">
                                    <input
                                        title="Remove one" @click="updateCart(havingProduct.rowId,'decrement')"
                                        type="button"
                                        value="-"
                                        class="minus">
                                    <strong class="qty_text">{{ havingProduct.qty }}</strong>
                                    <input title="Add one more" @click="updateCart(havingProduct.rowId,'increment')"
                                           type="button"
                                           value="+"
                                           class="plus">
                                </div>
                            </div>

                            <span class="button-wrapper" v-else style="margin-top:-15px">
                            	<a @click.prevent="addToCart(product.id,
                            		product.product_name,
                            		product.quantity_unit,
                            		cart.qty,
                            		product.current_quantity,
                            		product.discount_status == 1 && product.discount_amount > 0 ? product.selling_price - product.discount_amount : product.selling_price,
                            		product.feature_image,product.discount_status == 1 && product.discount_amount > 0 ? product.discount_amount : 0)"
                                   class="button btn-cart" href="">

                            		Add to Bag <i class="lni lni-shopping-basket"></i>
                            	</a>
                            </span>
                            <div style="margin-bottom:20px" class="short-des" v-if="product.product_description">
                                <div v-if="!readMoreActive && product.product_description.length > 199">
                                    <div v-html="product.product_description.slice(0, 200)">
                                    </div>
                                    <a class="more-less theme-color" v-if="!readMoreActive"
                                       @click.prevent="activateReadMore" href="">...Read more</a>
                                </div>
                                <div v-else>
                                    <div v-html="product.product_description">
                                    </div>
                                    <a class="more-less theme-color" v-if="readMoreActive"
                                       @click.prevent="activateLessText" href="">---Less text</a>
                                </div>

                            </div>

                            <div class="follow mt10">
                                <!-- <span>share:</span> -->
                                <a class="entry bg-primary text-white"
                                   :href="'https://www.facebook.com/sharer/sharer.php?u='+url+'product/'+product.id+'/'+product.product_slug">
                                    <i class='lni lni-facebook-filled'></i>
                                </a>
                                <a class="entry bg-info text-white"
                                   :href="'https://twitter.com/intent/tweet?text='+url+'product/'+product.id+'/'+product.product_slug"
                                   data-size="large">
                                    <i class='lni lni-twitter-filled'></i>
                                </a>
                                <!-- <a class="entry" href="#"><i class='lni lni-linkedin-original'></i></a>
                                <a class="entry" href="#"><i class='lni lni-pinterest'></i></a> -->
                            </div>
                        </div>
                    </div>
                    <!-- product details end-->
                </div>
            </div>
        </section>
    </div>
</template>

<script>
import Mixin from '../../../mixin';
import Drift from 'drift-zoom';

export default {
    props: ['currency', 'product'],
    mixins: [Mixin],
    data() {

        return {
            drift: null,

            cloudinaryUrl: 'https://res.cloudinary.com/ditgrfuov/image/upload/',
            mounted: false,
            thumbs: [],
            choosedThumb: {},
            previewImg: '',
            previewLargeImg: '',

            url: base_url,

            cart: {
                'qty': 1,
            },

            readMoreActive: false

        }
    },

    mounted() {
        let _this = this;
        _this.mounted = true;

        let t = setInterval(() => {
            if (document.readyState === 'complete') {
                _this.drift = new Drift(document.getElementById('product_zoom'), {
                    zoomFactor: 3,
                    inlinePane: true,
                    injectBaseStyles: true,
                    hoverDelay: 300,
                    containInline: true,
                });

                clearInterval(t);
            }
        }, 500);
    },

    watch: {
        choosedThumb: function(thumb) {
            let matchImg = this.thumbs.find(img => {
                return img.id === thumb.id;
            });

            this.previewImg = matchImg.dataImage;
            this.previewLargeImg = matchImg.dataZoomImage;

            if (this.drift !== null) {
                this.drift.setZoomImageURL(matchImg.dataZoomImage);
            }
        }
    },

    created() {
        let _this = this;

        let galleryImages = [
            {
                id: 1,
                dataImage: _this.cloudinaryUrl + _this.product.feature_image + '.jpg',
                dataZoomImage: _this.cloudinaryUrl + _this.product.feature_image + '.jpg'
            }
        ];

        if (_this.product.multiple_image.length > 0) {
            const multiSubImages = _this.product.multiple_image.map((value, index) => {
                return {
                    id: index + 2,
                    dataImage: _this.cloudinaryUrl + value.image + '.jpg',
                    dataZoomImage: _this.cloudinaryUrl + value.image + '.jpg'
                };
            });

            galleryImages = [
                ...galleryImages,
                ...multiSubImages
            ];
        }

        if (_this.thumbs.length === 0) {
            _this.thumbs = galleryImages;
        }

        _this.choosedThumb = _this.thumbs[0];
    },

    computed: {

        havingProduct() {
            return this.$store.getters.productWithId(this.product.id);
        }
    },

    methods: {
        moveThumbs(direction) {
            let len = this.thumbs.length;

            if (direction === 'backward') {
                const moveThumb = this.thumbs.splice(len - 1, 1);
                this.thumbs = [moveThumb[0], ...this.thumbs];
            } else {
                const moveThumb = this.thumbs.splice(0, 1);
                this.thumbs = [...this.thumbs, moveThumb[0]];
            }
        },

        chooseThumb(thumb) {
            this.choosedThumb = thumb;
        },

        addToCart(id, product_name, qty_unit, qty, current_qty, price, product_image, discount) {

            axios.post(base_url + 'add-to-cart', {
                'id': id,
                'product_name': product_name,
                'qty_unit': qty_unit,
                'qty': 1,
                'current_qty': current_qty,
                'price': price,
                'product_image': product_image,
                'discount': discount
            })
                .then(response => {

                    if (response.data.status === 'success') {
                        // this.successMessage(response.data);
                        // dispatch a store commit
                        this.$store.dispatch("getCart");
                    } else {
                        this.successMessage(response.data);
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

        activateReadMore() {
            this.readMoreActive = true
        },

        activateLessText() {
            this.readMoreActive = false
        },

    },


}
</script>


<style scoped="">

.num {
    display: inline;
}

.qty_text {

    float: left;
    /*width: 34px;*/
    height: 40px;
    line-height: 40px;
    border: none;
    background-color: transparent;
    text-align: center;
    padding: 0px 10px 0px 10px;
    margin: 0px;
    font-size: 1.5em;

}

.qty_text[data-v-5f1758f8] {
    float: left;
    height: 40px;
    line-height: 40px;
    border: none;
    background-color: transparent;
    text-align: center;
    padding: 0px 10px 0px 10px;
    margin: 0px;
    font-size: 1em !important;
    font-weight: bold;
}
</style>
