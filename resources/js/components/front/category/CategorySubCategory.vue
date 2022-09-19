<template>
    <div class="container">
        <div class="row category">
            <div class="col-md-12 mt-5">
                <div class="title text-center">
                    <h4>{{ category.category_name }}</h4>
                    <div class="title_border mb-5"></div>
                </div>
            </div>
        </div>
        <div class="row category " v-if="category.sub_category.length > 0 ">
            <div class="col-lg-2 col-md-3 col-sm-6 col-6 flex_box" v-for="(value,index) in category.sub_category"
                 :key="index">

                <div class="category_item_list flex_item">
                    <a :href="url+'product/sub-category/'+value.id+'/'+value.sub_category_slug" class="flex_wrap">
                        <cld-image :publicId="value.image" loading="lazy"
                                   alt="icon" class="img-fluid"></cld-image>

                        <h3 class="flex_bottom">{{ value.sub_category_name }}</h3>
                    </a>
                </div>

            </div>
        </div>

        <div class="row" v-else>
            <div class="col-md-12 text-center">
                <h3>No Sub Category</h3>
            </div>
        </div>
    </div>
</template>

<script>
import {EventBus} from '../../../vue-assets';
import Mixin from '../../../mixin';

export default {
    props: ['category'],
    data() {
        return {

            url: base_url,

        }
    },

    mounted() {
        // console.log(this.category.sub_category);
    }


}
</script>
<style scoped>
.flex_box {
    display: flex;
}

.flex_item {
    flex: 1;
    display: flex;
    flex-wrap: wrap;
    flex-grow: 1;
}

.flex_wrap {
    text-align: center;
    flex: 1;
    display: flex;
    flex-wrap: wrap;
}

.flex_wrap > * {
    flex: 1 1 100%;
}

.flex_bottom {
    align-self: flex-end;

}

.category_item_list {
    padding: 20px 10px;
    text-align: center;
    margin-bottom: 10px;
    transition: all 0.5s;
}

.category_item_list:hover {
    box-shadow: 0 3px 1px -2px rgba(0, 0, 0, .2), 0 2px 2px 0 rgba(0, 0, 0, .14), 0 1px 5px 0 rgba(0, 0, 0, .12);
    border-radius: 3px;
}

.category_item_list:hover h3 {
    color: #345B2C;
}

.category_item_list img {
    margin-bottom: 20px;
    transition: all 0.7s;
    transform: scale(1);
}

.category_item_list:hover img {
    transform: scale(1.1);
}

.category_item_list h3 {
    line-height: 24px !important;
    font-weight: bold !important;
    font-size: 16px !important;
}
</style>
