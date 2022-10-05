<template>
    <v-app>
        <Header
            :selected-component="selectedComponent"
            :items="items"
            @updated-tab="selectComponent"
        />
        <v-card flat>
            <Home   v-if="selectedComponent === items.Home"
                    @updated-tab="selectComponent"
            />
            <Games  v-if="selectedComponent === items.Games"/>
        </v-card>
    </v-app>
</template>

<script lang="ts">
const {Component, VueComponent} = require('@/common/VueComponent');
import {HomepageTabs} from "@/common/components/Enums/HomepageTabs";
import Header from "@/pages/home/components/Header.vue";
import Home from "@/pages/home/components/Home.vue";
import Games from "@/pages/home/components/Games.vue";

@Component({
    components: {
        Header,
        Home,
        Games
    }
})
export default class App extends VueComponent {
    private user = null;
    private selectedComponent = 'Home';
    private items = HomepageTabs;

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
}
</script>
