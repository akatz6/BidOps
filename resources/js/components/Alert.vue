<template>
  <div>
    <div v-if="saved" class="alert alert-success" role="alert">{{successText}}</div>
    <div v-if="error" class="alert alert-danger" role="alert">{{errorText}}</div>
  </div>
</template>

<script>
import axios from "axios";
import { bus } from "../app";
export default {
  props: ["errors"],
  created() {
    bus.$on("errorAlert", (data) => {
      this.errorText = data;
      this.error = true;
      setTimeout(() => {
        this.error = false;
      }, 3000);
    });
    bus.$on("successAlert", (data) => {
      this.saved = true;
      this.successText = data;
      setTimeout(() => {
        this.saved = false;
      }, 3000);
    });
  },
  mounted() {},
  data() {
    return {
      error: false,
      saved: false,
      errorText: "",
      successText: "",
    };
  },
  methods: {},
};
</script>
<style lang="scss" scoped>
</style>
