<template>
    <div id="category">
        <AddCategory />
        <EditCategory />
    </div>
</template>

<script>
import axios from "axios";
import AddCategory from "./AddCategory.vue";
import EditCategory from "./EditCategory.vue";
export default {
    components: {
        AddCategory,
        EditCategory
    },
    mounted() {},
    data() {
        return {};
    },
    methods: {}
};
</script>
<style lang="scss" scoped>
#category {
    display: flex;
    padding-top: 5%;
    justify-content: space-around;
}
</style>
