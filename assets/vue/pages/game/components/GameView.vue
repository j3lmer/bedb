<template>
    <v-container v-if="gameExists">
        <v-row class="pa-5" justify="space-between">
            <v-col class="text-center">
                <h1>{{ game.name }}</h1>
            </v-col>
            <v-col>
                <v-spacer/>
                <v-col
                    align-self="end"
                    v-if="user"
                >
                    <v-row>
                        <v-spacer/>
                        <v-col cols="6" align="right">
                            <ReviewDialog @review-made="getReviews"/>
                        </v-col>
                    </v-row>
                </v-col>
            </v-col>
        </v-row>
        <v-row>
            <v-col id="game" cols="6">
                <v-row>
                    <v-col id="imageCarousel">
                        <v-carousel
                            cycle
                            hide-delimiter-background
                            show-arrows-on-hover
                        >
                            <v-carousel-item v-for="(screenshot, i) in game.screenshots.edges" :key="i">
                                <v-img :src="screenshot.node.thumbnail"></v-img>
                            </v-carousel-item>
                        </v-carousel>
                    </v-col>

                </v-row>
                <v-divider class="my-5"/>
                <v-row
                    justify="space-around"
                >
                    <v-col id="userScore" class="d-flex justify-center">
                        <v-sheet
                            class="text-center"
                            elevation="1"
                            height="100"
                            width="100"
                        >
                            User score
                        </v-sheet>
                    </v-col>
                    <v-col id="metaScore" class="d-flex justify-center" v-if="game.metacritic">
                        <v-sheet
                            class="text-center"
                            elevation="1"
                            height="100"
                            width="100"
                            :color="getColor(game.metacritic.score)"
                        >
                            Meta score
                            {{ game.metacritic.score }}
                        </v-sheet>
                    </v-col>
                    <v-col id="steamRecommendations" class="d-flex justify-center">
                        <v-sheet
                            class="text-center"
                            elevation="1"
                            height="100"
                            width="100"
                            :color="getColor(game.recommendationsTotal)"
                        >
                            SteamScore
                            <br/>
                            {{ game.recommendationsTotal }}
                        </v-sheet>
                    </v-col>
                </v-row>
                <v-divider class="my-5"/>
                <v-row>
                    <v-col id="detailedDescription" v-html="game.detailedDescription"></v-col>
                </v-row>
                <v-divider class="mt-5 mb-3"/>
                <v-row>
                    <v-col>
                        Supported languages:
                        <v-col>
                            <p v-html="game.supportedLanguages"></p>
                        </v-col>
                    </v-col>
                    <v-divider vertical v-if="game.website"/>
                    <v-col v-if="game.website">
                        Website:
                        <v-col>
                            <a :href="game.website">{{ game.website }}</a>
                        </v-col>
                    </v-col>
                </v-row>
                <v-divider class="my-3"/>
                <v-row>
                    <v-col>
                        Publishers:
                        <v-col>
                            <p v-for="(pub, i) in game.publishers" :key="i">{{ pub }}</p>
                        </v-col>
                    </v-col>
                    <v-divider vertical/>
                    <v-col>
                        Developers:
                        <v-col>
                            <p v-for="(dev, i) in game.developers" :key="i">{{ dev }}</p>
                        </v-col>
                    </v-col>
                </v-row>
                <v-divider class="my-3"/>
                <v-row>
                    <v-col>
                        Categories:
                        <v-col>
                            <p v-for="(cat, i) in game.categories.edges" :key="i">{{ cat.node.description }}</p>
                        </v-col>
                    </v-col>
                    <v-divider vertical/>
                    <v-col>
                        Genres:
                        <v-col>
                            <p v-for="(genre, i) in game.genres.edges" :key="i">{{ genre.node.description }}</p>
                        </v-col>
                    </v-col>
                </v-row>
                <v-divider class="my-3"/>
                <v-row>
                    <v-col>
                        Pc requirements:
                        <v-col v-html="game.pcRequirement.minimum"></v-col>
                        <v-col v-html="game.pcRequirement.recommended"></v-col>
                    </v-col>
                    <v-divider vertical/>
                    <v-col>
                        Platforms:
                        <v-col>
                            <p v-for="(value, platform) in game.platform">
                                {{ capitalizeFirstLetter(platform) }}: {{ value ? "Supported" : "Not supported" }}
                            </p>
                        </v-col>
                    </v-col>
                </v-row>
            </v-col>
            <v-col id="reviews" cols="6">
                <v-container>
                    <v-row>
                        <v-col id="userReviews" cols="6">
                            <v-row justify="center">
                                <h3>Gebruiker reviews</h3>
                            </v-row>
                            <v-row v-if="isReady" v-for="review in userReviews" cols="6">
                                <v-col cols="12">
                                    <v-card class="my-4">
                                        <v-card-title>
                                            <v-col cols="9">
                                                {{ review.node.owner.username }} | Rating: {{ review.node.rating }}
                                            </v-col>
                                            <v-col cols="3">
                                                <v-btn @click="reportReview(review.node)">
                                                    <v-icon>
                                                        mdi-alert-octagon
                                                    </v-icon>
                                                </v-btn>
                                            </v-col>
                                        </v-card-title>
                                        <v-card-subtitle>
                                            {{ new Date(review.node.dateUpdated).toLocaleTimeString() }}
                                            {{ new Date(review.node.dateUpdated).toLocaleDateString() }}
                                        </v-card-subtitle>
                                        <v-divider/>
                                        <v-card-text>
                                            {{ review.node.text }}
                                        </v-card-text>
                                    </v-card>
                                </v-col>
                            </v-row>
                            <v-row v-if="!isReady || userReviews.length === 0">
                                <v-col cols="12">
                                    <v-card class="my-4">
                                        <v-card-title>
                                            <v-col>
                                                Het lijkt er op dat er nog geen reviews zijn voor deze game
                                            </v-col>
                                        </v-card-title>
                                        <v-divider/>
                                        <v-card-text>
                                            Word de eerste om er een toe te voegen!
                                        </v-card-text>
                                    </v-card>
                                </v-col>
                            </v-row>
                        </v-col>
                        <v-col id="steamReviews" cols="6">
                            <v-row justify="center">
                                <h3>Steam reviews</h3>
                            </v-row>
<!--                            TODO: steamreviews-->
                            <v-row v-if="!isReady || userReviews.length === 0">
                                <v-col cols="12">
                                    <v-card class="my-4">
                                        <v-card-title>
                                                Het lijkt er op dat er geen Steam reviews zijn voor deze game
                                        </v-card-title>
                                        <v-divider/>
                                    </v-card>
                                </v-col>
                            </v-row>
                        </v-col>
                    </v-row>
                </v-container>
            </v-col>
        </v-row>
    </v-container>
</template>

<script lang="ts">
import axios from "axios";

const {Component, VueComponent} = require('@/common/VueComponent');
import GraphqlHelper from "@/common/components/graphqlHelper";
import ReviewDialog from "@/pages/game/components/ReviewDialog.vue";
import {commonGameViewHelper} from "@/pages/game/components/commonGameViewHelper";
import base from "@/common/components/base";

@Component({
    components: {
        ReviewDialog
    }
})
export default class GameView extends VueComponent {

    private user: any;
    private steamAppId = commonGameViewHelper.getSteamAppId();
    private game: any;
    private queryString: string;
    private gameExists = false;
    private userReviews = [];
    private isReady = false;

    public beforeMount(): void {
        this.getGameDetails();
        this.getReviews();
    }

    private created(): void {
        this.user = (window as any).user;
    }

    // TODO: iets maken dat 1 gebruiker maar 1x een review kan reporten. op deze manier kan je maar 1x per page refresh (ik weet ook niet waarom) een (individueel) review reporten. will do for now.

    private reportReview(review: object): void {
        let rev = JSON.parse(JSON.stringify(review));
        rev.timesReported += 1;
        rev.owner = rev.owner.id
        const id = this.getId(rev.id);
        axios.patch(`${base.getBase()}api/reviews/${id}`, rev, {
            headers: {
                'Content-Type': "application/merge-patch+json"
            }
        });
    }

    // image moet nog
    //steamreviews moet nog
    /**
     * # steamReviews {
     *     #   edges {
     *     #     node {
     *     #       hours
     *     #       recommended
     *     #       text
     *     #       date
     *     #       username
     *     #     }
     *     #   }
     *     # }
     */
    private setupGamesQuery(): void {
        this.queryString =
            `query getGameDetails($id: ID!) {
                game(id: $id) {
                name
                about
                shortDescription
                detailedDescription
                developers
                headerImage
                nsfw
                publishers
                recommendationsTotal
                supportedLanguages
                website
                metacritic {
                    score
                }
                screenshots {
                    edges {
                        node {
                            full
                            thumbnail
                        }
                    }
                }
                releaseDate {
                    comingSoon
                    date
                }
                platform {
                    windows
                    mac
                    linux
                }
                pcRequirement {
                    minimum
                    recommended
                }
                genres {
                    edges {
                        node {
                          description
                        }
                    }
                }
                categories {
                    edges {
                        node {
                          description
                        }
                    }
                }
                }
            }`
        ;
    }

    private async getGameDetails(): Promise<void> {
        this.setupGamesQuery();
        const response = await GraphqlHelper.queryPoster(this.queryString, {"id": `/api/games/${this.steamAppId}`});
        this.gameExists = response !== null;

        if (this.gameExists) {
            this.game = (response as any).game;
        }
        this.$forceUpdate();
    }

    private async getReviews(): Promise<void> {
        const q = `
        query getGameDetails($id: ID!) {
            game(id: $id) {
                reviews {
                    edges {
                        node {
                            text
                            id
                            rating
                            dateUpdated
                            timesReported
                            owner {
                                id
                                username
                            }
                        }
                    }
                }
            }
        }`;

        const variables = {
            "id": `/api/games/${this.steamAppId}`
        };
        const response = await GraphqlHelper.queryPoster(q, variables);
        if (response != undefined) {
            this.userReviews = (response as any).game.reviews.edges;
        }
        this.isReady = true;
    }

    private capitalizeFirstLetter(string: string): string {
        return string.charAt(0).toUpperCase() + string.slice(1);
    }

    private getId(id: string): number {
        const parts = id.split("/");
        const res = parts[parts.length - 1];
        return +res;
    }

    private getColor(score: number): string {
        let color = 'red';

        if (score >= 70) color = "green";
        else if (score >= 40) color = "orange";
        return color;
    }

}
</script>
