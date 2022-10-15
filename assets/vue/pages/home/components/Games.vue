<template>
    <div class="my-8">
        <div
            v-for="(genre, key) in genreGames"
            :key="key"
        >
            <v-divider class="my-10" v-if="key !== 'genre0'"/>
            <v-row align="center" justify="center">
                <v-spacer/>
                <v-col>
                    <h1>{{ genre.description }}</h1>
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
                    <v-carousel-item justify="center" v-for="(chunk, i) in genre.chunkedGames"
                        :key="i"
                    >
                        <v-row
                            align="center"
                            justify="center"
                        >
                            <v-col
                                v-for="(game, j) in chunk"
                                :key="j + i"
                                v-if="!game.node.nsfw"
                                class="pt-10"
                            >
<!--                                TODO / FIX: Ja oke dus vet veel games die vrij nsfw lijken, worden alsnog gedisplayed omdat ze in de db staan als niet-nsfw. what to do now.
                                        update: steam api zegt dat deze games required_age: 0. Weet niet hoe steam dit verder zelf fixt, of dit is met opzet zo gedaan om het niet zo commercieel plausibel/moeilijker te maken om hun games zo maar te stelen.
-->
                                <v-card
                                    width="250"
                                    outlined
                                    elevation="10"
                                >
                                    <v-card-title class="text-center">
                                        <h4>
                                            {{ game.node.name }}
                                        </h4>
                                    </v-card-title>
                                    <v-img
                                        height="150"
                                        width="250"
                                        :src="game.node.headerImage"
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
const {Component, VueComponent, Prop} = require('@/common/VueComponent');
const featuredWinIds = require("../../../../steam/featuredGames.json");

@Component
export default class Games extends VueComponent {

    @Prop({required: true})
    private genreGames = {};


}
</script>
