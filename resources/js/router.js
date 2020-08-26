import Vue from "vue";
import VueRouter from "vue-router";
import ViewInventory from "./components/ViewInventory.vue";
import Inventory from "./components/Inventory.vue";
import Category from "./components/Category.vue";
import Properties from "./components/Properties.vue";

Vue.use(VueRouter);

const routes = [
    { path: "/", name: "view inventory", component: ViewInventory },
    { path: "/inventory", name: "inventory", component: Inventory },
    { path: "/category", name: "category", component: Category },
    { path: "/properties", name: "properties", component: Properties }
];

const router = new VueRouter({
    mode: "history",
    routes
});

export default router;
