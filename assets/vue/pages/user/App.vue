<template>
    <v-app>
        <Header :is-logged-in="this.user !== undefined"/>
        <Settings :user="user"/>
    </v-app>
</template>

<script lang="ts">
const {Component, VueComponent} = require('@/common/VueComponent')
import Header from "@/pages/common/Header.vue"
import Settings from "./components/Settings.vue"
@Component({
    components: {
        Header,
        Settings
    }
})
export default class App extends VueComponent {
    private user: any;
    public created(): void {
        this.user = (window as any).user;
    }
}
</script>
