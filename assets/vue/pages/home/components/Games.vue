<template>
    <div class="my-8">
        <div
            v-for="(category, genre) in categoryGames"
            :key="genre"
        >
            <v-divider class="my-10" v-if="genre !== 'Featured'"/>
            <v-row align="center" justify="center">
                <v-spacer/>
                <v-col>
                    <h1>{{ genre }}</h1>
                </v-col>
                <v-spacer/>
                <v-spacer/>
            </v-row>
            <v-row class="mx-4">
                <v-carousel
                    cycle
                    hide-delimiters
                    show-arrows-on-hover
                    height="300"
                >
                    <v-carousel-item
                        v-for="(chunk, i) in category"
                        :key="i"
                        justify="center"
                    >
                        <v-row
                            align="center"
                            justify="center"
                        >
                            <v-col
                                v-for="(game, j) in chunk"
                                :key="j + i"
                                class="pt-10"
                            >
                                <v-card
                                    width="250"
                                    outlined
                                    elevation="10"
                                >
                                    <v-card-title class="text-center">
                                        <h4>
                                            {{ game.name }}
                                        </h4>
                                    </v-card-title>
                                    <v-img
                                        height="150"
                                        width="250"
                                        :src="game.headerImage"
                                    ></v-img>
                                </v-card>
                            </v-col>
                        </v-row>
                    </v-carousel-item>
                </v-carousel>
            </v-row>
        </div>
    </div>
</template>

<script lang="ts">
const {Component, VueComponent} = require('@/common/VueComponent');
const featuredWinIds = require("../../../../steam/featuredGames.json");
import genre from "@/common/models/genre";
import axios from "axios";
import { buildSchema } from "graphql";


@Component
export default class Games extends VueComponent {

    private categoryGames = {
        'Featured': [],
    }

    mounted(): void {
        this.loadFeaturedGames();
        this.loadGames();
    }

    //TODO / nice to have: het zo maken dat genres en games worden opgehaald bij scroll

    private async loadGames(): Promise<void> {

    }

}
</script>
