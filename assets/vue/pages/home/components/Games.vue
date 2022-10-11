<template>
    <div class="mt-8">
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
import genre from "@/common/models/genre"
import axios from "axios";

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
        for (let i = 0; i < 5; i++) {
            let rand = Math.floor(Math.random() * (13 - 1 + 1) + 1)
            const thisGenre = await this.getGenre(rand) as genre
            this.categoryGames[`${thisGenre.description}`] = [];
            const length = thisGenre.games.length >= 25 ? 25 : thisGenre.games.length;
            for (let j = 0; j < length - 1; j++) {
                const rand = Math.floor(Math.random() * ((thisGenre.games.length - 2 + 1) + 1))
                const string = thisGenre.games[rand].replace("/api/games/", "");
                const number: number = +string;
                await this.getGameAppDetails(number, thisGenre.description);
                this.$forceUpdate();
            }
        }
    }


    private getGenre(id: number): Promise<genre | void> {
        return axios
            .get(`/api/genres/${id}`)
            .then(response => {
                let Genre: genre = response.data;
                return Genre;
            })
            .catch(error => {
                console.log(error)
            })
    }


    private loadFeaturedGames(): void {
        for (let id in featuredWinIds) {
            this.getGameAppDetails(featuredWinIds[id], "Featured");
        }
    }


    private async getGameAppDetails(id: number, genre: string): Promise<void> {
        return axios
            .get(`/api/games/${id}`)
            .then(response => {
                let foundASpot = false;
                for (let i = 0; i < this.categoryGames[genre].length; i++) {
                    if (this.categoryGames[genre][i].length < 6) {
                        this.categoryGames[genre][i].push(response.data);
                        foundASpot = true;
                    }
                }
                if (!foundASpot) {
                    this.categoryGames[genre].push([response.data]);
                }
            })
            .catch(error => {
                console.log(error);
            })
    }
}
</script>
