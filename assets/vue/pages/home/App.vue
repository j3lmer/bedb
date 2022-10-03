<template>
    <v-app>
        <Header
            :selected-component="selectedComponent"
            :items="items"
            @updated-tab="selectComponent"
        />
    </v-app>
</template>

<script lang="ts">
const {Component, VueComponent} = require('@/common/VueComponent');
import Header from "@/pages/home/components/Header.vue";

@Component({
    components: {
        Header
    }
})
export default class App extends VueComponent {
    private user = null;
    private selectedComponent = 'Home';
    private items = ['Home', 'Test'];

    public created(): void {
        let cookiePage = this.$cookies.get('page');

        if ((window as any).user !== null) {
            this.user = (window as any).user;
        }

        if (cookiePage) {
            this.selectComponent(cookiePage);
        }
    }

    private selectComponent(component: any): void {
        this.selectedComponent = component;
    }
};
</script>
