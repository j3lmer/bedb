<template>
    <div>
        <v-card
            app
            elevation="0"
        >
            <v-row>
                <v-spacer/>
                <v-col cols="6">
                    <v-card-title class="text-center justify-center py-6">
                        <a
                            class="h1 font-weight-bold text-h2 title"
                            href="/"
                        >
                            Be-db
                        </a>
                    </v-card-title>
                </v-col>
                <v-col>
                    <v-row
                        class="pa-2"
                        align="center"
                    >
                        <v-spacer/>
                        <v-col cols="6">
                            <!--                            />-->
                            <v-autocomplete
                                v-model="select"
                                :search-input.sync="search"
                                :loading="loading"
                                :items="queryResults"
                                cache-items
                                class="mx-4"
                                hide-no-data
                                hide-details
                                label="Find a game..."
                                solo
                                item-text="name"
                                item-value="id"
                            ></v-autocomplete>
                        </v-col>

                        <v-col
                            class="text-right pt-0 mr-8"
                            cols="1"
                        >
                            <a :href="isLoggedIn ? '/settings' : '/entry'">
                                <v-icon
                                    large
                                    class="pb-4"
                                >
                                    mdi-account
                                </v-icon>
                            </a>
                        </v-col>
                    </v-row>
                </v-col>
            </v-row>
            <v-tabs
                v-if="items"
                v-model="tab"
                grow
                hide-details
            >
                <v-tab
                    v-for="(item, i) in items"
                    :key="i"
                    @click="selectComponent(item)"
                >
                    {{ item }}
                </v-tab>
            </v-tabs>
            <v-divider/>
        </v-card>
    </div>
</template>

<script lang="ts">
const {Component, VueComponent, Prop, Watch} = require('@/common/VueComponent');
import {HomepageTabs} from '@/common/components/Enums/HomepageTabs';
import axios from "axios";
import base from "@/common/components/base";

@Component
export default class Header extends VueComponent {

    private drawer = false;
    private tab = null;

    @Prop({required: false})
    private items;

    @Prop({required: false})
    private selectedComponent: string

    @Prop({required: true})
    private isLoggedIn: boolean

    private select = null;
    private search = null;
    private queryResults = [];
    private loading = false;

    @Watch('selectedComponent')
    private updateTab(Value: string): void {
        switch (Value) {
            case HomepageTabs.Home:
                this.tab = 0;
                break;
            case HomepageTabs.Games:
                this.tab = 1;
        }
    }

    public created() {
        this.updateTab(this.selectedComponent);
    }

    @Watch('search')
    private async searchForGames(s: string) {
        const response = await axios.post(`${base.getBase()}getGame`, {
            "gameName": s
        });

        console.log(response.data);
        this.queryResults = response.data;
    }


    private selectComponent(component: any): void {
        this.$cookies.set('page', component)
        this.$emit('updated-tab', component);
    }

    private closeDrawer(component: any): void {
        this.selectComponent(component);
        this.drawer = false;
    }

    @Watch('select')
    private redirectToGame(select: any): void {
        window.location.replace(`/game/${select}`);
    }

};
</script>

<style scoped>
.title {
    text-decoration: none;
    color: black;
}

.title:hover {
    text-decoration: none;
    color: black;
}

</style>