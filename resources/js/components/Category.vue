<template>
  <div>
    <div v-if="categorySaved" class="alert alert-success" role="alert">{{successText}}</div>
    <div v-if="categoryError" class="alert alert-danger" role="alert">{{errorText}}</div>
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
      <form>
        <label for="category">Choose a category</label>
        <select v-model="categoryArray.id" @change="onChange($event)" class="form-control">
          <option value>Default Value</option>
          <option
            v-for="category in categoryArray"
            :key="category.id"
            :value="category.id"
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
export default {
  mounted() {
    this.getCategories();
  },
  data() {
    return {
      category: "",
      categoryError: false,
      categorySaved: false,
      errorText: "",
      categoryArray: [],
      selected: "",
      id: "",
      myOption: {},
      successText: ""
    };
  },
  methods: {
    async formSubmit() {
      const result = await axios
        .post("/category", {
          category: this.category,
        })
        .catch((err) => {
          this.categoryError = true;
          if (err.response.data === "Category already exists") {
            this.errorText = err.response.data;
          } else if (err.response.data.errors.category) {
            this.errorText = err.response.data.errors.category[0];
          } else {
            console.log(err);
          }
          setTimeout(() => {
            this.categoryError = false;
          }, 3000);
        });
      if (result) {
        this.categorySaved = true;
        this.successText = "New Category Has Been Saved!"
        setTimeout(() => {
          this.categorySaved = false;
        }, 3000);
        this.category = "";
        this.getCategories();
      }
    },
    async getCategories() {
      const result = await axios
        .get("/categories")
        .catch((error) => console.log(error));
      this.categoryArray = result.data;
    },
    onChange(event) {
      this.id = event.target.value;
      this.selected = event.target[this.id].text;
    },
    async edit() {
      const result = await axios
        .post("/category/update", {
          selected: this.selected,
          id: this.id,
        })
        .catch((error) => {
          this.categoryError = true;
          this.errorText = "Please select Category";
          setTimeout(() => {
            this.categoryError = false;
          }, 3000);
        });
      this.getCategories();
      this.selected = "";
       if (result) {
        this.categorySaved = true;
        this.successText = "Category has been Updated!"
        setTimeout(() => {
          this.categorySaved = false;
        }, 3000);
        this.category = "";
        this.getCategories();
      }
    },
    async erase() {
      const result = await axios
        .post("/category/delete", {
          id: this.id,
        })
        .catch((error) => {
          this.categoryError = true;
          this.errorText = "Please select Category";
          setTimeout(() => {
            this.categoryError = false;
          }, 3000);
        });
      this.getCategories();
      this.selected = "";
       if (result) {
        this.categorySaved = true;
        this.successText = "Category Has Been Deleted!"
        setTimeout(() => {
          this.categorySaved = false;
        }, 3000);
        this.category = "";
        this.getCategories();
      }
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
