<template>
    <div>
        <v-row align="center" justify="center">
            <v-spacer/>
            <v-col>
                <h3>Featured</h3>
            </v-col>
            <v-spacer/>
            <v-spacer/>
        </v-row>
        <v-row class="mx-4">
            <v-col
                v-for="item in featuredWinIds"
            >
                <v-card
                    max-width="200"
                    height="250"
                    outlined
                >
                    {{item}}
                </v-card>
            </v-col>
        </v-row>
    </div>

</template>

<script lang="ts">
const {Component, VueComponent} = require('@/common/VueComponent');
const featuredWinIds = require("../../../../steam/featuredGames.json")

@Component
export default class Games extends VueComponent {

    private featuredWinIds = featuredWinIds;

    public created(): void {

    }


    private getGameAppDetails: void {

}


}
</script>
