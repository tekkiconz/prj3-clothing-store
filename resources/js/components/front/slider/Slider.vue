<template>
    <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
        <ol class="carousel-indicators">
            <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
            <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
            <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
        </ol>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img class="d-block responsive_image" src="images/slider/1.jpg" alt="First slide">
            </div>

            <div class="carousel-item">
                <img class="d-block responsive_image" src="images/slider/2.jpg" alt="First slide">
            </div>

            <div class="carousel-item">
                <img class="d-block responsive_image" src="images/slider/3.jpg" alt="First slide">
            </div>

        </div>
        <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>
</template>


<script>

import {EventBus} from "../../../vue-assets";

export default {

    data() {

        return {

            url: base_url,
            searchProducts: [],
            page: 1,
            keyword: '',
            infiniteId: +new Date(),

        }

    },

    mounted() {
        var _this = this;
        EventBus.$on('scrol-to-result', function (keyword) {
            _this.searchProducts = [];
            _this.keyword = keyword;
            _this.chnageType();
            var elmnt = document.getElementById("searchItem");
            if (!_this.isInViewport(elmnt)) {

                elmnt.scrollIntoView(true);
            }


        });

    },

    methods: {

        isInViewport(elem) {
            var bounding = elem.getBoundingClientRect();
            return (
                bounding.top >= 20 &&
                bounding.left >= 0 &&
                bounding.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
                bounding.right <= (window.innerWidth || document.documentElement.clientWidth)
            );
        },


        fetchProduct: function () {

            return axios.get(base_url + 'search-product?page=' + this.page + '&keyword=' + this.keyword);
        },

        infiniteHandler: function ($state) {
            setTimeout(function () {
                this.fetchProduct()
                    .then(response => {
                        if (response.data.data.length > 0) {
                            this.lastPage = response.data.meta.last_page;
                            this.searchProducts.push(...response.data.data);

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
            if (this.keyword != '' && this.keyword != ' ') {
                this.fetchProduct()
                    .then(response => {
                        if (response.data.data.length > 0) {
                            this.searchProducts = response.data.data;
                            this.page += 1;
                        }
                    })
                    .catch(e => console.log(e))

            } else {
                this.searchProducts = [];
            }

        },
        chnageType() {
            this.page = 1;
            this.searchProducts = [];
            this.infiniteId += 1;
            this.initialData();
        }

    }
}
</script>
<style scoped>


.caption {
    position: absolute;
    /* right: 15%; */
    bottom: 20px;
    /* left: 15%; */
    z-index: 1;
    padding-top: 20px;
    padding-bottom: 20px;
    color: #fff;
    text-align: center;
    width: 100%;
    height: 100%;
    display: grid;
    align-items: center;
}

.lay_box {
    display: flex;
    -ms-flex: 0 0 auto;
    flex: 0 0 auto;
    -ms-flex-wrap: nowrap;
    flex-wrap: nowrap;
    min-width: 0;
}

.col_box {
    flex-direction: column;
}

.center_justify {
    justify-content: center;
}

.caption h1 {
    font-size: 60px !important;
    letter-spacing: 1px;
    color: #000000;
    font-weight: 700;
    text-align: center;
    margin-bottom: 10px !important;
    font-family: Lobster Two, cursive;
}

.caption p {
    font-size: 20px;
    text-align: center;
    color: #000000;
    line-height: 1.5rem;
    padding: 0 20px;
    margin-bottom: 20px;
}

.theme_button {
    background: #345B2C !important;
    color: #FFF;
    box-shadow: 0 3px 1px -2px rgba(0, 0, 0, .2), 0 2px 2px 0 rgba(0, 0, 0, .14), 0 1px 5px 0 rgba(0, 0, 0, .12) !important;
    padding: 15px 0 !important;
    width: 150px;
    text-transform: uppercase !important;
    border-radius: 2px;
    font-size: 13px !important;
    font-weight: 600;
    letter-spacing: 1px;
}

.theme_button:hover {
    background: #4C6E45 !important;
}

.carousel {
    padding-left: 1px;
}

.carousel-indicators li {
    box-sizing: content-box;
    -ms-flex: 0 1 auto;
    flex: 0 1 auto;
    width: 30px;
    height: 3px;
    margin-right: 3px;
    margin-left: 3px;
    text-indent: -999px;
    cursor: pointer;
    background-color: #345B2C;
    background-clip: padding-box;
    border-top: 10px solid transparent;
    border-bottom: 10px solid transparent;
    opacity: .5;
    transition: opacity .6s ease;
}

.carousel-indicators li.active {
    opacity: 1;
}

@media (max-width: 991px) {
    .caption h1 {
        font-size: 33px !important;
        margin-top: 30px;
    }

    .caption p[data-v-6ac9be0e] {
        font-size: 20px;
        text-align: center;
        color: #000000;
        line-height: 1.5rem;
        padding: 0 20px;
        margin-bottom: 10px !important;
    }

    .theme_button[data-v-6ac9be0e] {
        background: #345B2C !important;
        color: #FFF;
        box-shadow: 0 3px 1px -2px rgba(0, 0, 0, .2), 0 2px 2px 0 rgba(0, 0, 0, .14), 0 1px 5px 0 rgba(0, 0, 0, .12) !important;
        padding: 8px 0 !important;
        width: 150px;
        text-transform: uppercase !important;
        border-radius: 2px;
        font-size: 13px !important;
        font-weight: 600;
        letter-spacing: 1px;
    }
}

@media (max-width: 884px) {
    .carousel-indicators[data-v-6ac9be0e] {
        position: absolute;
        right: 0;
        bottom: -17px !important;
    }
}

@media (max-width: 769px) {
    .slide {
        display: none;
    }
}

@media (min-width: 769px) {
    .responsive_image {
        width: 100% !important;
    }

}

@media (max-width: 370px) {
    .caption h1 {
        font-size: 43px !important;
    }
}


</style>
