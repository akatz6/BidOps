<template>
  <div>
      
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
      categoryArray: [],
      objProperties: {},
      objCategories: {},
      propertiesPerInventory: [],
      categoryPerInventory: [],
    };
  },
  methods: {
    async getViewInventory() {
      const result = await axios
        .get("/viewInventory")
        .catch((err) => console.log(err));

      this.inventoryArray = result.data.inventory;
      this.propertyArray = result.data.property;
      this.categoryArray = result.data.category;
      for (const prop of this.propertyArray) {
        this.objProperties[prop.id] = prop.property;
      }
      for (const cat of this.categoryArray) {
        this.objCategories[cat.id] = cat.category;
      }

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
