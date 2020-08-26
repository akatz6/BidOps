<template>
    <div id="app">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <button
                class="navbar-toggler"
                type="button"
                data-toggle="collapse"
                data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent"
                aria-expanded="false"
                aria-label="Toggle navigation"
            >
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item active">
                        <a class="nav-link">
                            <router-link
                                :to="{ name: 'view inventory' }"
                                class="navbar-brand"
                                activeClass="exact-active"
                                ><a class="nav-link"
                                    >View Inventory</a
                                ></router-link
                            >
                        </a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link">
                            <router-link
                                :to="{ name: 'inventory' }"
                                class="navbar-brand"
                                activeClass="active"
                                ><a class="nav-link"
                                    >Edit or Add Inventory</a
                                ></router-link
                            >
                        </a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link">
                            <router-link
                                :to="{ name: 'category' }"
                                class="navbar-brand"
                                activeClass="active"
                                ><a class="nav-link"
                                    >Edit or Add Category</a
                                ></router-link
                            >
                        </a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link">
                            <router-link
                                :to="{ name: 'properties' }"
                                class="navbar-brand"
                                activeClass="active"
                                ><a class="nav-link"
                                    >Add Property</a
                                ></router-link
                            >
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
        <router-view></router-view>
    </div>
</template>

<script>
import axios from "axios";

export default {
    mounted() {},
    data() {
        return {};
    },
    methods: {},
};
</script>
<style lang="stylus" scoped></style>
