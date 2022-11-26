<template>
    <v-container>
        <v-dialog width="50vw" v-model="dialog">
            <template v-slot:activator="{on, attrs}">
                <v-btn
                    v-bind="attrs"
                    v-on="on"
                >
                    Laat een review achter
                </v-btn>
            </template>

            <v-card>
                <v-card-title>
                    <v-row>
                        <v-col class="text-center">
                            <h2>Wat vind je?</h2>
                        </v-col>
                    </v-row>
                </v-card-title>
                <v-divider/>
                <v-card-text class="pa-5">
                    <v-row justify="center">
                        <v-rating
                            v-model="rating"
                            length="10"
                        />
                    </v-row>
                    <v-row>
                        <v-textarea outlined v-model="reviewText" label="Wat je denkt"/>
                    </v-row>
                    <v-row class="d-flex flex-row-reverse">
                        <v-spacer/>
                        <v-col cols="3" class="text-end">
                            <v-btn @click="sendReview">Verzend review!</v-btn>

                        </v-col>
                        <v-col class="text-end">
                            <v-file-input
                                :rules="rules"
                                v-model="files"
                                accept="image/png, image/jpeg, image/bmp"
                                placeholder="Upload a screenshot"
                                prepend-icon="mdi-camera"
                                label="Images"
                            />
                        </v-col>
                        <v-spacer/>
                    </v-row>
                </v-card-text>
            </v-card>
        </v-dialog>
        <v-snackbar
            v-model="snackbar"
        >
            <v-row class="ma-2" v-for="text in snackbarText">
                {{ text }}
            </v-row>

            <template v-slot:action="{ attrs }">
                <v-btn
                    color="pink"
                    text
                    v-bind="attrs"
                    @click="closeSnackBar"
                >
                    Close
                </v-btn>
            </template>
        </v-snackbar>
    </v-container>
</template>

<script lang="ts">
const {Component, VueComponent, Prop} = require('@/common/VueComponent');
import {commonGameViewHelper} from "@/pages/game/components/commonGameViewHelper";
import axios from "axios";
import base from "@/common/components/base";

@Component()
export default class ReviewDialog extends VueComponent {
    private dialog = false;
    private reviewText = '';
    private rating = 0;
    private files = [];
    private gameIri = `/api/games/${commonGameViewHelper.getSteamAppId()}`;
    private snackbar = false;
    private snackbarText = [];
    private rules = [value => !value || value.size < 2000000 || 'Image size should be less than 2 MB!'];


    created()
    {
        this.test();
    }

    private test()
    {
        axios.post(`${base.getBase()}media_objects`, {"test": "test"})
    }

    private async sendReview(): Promise<void> {
        console.log(this.files);
        if (!(this.rating > 0 && this.reviewText && this.reviewText.length > 0)) {
            let reasons = [];
            if (this.rating <= 0) {
                reasons.push("Vul a.u.b. een rating in.");
            }
            if (!this.reviewText || this.reviewText.length <= 0) {
                reasons.push("Vul a.u.b. een review in.");
            }
            this.showSnackBar(reasons);
        }

        const postData = {
            text: this.reviewText,
            game: this.gameIri,
            rating: this.rating,
            dateUpdated: new Date(),
            owner: `/api/users/${(window as any).user.id}`
        };

        const response = await axios.post(`${base.getBase()}api/reviews`, postData);
        this.reviewText = "";
        this.rating = 0;

        if (response.status > 299) {
            this.dialog = false;
            this.snackbarText.push("Review kon niet verstuurd worden, probeer het later opnieuw.")
            this.snackbar = true;
            return;
        }

        this.snackbarText.push("Je review is successvol verstuurd!");
        this.snackbar = true;
        this.$emit("review-made");
        this.dialog = false;
    }

    private showSnackBar(snackbarText: string[]) {
        this.snackbarText = snackbarText;
        this.snackbar = true;
    }

    private closeSnackBar(): void {
        this.snackbarText = [];
        this.snackbar = false;
    }
}
</script>
