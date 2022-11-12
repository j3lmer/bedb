<template>
    <v-container>
        <v-row>
            <v-col>
                <v-card>
                    <!--                Als er reviews zijn, anders misschien weergeven dat je nog geen posts hebt-->

                    <v-card-title>
                        <v-row class="text-center">
                            <v-col>
                                <h3>Jouw reviews {{ isAdminUser ? "en gerapporteerde reviews" : "" }}</h3>
                            </v-col>
                        </v-row>
                    </v-card-title>
                    <v-divider/>
                    <v-card-text>
                        <v-row justify="center">
                            <v-card
                                class="my-4"
                                v-if="!hasReviews"
                            >
                                <v-card-title>Het lijkt er op dat je nog geen reviews hebt.</v-card-title>
                                <v-divider/>
                                <v-card-subtitle>Probeer er een aan te maken!</v-card-subtitle>
                            </v-card>
                            <v-card
                                v-if="hasReviews"
                                v-for="review in userReviews"
                                elevation="0"
                                width="50vw"
                                class="px-5 pb-5 pt-2 ma-3"
                                outlined
                            >
                                <!--                               v-for in de nested v-card-->
                                <v-row class="top-layer" justify="space-around">
                                    <v-col>
                                        <h4>
                                            {{ review.game.name }}
                                        </h4>
                                        <!--                                       hier een br en gebruikersnaam gerapporteerde post mocht dit een admin zijn-->
                                    </v-col>
                                    <v-col class="text-end mt-3 pr-3">
                                        <v-btn color="red" class="white--text pa-0 ma-0" @click="deleteReview(review.id)">X</v-btn>
                                    </v-col>
                                </v-row>
                                <v-row class="mid-layer">
                                    <v-col>
                                        {{ review.text }}
                                    </v-col>
                                    <v-col class="text-end">
                                        <h5>
                                            {{ new Date(review.dateUpdated).toLocaleTimeString() }}
                                            {{ new Date(review.dateUpdated).toLocaleDateString() }}
                                        </h5>
                                    </v-col>
                                </v-row>
                                <v-row class="bottom-layer">
                                    <!--                                   FIXME: hier een max height en width aan geven zonder te croppen-->
                                    <v-img
                                        v-if="review.image"
                                        class="ma-5"
                                        :src="review.image"
                                        max-height="25vh"
                                        max-width="25-vw"
                                        contain
                                    />
                                </v-row>
                            </v-card>
                        </v-row>
                    </v-card-text>
                </v-card>
            </v-col>
            <v-col>
                <v-card>
                    <v-card-title>
                        <v-row class="text-center">
                            <v-col>
                                <h3>Account instellingen</h3>
                            </v-col>
                        </v-row>
                    </v-card-title>
                    <v-divider/>
                    <v-card-text class="pa-5 pl-10 pb-10">
                        <v-row class="mb-2 text-center">
                            <v-col><h4>Gebruikersnaam:</h4></v-col>
                            <v-col>{{ user.username }} <a>
                                <v-icon> mdi-pencil</v-icon>
                            </a></v-col>
                        </v-row>
                        <v-row class="my-2 text-center">
                            <v-col><h4>E-mail:</h4></v-col>
                            <v-col>{{ user.email }}</v-col>
                        </v-row>
                        <v-row class="mt-2">
                            <v-spacer/>
                            <v-btn>Verander wachtwoord</v-btn>
                            <v-spacer/>
                        </v-row>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>

<script lang="ts">
const {Component, VueComponent, Prop} = require('@/common/VueComponent')
import GraphqlHelper from "@/common/components/graphqlHelper";
import base from "@/common/components/base"
import axios from "axios";

@Component
export default class Settings extends VueComponent {

    @Prop({required: true})
    private user: any;
    private isAdminUser = false;
    private hasReviews = false;
    private userReviews = {};

    // TODO: als admin; query voor alle gerapporteerde reviews
    private async beforeMount(): Promise<void> {
        this.isAdminUser = !!this.user.roles.includes("ROLE_ADMIN");
        const [q, variables] = this.setupQuery(this.user.reviews);
        this.userReviews = await GraphqlHelper.queryPoster(q, variables);
        this.hasReviews = this.userReviews != null;

    }

    private async getReviews(): Promise<string[]>
    {
        const response = await axios.get(`${base.getBase()}api/users/${this.user.id}`);
        return (response as any).data.reviews;
    }


    private setupQuery(reviews: string[]): [string, object] {
        let outerString = `query GetUserReviews(`;
        let innerString = ``;
        let variables = {};

        for (let i = 0; i < reviews.length; i++) {
            variables[`id${i}`] = reviews[i];

            outerString += i === reviews.length - 1 ? `$id${i}: ID!` : `$id${i}: ID!, `;
            let userReviewString = `
                review${i} : review(id: $id${i}) {
                    id
                    text
                    rating
                    game {
                        id,
                        name
                    }
                    dateUpdated
                }`
            ;
            userReviewString += i !== reviews.length ? ',' : '';
            innerString += userReviewString;
        }
        outerString += ") {";
        innerString += '}';
        outerString += innerString;

        return [outerString, variables];
    }

    private async deleteReview(id: string) {

        await axios.delete(`${base.getBase()}${id.substring(1)}`)
        const response = await this.getReviews();
        const [q, variables] = this.setupQuery(response);
        this.userReviews = await GraphqlHelper.queryPoster(q, variables);
        this.$forceUpdate();
    }
}
</script>
