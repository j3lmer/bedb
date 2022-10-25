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
                        <h1 class="font-weight-bold text-h2 title">
                            Be-db
                        </h1>
                    </v-card-title>
                </v-col>
                <v-col>
                    <v-row
                        class="pa-2"
                        align="center"
                    >
                        <v-spacer/>
                        <v-col cols="5">
                            <v-autocomplete
                                label="Find a game..."
                                clearable
                                solo
                                v-model="searchNameString"
                                :items="queryResults"
                                @change="searchForGames"
                            />
                        </v-col>

                        <v-col
                            class="text-right pt-0 mr-8"
                            cols="1"
                        >
                            <v-icon
                                large
                                class="pb-4"
                            >
                                mdi-account
                            </v-icon>
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
            <v-divider />
        </v-card>
    </div>
</template>

<script lang="ts">
const {Component, VueComponent, Prop, Watch} = require('@/common/VueComponent');
import {HomepageTabs} from '@/common/components/Enums/HomepageTabs';


@Component
export default class Header extends VueComponent {

    private drawer = false;
    private tab = null;

    @Prop({required: false})
    private items;

    @Prop({required: false})
    private selectedComponent: string

    private searchNameString = '';
    private queryResults = [];

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

    // deze functie elke keer callen wanneer er iets veranderd aan de string in de zoekbalk,
    // graphql query doen voor games met een naam die lijkt op wat er in word getypt (naam, afbeelding?)
    // items die graphql returned in een array zetten en die weer aan de autocomplete geven
    private searchForGames()
    {
        console.log('test')
    }


    private selectComponent(component: any): void {
        this.$cookies.set('page', component)
        this.$emit('updated-tab', component);
    }

    private closeDrawer(component: any): void {
        this.selectComponent(component);
        this.drawer = false;
    }

};
</script>