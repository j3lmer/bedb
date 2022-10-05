<template>
    <div class="mb-8">
<!--        <div v-if="$vuetify.breakpoint.mobile">-->
<!--            <v-row class="ma-5">-->
<!--                <v-spacer/>-->
<!--                <v-app-bar-nav-icon-->
<!--                    @click.stop="drawer = !drawer">-->
<!--                </v-app-bar-nav-icon>-->
<!--            </v-row>-->
<!--            <v-navigation-drawer-->
<!--                v-model="drawer"-->
<!--                absolute-->
<!--                temporary-->
<!--            >-->
<!--                <v-list-->
<!--                    nav-->
<!--                    dense-->
<!--                >-->
<!--                    <v-list-item-group-->
<!--                        v-model="group"-->
<!--                        active-class="indigo&#45;&#45;text text&#45;&#45;accent-4"-->
<!--                    >-->
<!--                        <v-list-item-->
<!--                            v-for="(item, i) in items"-->
<!--                            :key="i"-->
<!--                            @click="closeDrawer(item)"-->
<!--                        >-->
<!--                            <v-list-item-title>{{ item }}</v-list-item-title>-->
<!--                        </v-list-item>-->
<!--                    </v-list-item-group>-->
<!--                </v-list>-->
<!--            </v-navigation-drawer>-->
<!--        </div>-->

        <v-card
            v-if="items"
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

<!-- v-if="!$vuetify.breakpoint.mobile"-->
            <v-tabs
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
<!--            v-if="$vuetify.breakpoint.mobile"-->
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

    @Prop({required: true})
    private items;

    @Prop({required: true})
    private selectedComponent

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