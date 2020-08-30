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
    EditCategory,
  },
  mounted() {
    this.getCategories();
  },
  data() {
    return {};
  },
  methods: {
    async getCategories() {
      const result = await axios
        .get("/categories")
        .catch((error) => console.log(error));
      this.categoryArray = result.data;
    },
  },
};
</script>
<style lang="scss" scoped>
#category {
  display: flex;
  padding-top: 5%;
  justify-content: space-around;
}
</style>
