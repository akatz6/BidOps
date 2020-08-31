<template>
    <div>
        <div v-if="alert">
            <Alert />
        </div>
        <div id="inventory">
            <form @submit.prevent="formSubmit">
                <div class="form-group">
                    <label for="inventory">Enter new inventory</label>
                    <input
                        type="text"
                        class="form-control"
                        id="inventory"
                        aria-describedby="inventoryHelp"
                        placeholder="Enter new Inventory"
                        v-model="inventory"
                    />
                </div>
                <br />
                <label for="category">Choose a category</label>
                <select
                    v-model="categoryArray.id"
                    @change="onChange($event)"
                    class="form-control"
                >
                    <option value>Default Value</option>
                    <option
                        v-for="category in categoryArray"
                        :key="category.id"
                        :value="category.id"
                        >{{ category.category }}</option
                    >
                </select>

                <br />
                <div
                    class="form-group"
                    v-for="property in propertyArray"
                    :key="property.id"
                    :value="property.id"
                >
                    <label for="property">{{ property.property }}</label>
                    <input
                        v-if="property.type === 'text'"
                        type="text"
                        placeholder="Enter text here"
                        class="form-control"
                        :id="property.id"
                        v-bind="propertyInfo"
                        @change="addProperty($event)"
                    />
                    <input
                        v-else
                        type="number"
                        placeholder="Enter number here"
                        class="form-control"
                        :id="property.id"
                        v-bind="propertyInfo"
                        @change="addProperty($event)"
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
import {getInventories, getCategories, getProperties} from './inventory';
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
      allProperties: {},
      propertyInfo: "",
      categoryId: "",
      inventory: "",
      propertiesObj: {},
      selectedInventory: "",
      propertyObjectEdit: [],
      propertyObjectKeys: [],
      propertiesArr: [],
      editShow: false,
      editCategory: "",
      alert: false,
    };
  },
  methods: {
    async getInventories() {
      this.inventoryArray =  await getInventories();
      bus.$emit("newInventory");
    },
    async getCategories() {
      this.categoryArray = await getCategories();
    },
    async getProperties() {
      this.propertyArray = await getProperties();
    },
    async formSubmit() {
      this.alert = true;
      const result = await axios
        .post("/inventory", {
          inventory: this.inventory,
          category_id: this.categoryId,
          all_properties: this.allProperties,
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
        bus.$emit("successAlert", "New Inventory Has Been Saved!");
        this.getInventories();
      }
      setTimeout(() => {
        this.alert = false;
      }, 3000);
    },
    addProperty(e) {
      this.allProperties[e.target.id] = e.target.value;
    },
    onChange(event) {
      this.categoryId = event.target.value;
    },
    async onChangeEdit(event) {
      const inventoryId = event.target.value;
      const result = await axios
        .get(`/getInventoryById/${inventoryId}`)
        .catch((err) => {});
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
    },
    editProperty(event) {
      this.propertyObjectEdit[event.target.id] = event.target.value;
    },
  },
};
</script>
<style lang="scss" scoped></style>
