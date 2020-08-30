<template>
  <div>
    <div v-if="alert">
      <Alert />
    </div>
    <div id="category">
      <form>
        <label for="category">Choose a category</label>
        <select v-model="categoryArray.id" @change="onChange($event)" class="form-control">
          <option value>Default Value</option>
          <option
            v-for="category in categoryArray"
            :key="category.id"
            :value="category.id"
            :itemid="category.category"
          >{{category.category}}</option>
        </select>
        <br />
        <input type="text" v-model="selected" />
        <br />
        <br />
        <button type="button" @click="edit" class="btn btn-success">Edit</button>
        <button type="button" @click="erase" class="btn btn-danger">Delete</button>
      </form>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import Alert from "./Alert.vue";
import { bus } from "../app";
export default {
  components: {
    Alert,
  },
  mounted() {
    this.getCategories();
  },
  data() {
    return {
      category: "",
      categoryArray: [],
      selected: "",
      id: "",
      myOption: {},
      alert: false,
    };
  },
  methods: {
    async getCategories() {
      const result = await axios
        .get("/categories")
        .catch((error) => console.log(error));
      this.categoryArray = result.data;
      this.categoryArray.forEach((element) => {
        this.myOption[element.id] = element.category;
      });
    },
    onChange(event) {
      this.id = event.target.value;
      this.selected = this.myOption[this.id];
    },
    async edit() {
      this.alert = true;
      const result = await axios
        .post("/category/update", {
          selected: this.selected,
          id: this.id,
        })
        .catch((error) => {
          bus.$emit("errorAlert", "Please select Category");
        });
      this.selected = "";
      if (result) {
        bus.$emit("successAlert", "Category has been Updated!");
        this.category = "";
        this.getCategories();
      }
      setTimeout(() => {
        this.alert = false;
      }, 3000);
    },
    async erase() {
      this.alert = true;
      const result = await axios
        .post("/category/delete", {
          id: this.id,
        })
        .catch((error) => {
          bus.$emit("errorAlert", "Please select Category");
        });
      this.selected = "";
      if (result) {
        bus.$emit("successAlert", "Category Has Been Deleted!");
        this.category = "";
        this.getCategories();
      }
      setTimeout(() => {
        this.alert = false;
      }, 3000);
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
