<template>
    <v-content>
        <section id="hero">
            <v-row no-gutters>
                <v-img
                    :min-height="'calc(100vh - ' + $vuetify.application.top + 'px)'"
                    src="https://images.unsplash.com/photo-1487017159836-4e23ece2e4cf?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=1951&q=80"
                >
                    <v-theme-provider dark>
                        <v-container fill-height>
                            <v-row
                                align="center"
                                class="white--text mx-auto"
                                justify="center"
                            >
                                <v-col
                                    class="white--text text-center"
                                    cols="12"
                                    tag="h1"
                                >
                                    <span
                                        :class="[$vuetify.breakpoint.smAndDown ? 'display-1' : 'display-2']"
                                        class="font-weight-light"
                                    >
                                      WELCOME TO
                                    </span>
                                    <br>
                                    <span
                                        :class="[$vuetify.breakpoint.smAndDown ? 'display-3': 'display-4']"
                                        class="font-weight-black"
                                    >
                                        Be-DB
                                    </span>
                                </v-col>

                                <v-btn
                                    class="align-self-end"
                                    fab
                                    outlined
                                    @click="$vuetify.goTo('#about-me')"
                                >
                                    <v-icon>mdi-chevron-double-down</v-icon>
                                </v-btn>
                            </v-row>
                        </v-container>
                    </v-theme-provider>
                </v-img>
            </v-row>
        </section>

        <section id="about-me">
            <div class="py-12"></div>

            <v-container class="text-center">
                <h2 class="display-2 font-weight-bold mb-3">ABOUT Be-DB</h2>

                <v-responsive
                    class="mx-auto mb-8"
                    width="56"
                >
                    <v-divider class="mb-1"></v-divider>
                    <v-divider></v-divider>
                </v-responsive>

                <v-responsive
                    class="mx-auto title font-weight-light mb-8"
                    max-width="720"
                >
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Iusto cupiditate sint possimus quidem atque harum excepturi nemo velit tempora! Enim inventore fuga, qui ipsum eveniet facilis obcaecati corrupti asperiores nam
                </v-responsive>

                <v-avatar
                    class="elevation-12 mb-12"
                    size="128"
                >
                    LOGO
                </v-avatar>

                <div></div>

                <v-btn
                    color="grey"
                    outlined
                    large
                    @click="selectComponent(items.Games)"
                >
            <span class="grey--text text--darken-1 font-weight-bold">
              Games
            </span>
                </v-btn>
            </v-container>

            <div class="py-12"></div>
        </section>

        <section
            id="features"
            class="grey lighten-3"
        >
            <div class="py-12"></div>

            <v-container class="text-center">
                <h2 class="display-2 font-weight-bold mb-3">FEATURES</h2>

                <v-responsive
                    class="mx-auto mb-12"
                    width="56"
                >
                    <v-divider class="mb-1"></v-divider>

                    <v-divider></v-divider>
                </v-responsive>

                <v-row>
                    <v-col
                        v-for="({ icon, title, text }, i) in features"
                        :key="i"
                        cols="12"
                        md="4"
                    >
                        <v-card
                            class="py-12 px-4"
                            color="grey lighten-5"
                            flat
                        >
                            <v-theme-provider dark>
                                <div>
                                    <v-avatar
                                        color="primary"
                                        size="88"
                                    >
                                        <v-icon
                                            large
                                            v-text="icon"
                                        ></v-icon>
                                    </v-avatar>
                                </div>
                            </v-theme-provider>

                            <v-card-title
                                class="justify-center font-weight-black text-uppercase"
                                v-text="title"
                            ></v-card-title>

                            <v-card-text
                                class="subtitle-1"
                                v-text="text"
                            >
                            </v-card-text>
                        </v-card>
                    </v-col>
                </v-row>
            </v-container>

            <div class="py-12"></div>
        </section>

        <section id="stats">
            <v-parallax
                :height="$vuetify.breakpoint.smAndDown ? 700 : 500"
                src="https://images.unsplash.com/photo-1510915228340-29c85a43dcfe?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=1950&q=80"
            >
            </v-parallax>
        </section>
    </v-content>
</template>

<script lang="ts">
const {Component, VueComponent} = require('@/common/VueComponent');
import {HomepageTabs} from "@/common/components/Enums/HomepageTabs";

@Component
export default class Home extends VueComponent {
    private items = HomepageTabs;
    private articles = [
        {
            src: 'https://images.unsplash.com/photo-1423784346385-c1d4dac9893a?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=1950&q=80',
            title: 'Mobile first & Responsive',
            text: 'Phasellus lorem enim, luctus ut velit eget, convallis egestas eros. Sed ornare ligula eget tortor tempor, quis porta tellus dictum.',
        },
        {
            src: 'https://images.unsplash.com/photo-1475938476802-32a7e851dad1?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=1950&q=80',
            title: 'Think outside the box',
            text: 'Nam ut leo ipsum. Maecenas pretium aliquam feugiat. Aenean vel tempor est, vitae tincidunt risus. Sed sodales vestibulum nibh.',
        },
        {
            src: 'https://images.unsplash.com/photo-1416339442236-8ceb164046f8?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=1892&q=80',
            title: 'Small changes, big difference',
            text: 'Vestibulum in dictum velit, in rhoncus nibh. Maecenas neque libero, interdum a dignissim in, aliquet vitae lectus. Phasellus lorem enim, luctus ut velit eget.',
        },
    ];

    private features = [
        {
            icon: 'mdi-account-group-outline',
            title: 'Vibrant Community',
            text: 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Iusto cupiditate sint possimus quidem atque harum excepturi nemo velit tempora! Enim inventore fuga, qui ipsum eveniet facilis obcaecati corrupti asperiores nam',
        },
        {
            icon: 'mdi-update',
            title: 'Lorem ipsum',
            text: 'Sed ut elementum justo. Suspendisse non justo enim. Vestibulum cursus mauris dui, a luctus ex blandit. Lorem ipsum dolor sit amet consectetur adipisicing elit. qui ipsum eveniet facilis obcaecati corrupti consectetur adipisicing elit.',
        },
        {
            icon: 'mdi-shield-outline',
            title: 'Lorem ipsum',
            text: 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Iusto cupiditate sint possimus quidem atque harum excepturi nemo velit tempora! Enim inventore fuga, qui ipsum eveniet facilis obcaecati corrupti asperiores nam',
        },
    ]

    private selectComponent(component: any): void {
        this.$cookies.set('page', component)
        this.$emit('updated-tab', component);
    }
}
</script>
