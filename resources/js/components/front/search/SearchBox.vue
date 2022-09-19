<template>
    <div class="search col-sm-12 col-md-12 col-lg-5 px-0" id="searchBox">
        <!-- <form> -->
        <div class="input-box">
            <input

                :value="keyword"
                @input="evt=>keyword=evt.target.value"
                name="key"
                class="single-input col-sm-12"
                placeholder="Search for product..."
                @keyup="searchProduct()" type="text">
            <!-- <button type="submit" class="src-btn"><i class="lni-search"></i></button> -->
        </div>
        <!-- </form> -->
    </div>
</template>

<script>
import {EventBus} from '../../../vue-assets';
import Mixin from '../../../mixin';

export default {
    data() {
        return {
            keyword: '',
        }
    },

    methods: {
        searchProduct() {

            // this.$store.dispatch("searchProduct",{page:1,keyword:this.keyword});

            EventBus.$emit('scrol-to-result', this.keyword);


        }
    }
}
</script>
<style scoped>
.col-lg-5 {
    float: none !important;
}

.single-input {
    padding: 10px;
}

.single-input:focus {
    border: 0 !important;
}

.search {
    border: 1px solid #1a94ff;
}
</style>
