<template>
  <div>
    <div v-if="alert">
      <Alert />
    </div>

    <div id="category">
      <form @submit.prevent="formSubmit">
        <div class="form-group">
          <label for="category">Enter new category</label>
          <input
            type="text"
            class="form-control"
            id="category"
            aria-describedby="categoryHelp"
            placeholder="Enter new category"
            v-model="category"
          />
        </div>
        <br />
        <button type="submit" class="btn btn-primary">Submit</button>
      </form>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import Alert from "./Alert.vue";
import { bus } from "../app";
import { getCategories } from "./inventory";
export default {
  components: {
    Alert,
  },
  mounted() {},
  data() {
    return {
      category: "",
      alert: false,
    };
  },
  methods: {
    async formSubmit() {
      this.alert = true;
      const result = await axios
        .post("/category", {
          category: this.category,
        })
        .catch((err) => {
          this.categoryError = true;
          if (err.response.data === "Category already exists") {
            bus.$emit("errorAlert", "Category already exists");
          } else if (err.response.data.errors.category) {
            bus.$emit("errorAlert", err.response.data.errors.category[0]);
          } else {
            console.log(err);
          }
        });
      if (result) {
        this.categorySaved = true;
        bus.$emit("successAlert", "New Category Has Been Saved!");
        this.category = "";
        this.categoryArray = await getCategories();
        bus.$emit("newCategory");
      }
      setTimeout(() => {
        this.alert = false;
      }, 3000);
    },
  },
};
</script>
<style lang="scss" scoped>
</style>
