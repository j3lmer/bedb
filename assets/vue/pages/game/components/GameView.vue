<template>
    <v-container v-if="gameExists">
        <v-row class="pa-5" justify="space-between">
            <v-col class="text-center">
                <h1>{{ game.name }}</h1>
            </v-col>
            <v-col>
                <v-spacer/>
                <v-col align-self="end">
                    <v-row>
                        <v-spacer/>
                        <v-col cols="6" align="right">
                            <ReviewDialog/>
                        </v-col>
                    </v-row>
                </v-col>
            </v-col>
        </v-row>
        <v-row>
            <v-col id="game">
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
                        <!--                       TODO: kleur van de sheet bepalen op hoe hoog de score is?-->
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
<!--                TODO: wellicht website op zn eigen row, publishers en developers naast elkaar.-->
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
                                {{ capitalizeFirstLetter(platform) }}: {{ value ? "Supported" : "Not supported"}}
                            </p>
                        </v-col>
                    </v-col>
                </v-row>
            </v-col>
            <v-col id="reviews">
                <v-col id="userReviews">

                </v-col>
                <v-col id="steamReviews">

                </v-col>
            </v-col>
        </v-row>
    </v-container>
</template>

<script lang="ts">
import Header from "@/pages/common/Header.vue";

const {Component, VueComponent} = require('@/common/VueComponent');
import GraphqlHelper from "@/common/components/graphqlHelper";
import ReviewDialog from "@/pages/game/components/ReviewDialog.vue";
import {commonGameViewHelper} from "@/pages/game/components/commonGameViewHelper";

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

    public created(): void {
        this.user = (window as any).user;
        this.getGameDetails();
    }


    // image moet nog
    //steamreviews tijdelijk kapot
    /**
     * # steamReviews { tijdelijk kapot
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
    private setupQuery(): void {
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
                reviews {
                    edges {
                        node {
                            text
                            rating
                            dateUpdated
                                owner {
                                username
                            }
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

    private getVariables(): object {
        return {
            "id": `/api/games/${this.steamAppId}`
        };
    }

    private async getGameDetails(): Promise<void> {
        this.setupQuery();
        const variables = this.getVariables();
        const response = await GraphqlHelper.queryPoster(this.queryString, variables);
        this.gameExists = response !== null;

        if (this.gameExists) {
            this.game = (response as any).game;
            console.log(this.game)
        }
    }

    private capitalizeFirstLetter(string: string): string {
        return string.charAt(0).toUpperCase() + string.slice(1);
    }

    private getColor(score: number): string {
        let color = 'red';

        if(score >= 70) color = "green";
        else if(score >=40) color = "orange";
        return color;
    }

}
</script>
