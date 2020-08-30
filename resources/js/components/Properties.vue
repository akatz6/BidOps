<template>
  <div>
    <Alert />
    <h1>Add Property</h1>
    <form @submit.prevent="formSubmit">
      <div class="form-group">
        <label for="property">Enter new property</label>
        <input
          type="text"
          class="form-control"
          id="property"
          aria-describedby="propertHelp"
          placeholder="Enter new property"
          v-model="property"
        />
      </div>
      <label for="property">Select type</label>
      <select v-model="type" value="type" id="type" class="form-control">
        <option>Default select</option>
        <option value="numeric" id="numeric">Numeric</option>
        <option value="text" id="text">Text</option>
      </select>
      <br />
      <button type="submit" class="btn btn-primary">Submit</button>
    </form>
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
  mounted() {},
  data() {
    return {
      property: "",
      type: "",
      propertySaved: false,
      propertyError: false,
      errorText: "",
    };
  },
  methods: {
    async formSubmit() {
      const result = await axios
        .post("/property", {
          property: this.property,
          type: this.type,
        })
        .catch((err) => {
          this.propertyError = true;
         
          if (err.response.data === "Property already exists") {
            bus.$emit("errorAlert", err.response.data);
          } else if (err.response.data.errors.property) {
            bus.$emit("errorAlert", err.response.data.errors.property[0]);
          } else if (err.response.data.errors.type) {
            bus.$emit("errorAlert", err.response.data.errors.type[0]);
          } else {
            console.log(err);
          }
        });
      if (result) {
        bus.$emit("successAlert", "Propety has been saved");
        this.property = "";
      }
    },
  },
};
</script>
<style lang="stylus" scoped></style>
