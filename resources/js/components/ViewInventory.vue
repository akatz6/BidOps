<template>
  <div>
    <label for="category">Filter on a category</label>
    <select v-model="category" @change="onChange($event)" class="form-control">
      <option>Default Value</option>
      <option
        v-for="category in categoryArray"
        :key="category.id"
        :value="category.id"
      >{{category.category}}</option>
    </select>
    <br />
    <label for="property">Sum on property</label>
    <select v-model="property" @change="onChangeProperty($event)" class="form-control">
      <option>Default Value</option>
      <option
        v-for="property in propertySumArray"
        :key="property.id"
        :value="property.id"
      >{{property.property}}</option>
    </select>
    <button class="btn btn-dark" @click="getSum">See Sum</button>
    <p v-if="seeSum">{{sum}}</p>
    <table class="table table-striped">
      <thead>
        <tr>
          <th scope="col">Inventory Item</th>
          <th scope="col">Category</th>
          <th scope="col">Properties</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(item, index) in inventoryArray" :key="item.id">
          <th scope="row">{{item.inventory}}</th>
          <td>{{categoryPerInventory[index]}}</td>
          <td>{{propertiesPerInventory[index]}}</td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
import axios from "axios";
export default {
  mounted() {
    this.getViewInventory();
  },
  data() {
    return {
      inventoryArray: [],
      propertyArray: [],
      propertySumArray: [],
      categoryArray: [],
      objProperties: {},
      objCategories: {},
      propertiesPerInventory: [],
      categoryPerInventory: [],
      propertyId: -1,
      sum: 0,
      seeSum: false,
      propertySum: "",
    };
  },
  methods: {
    async getViewInventory() {
      const result = await axios
        .get("/viewInventory")
        .catch((err) => console.log(err));
      this.setPropertiesCategories(result);
    },
    async onChange(e) {
      const id = e.target.value;
      if (id === "Default Value") {
        this.getViewInventory();
        this.propertyId = -1;
        return;
      }
      this.propertyId = id;
      const result = await axios
        .get(`/viewInventoryWithId/${id}`)
        .catch((err) => console.log(err));
      this.setPropertiesCategories(result);
    },
    async onChangeProperty(e) {
      this.propertySum = e.target.value;
    },
    async getSum() {
      this.sum = 0;
      const id = this.propertySum;
      this.seeSum = true;
      const result = await axios
        .get(`/sumOnProperty/${id}/${this.propertyId}`)
        .catch((err) => console.log(err));
      this.sum = result.data;
    },
    setPropertiesCategories(result) {
      this.inventoryArray = result.data.inventory;
      this.propertyArray = result.data.property;
      this.categoryArray = result.data.category;
      this.propertySumArray = result.data.propertySum;
      for (const prop of this.propertyArray) {
        this.objProperties[prop.id] = prop.property;
      }

      for (const cat of this.categoryArray) {
        this.objCategories[cat.id] = cat.category;
      }
      this.propertiesPerInventory = [];
      this.categoryPerInventory = [];
      this.inventoryArray.forEach((element) => {
        element.all_properties = JSON.parse(element.all_properties);
        let str = "";
        for (const val in element.all_properties) {
          str += `${this.objProperties[val]}: ${element.all_properties[val]}, `;
        }
        this.propertiesPerInventory.push(str.slice(0, str.length - 2));
        this.categoryPerInventory.push(this.objCategories[element.category_id]);
      });
    },
  },
};
</script>
<style lang="stylus" scoped></style>
