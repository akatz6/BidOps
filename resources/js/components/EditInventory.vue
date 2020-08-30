<template>
  <div>
    <div v-if="alert">
      <Alert />
    </div>
    <div>
      <form @submit.prevent="getFormValues">
        <label for="Inventory">Choose a Inventory</label>
        <select v-model="inventoryChosen" @change="onChangeEdit($event)" class="form-control">
          <option value>Default Value</option>
          <option
            v-for="inventory in inventoryArray"
            :key="inventory.id"
            :value="inventory.id"
          >{{inventory.inventory}}</option>
        </select>
        <div v-if="editShow">
          <br />
          <select v-model="editCategory" @change="onChange($event)" class="form-control">
            <option value>Default Value</option>
            <option
              v-for="category in categoryArray"
              :key="category.id"
              :value="category.id"
              v-bind="editCategory"
            >{{category.category}}</option>
          </select>
          <br />
          <input type="text" v-model="selectedInventory" />
          <br />
          <div
            class="form-group"
            v-for="(property, index) in propertyObjectEdit"
            :key="property.id"
            :value="property.id"
          >
            <label for="inventory">{{propertiesObj[index][0]}}</label>
            <div>
              <input
                v-if="propertiesObj[index][1] === 'text'"
                type="text"
                placeholder="Enter text here"
                class="form-control"
                :id="propertiesObj[index][2]"
                :value="property"
                @change="editProperty($event)"
              />
              <input
                v-else
                type="number"
                placeholder="Enter number here"
                class="form-control"
                :id="propertiesObj[index][2]"
                :value="property"
                @change="editProperty($event)"
              />
            </div>
          </div>

          <button type="submit" class="btn btn-success">Edit</button>
          <button type="button" @click="erase" class="btn btn-danger">Delete</button>
        </div>
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
    this.getProperties();
    this.getInventories();
  },
  data() {
    return {
      categoryArray: [],
      propertyArray: [],
      inventoryArray: [],
      allProperties: {},
      propertyInfo: "",
      categoryId: "",
      inventory: "",
      inventoryChosen: "",
      propertiesObj: {},
      selectedInventory: "",
      propertyObjectEdit: [],
      propertyObjectKeys: [],
      propertyObjectValues: [],
      propertiesArr: [],
      editShow: false,
      editCategory: "",
      alert: false,
    };
  },
  methods: {
    async getInventories() {
      const result = await axios
        .get("/inventories")
        .catch((error) => console.log(error));
      this.inventoryArray = result.data;
    },
    async getCategories() {
      const result = await axios
        .get("/categories")
        .catch((error) => console.log(error));
      this.categoryArray = result.data;
    },
    async getProperties() {
      const result = await axios
        .get("/propeties")
        .catch((error) => console.log(error));
      this.propertyArray = result.data;
    },
    addProperty(e) {
      this.allProperties[e.target.id] = e.target.value;
    },
    onChange(event) {
      this.categoryId = event.target.value;
    },
    async onChangeEdit(event) {
      const inventoryId = event.target.value;
      this.alert = true;
      const result = await axios
        .get(`/getInventoryById/${inventoryId}`)
        .catch((err) => {
          debugger;
          if (err.response.data === "Inventory already exists") {
            bus.$emit("errorAlert", "Inventory already exists");
          } else if (err.response.data.errors.all_properties) {
            bus.$emit("errorAlert", err.response.data.errors.all_properties[0]);
          } else if (err.response.data.errors.category_id) {
            debugger;
            bus.$emit("errorAlert", "Category needs to be selected");
          } else if (err.response.data.errors.inventory) {
            bus.$emit("errorAlert", err.response.data.errors.inventory[0]);
          } else {
            console.log(err);
          }
        });
      const data = await result.data;
      for (const property of data.properties) {
        this.propertiesObj[property.id] = [
          property.property,
          property.type,
          property.id,
        ];
      }
      this.propertiesArr = data.properties;
      this.selectedInventory = data.inventory.inventory;
      this.propertyObjectEdit = JSON.parse(data.inventory.all_properties);
      this.propertyObjectKeys = Object.keys(this.propertyObjectEdit);
      this.propertyObjectValue = Object.values(this.propertyObjectEdit);
      this.editShow = true;
      this.editCategory = data.category.id;
      setTimeout(() => {
        this.alert = false;
      }, 3000);
    },
    editProperty(event) {
      this.propertyObjectEdit[event.target.id] = event.target.value;
    },
    async getFormValues() {
      this.alert = true;
      const result = await axios
        .post("/inventory/update", {
          id: this.inventoryChosen,
          inventory: this.selectedInventory,
          category_id: this.editCategory,
          all_properties: this.propertyObjectEdit,
        })
        .catch((err) => {
          if (err.response.data === "Inventory already exists") {
            bus.$emit("errorAlert", "Inventory already exists");
          } else if (err.response.data.errors.all_properties) {
            bus.$emit("errorAlert", err.response.data.errors.all_properties[0]);
          } else if (err.response.data.errors.category_id) {
            bus.$emit("errorAlert", "Category needs to be selected");
          } else if (err.response.data.errors.inventory) {
            bus.$emit("errorAlert", err.response.data.errors.inventory[0]);
          } else {
            console.log(err);
          }
        });
      if (result) {
        bus.$emit("successAlert", "Inventory Has Been Updated!");
        this.getCategories();
      }
      setTimeout(() => {
        this.alert = false;
      }, 3000);
    },
    async erase() {
      this.alert = true;
      const result = await axios
        .post("/inventory/delete", {
          id: this.inventoryChosen,
        })
        .catch((err) => {});
      if (result) {
        bus.$emit("successAlert", "Inventory Has Been Deleted!");
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
</style>
