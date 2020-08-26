<template>
  <div>
    <div v-if="categorySaved" class="alert alert-success" role="alert">{{successText}}</div>
    <div v-if="categoryError" class="alert alert-danger" role="alert">{{errorText}}</div>
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
        <select v-model="categoryArray.id" @change="onChange($event)" class="form-control">
          <option value>Default Value</option>
          <option
            v-for="category in categoryArray"
            :key="category.id"
            :value="category.id"
          >{{category.category}}</option>
        </select>

        <br />
        <div
          class="form-group"
          v-for="property in propertyArray"
          :key="property.id"
          :value="property.id"
        >
          <label for="property">{{property.property}}</label>
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
export default {
  mounted() {
    this.getCategories();
    this.getProperties();
    this.getInventories();
  },
  data() {
    return {
      successText: "",
      errorText: "",
      categorySaved: false,
      categoryError: false,
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
    async formSubmit() {
      const result = await axios
        .post("/inventory", {
          inventory: this.inventory,
          category_id: this.categoryId,
          all_properties: this.allProperties,
        })
        .catch((err) => {});
      if (result) {
        this.categorySaved = true;
        this.successText = "New Inventory Has Been Saved!";
        setTimeout(() => {
          this.categorySaved = false;
        }, 3000);
        this.inventory = "";
        this.getInventories();
      }
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
    async getFormValues() {
      const result = await axios
        .post("/inventory/update", {
          id: this.inventoryChosen,
          inventory: this.selectedInventory,
          category_id: this.editCategory,
          all_properties: this.propertyObjectEdit,
        })
        .catch((err) => {});
      if (result) {
        this.categorySaved = true;
        this.successText = "Inventory Has Been Updated!";
        setTimeout(() => {
          this.categorySaved = false;
        }, 3000);
        this.category = "";
        this.getCategories();
      }
    },
    async erase() {
      const result = await axios
        .post("/inventory/delete", {
          id: this.inventoryChosen,
        })
        .catch((err) => {});
      if (result) {
        this.categorySaved = true;
        this.successText = "Inventory Has Been Deleted!";
        setTimeout(() => {
          this.categorySaved = false;
        }, 3000);
        this.editShow = false;
        this.getInventories();
      }
    },
  },
};
</script>
<style lang="scss" scoped>
#inventory {
  display: flex;
  padding-top: 5%;
  justify-content: space-around;
}
</style>
