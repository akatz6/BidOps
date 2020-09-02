<template>
  <div>
    <div v-if="categorySaved" class="alert alert-success" role="alert">{{successText}}</div>
    <div v-if="categoryError" class="alert alert-danger" role="alert">{{errorText}}</div>
    <div id="inventory">
      <AddInventory />
      <EditInventory />
    </div>
  </div>
</template>

<script>
import axios from "axios";
import AddInventory from "./AddInventory.vue";
import EditInventory from "./EditInventory.vue";
export default {
  components: {
    AddInventory,
    EditInventory,
  },
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
