<template>
  <div>
    <div v-if="propertySaved" class="alert alert-success" role="alert">New Property Has Been Saved!</div>
    <div v-if="propertyError" class="alert alert-danger" role="alert">{{errorText}}</div>
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
export default {
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
          if(err.response.data === "Property already exists"){
              this.errorText = err.response.data;
          } else if (err.response.data.errors.property) {
            this.errorText = err.response.data.errors.property[0];
          } else if (err.response.data.errors.type) {
            this.errorText = err.response.data.errors.type;
          } else {
            console.log(err);  
          }
          setTimeout( () =>  {
            this.propertyError = false;
          }, 3000);
        });
      if (result) {
        this.propertySaved = true;
        setTimeout( () =>  {
          this.propertySaved = false;
        }, 3000);
        this.property = "";
      }
    },
  },
};
</script>
<style lang="stylus" scoped></style>
