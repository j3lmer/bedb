<template>
    <v-app>
        <Header :is-logged-in="this.user !== undefined"/>
        <GameView/>
    </v-app>
</template>

<script lang="ts">
const {Component, VueComponent} = require('@/common/VueComponent')
import Header from "@/pages/common/Header.vue"
import GameView from "@/pages/game/components/GameView.vue";


@Component({
    components: {
        Header,
        GameView
    }
})
export default class App extends VueComponent {

    private user: any;
    private steamAppid: number;

    public created(): void {
        this.user = (window as any).user;
        this.steamAppId = (window as any).steamAppid;
    }
}
</script>
