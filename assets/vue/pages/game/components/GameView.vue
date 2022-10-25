<template>
    <v-row class="px-5" justify="space-between">
        <v-col>
            Game naam
        </v-col>
        <v-col class="text-right">
            <v-spacer/>
            <v-col align-self="end">
                <v-col>
                    <v-row>
                        <v-col>
                            Categories
                        </v-col>
                    </v-row>
                    <v-row>
                        <v-col>
                            Genres
                        </v-col>
                    </v-row>
                </v-col>
                <v-col>
                    <v-btn>
                        Maak een nieuw review
                    </v-btn>
                </v-col>
            </v-col>
        </v-col>
    </v-row>
</template>

<script lang="ts">
const {Component, VueComponent} = require('@/common/VueComponent')

@Component()
export default class GameView extends VueComponent {

    private user: any;
    private steamAppid: number;

    public created(): void {
        this.user = (window as any).user;
        this.steamAppid = (window as any).steamAppid;
    }

    private getGameDetails(): void
    {

    }
}
</script>
