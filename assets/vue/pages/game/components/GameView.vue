<template>
    <v-container v-if="gameExists">
        <v-row class="pa-5" justify="space-between">
            <v-col class="text-center">
                <h1>{{ game.name }}</h1>
            </v-col>
            <v-col class="text-right">
                <v-spacer/>
                <v-col align-self="end">
                    <v-row>
                        <v-col cols="6">
                            <v-row>
                                <v-col class="text-right">
                                    Categories
                                </v-col>
                            </v-row>

                            <v-row>
                                <v-col class="text-right">
                                    Genres
                                </v-col>
                            </v-row>
                        </v-col>
                        <v-col cols="6" align="center">
                            <v-btn>
                                Leave a review
                            </v-btn>
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
                            <v-carousel-item v-for="(screenshot, i) in game.screenshots.edges">
                                <v-img :src="screenshot.node.thumbnail">></v-img>
                            </v-carousel-item>
                        </v-carousel>
                        djsajsdl
                    </v-col>
                    <v-col id="userScore">

                    </v-col>
                    <v-col id="metaScore">

                    </v-col>
                    <v-col id="steamRecommendations">

                    </v-col>
                </v-row>
            </v-col>
            <v-col id="reviews">

            </v-col>
        </v-row>
    </v-container>
</template>

<script lang="ts">
const {Component, VueComponent} = require('@/common/VueComponent');
import GraphqlHelper from "@/common/components/graphqlHelper";

@Component()
export default class GameView extends VueComponent {

    private user: any;
    private steamAppId: number;
    private game: any;
    private queryString: string;
    private gameExists = false;

    public created(): void {
        this.user = (window as any).user;
        this.getGameDetails();
    }

    private getSteamAppId(): void {
        const windowLocation = window.location.pathname;
        const temp = windowLocation.substring(windowLocation.lastIndexOf('/') + 1);
        this.steamAppId = +temp;
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
            }
        `
    }

    private getVariables(): object {
        return {
            "id": `/api/games/${this.steamAppId}`
        };
    }

    private async getGameDetails(): Promise<void> {
        this.getSteamAppId();
        this.setupQuery();
        const variables = this.getVariables();
        const response = await GraphqlHelper.queryPoster(this.queryString, variables);
        this.gameExists = response !== null;

        if (this.gameExists) {
            this.game = (response as any).game;
        }
    }


}
</script>
