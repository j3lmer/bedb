<template>
    <v-app>
        <Header
            :selected-component="selectedComponent"
            :items="items"
            :is-logged-in="this.user !== undefined"
            @updated-tab="selectComponent"
        />
        <v-card flat>
            <Home
                v-if="selectedComponent === items.Home"
                @updated-tab="selectComponent"
            />
            <Games
                v-if="selectedComponent === items.Games"
                :genre-games="genreGames"
            />
        </v-card>
    </v-app>
</template>

<script lang="ts">

const {Component, VueComponent} = require('@/common/VueComponent');
import {HomepageTabs} from "@/common/components/Enums/HomepageTabs";
import Header from "@/pages/common/Header.vue";
import Home from "@/pages/home/components/Home.vue";
import Games from "@/pages/home/components/Games.vue";
import GraphqlHelper from "@/common/components/graphqlHelper";
// const featuredWinIds = require("../../../../steam/featuredGames.json");

@Component({
    components: {
        Header,
        Home,
        Games
    }
})
export default class App extends VueComponent {
    private user = null;
    private selectedComponent = 'Home';
    private items = HomepageTabs;
    private genreGames = {};
    private amountOfGenres = 5;
    private chunkLength = 6;

    private beforeMount(): void {
        this.loadGames();
    }

    public created(): void {
        let cookiePage = this.$cookies.get('page');
        this.user = (window as any).user;

        if (cookiePage) {
            this.selectComponent(cookiePage);
        }
    }

    private selectComponent(component: any): void {
        this.selectedComponent = component;
        setTimeout(() => {
            this.$forceUpdate()
        }, 100)
    }

    //TODO / nth: featured games ook laden
    //TODO: stop using first x and get random games (random cursor?)
    //TODO / NTH: remove nsfw games and maybe query new ones (recursively until there are no nsfw games anymore?)
    //TODO / NTH: resultaten cachen en inladen
    private async loadGames(): Promise<void> {

        const [q, variables] = this.setupForQueryViaGenre();
        let response = await GraphqlHelper.queryPoster(q, variables);

        if (response == null) {
            return;
        }

        let disallowedIds = [
            "/api/games/1000550",
            "/api/games/1000280",
            "/api/games/1000120",
            "/api/games/1000240",
            "/api/games/1000180",
            "/api/games/1000140",
            "/api/games/1000150",
            "/api/games/1000160",
            "/api/games/1000170",
            "/api/games/1000180",
            "/api/games/1000190",
            "/api/games/1000200",
            "/api/games/1000210",
            "/api/games/1000220",
            "/api/games/1000230",
            "/api/games/1000240",
            "/api/games/1000250",
        ];

        for (let i = 0; i < Object.entries(response).length; i++) {
            let thisGenre = response[`genre${i}`];
            thisGenre.chunkedGames = [[]];
            let chunkCounter = 0;
            for (let j = 0; j < thisGenre.games.edges.length; j++) {
                const thisGame = thisGenre.games.edges[j];
                if (thisGenre.chunkedGames[chunkCounter].length >= this.chunkLength) {
                    thisGenre.chunkedGames.push([]);
                    chunkCounter++;
                }
                if (disallowedIds.indexOf(thisGame.node.id) > -1) {
                    continue;
                }
                thisGenre.chunkedGames[chunkCounter].push(thisGame);
            }
            delete thisGenre.games;
            response[`genre${i}`] = thisGenre;
        }

        this.genreGames = response;
    }

    /*  TODO / NTH: Het zo maken dat er eerst 18 games worden opgehaald vanaf een willekeurige cursor plek,
            vanaf daar dynamisch meer ophaalt met de cursor, wanneer t einde is bereikt,
            de eerste 18 queryen, totdat je de plek van de eerste cursor hebt bereikt.
        (vermoedelijk te veel werk voor nu)
     */
    private setupForQueryViaGenre(): [string, object] {
        let outerString = `query GetGenreWithGamesAndDescription(`;
        let innerString = ``;
        let variables = {};

        for (let i = 0; i < this.amountOfGenres; i++) {
            variables = this.addRandomGenres(variables, i);
            outerString += i === this.amountOfGenres - 1 ? `$id${i}: ID!` : `$id${i}: ID!, `;
            let genreString = `
            genre${i} : genre(id: $id${i}) {
                description
                games(first:18) {
                    edges {
                        node {
                            id
                            name
                            headerImage
                            nsfw
                        }
                    }
                }
            }`;
            genreString += i !== this.amountOfGenres ? ',' : '';
            innerString += genreString;
        }
        outerString += ") {";
        innerString += '}';
        outerString += innerString;
        return [outerString, variables];
    }

    private addRandomGenres(variables: object, iteration: number): object {
        let randNumber = this.randNumber(1, 13);
        while (this.objectContains(variables, `/api/genres/${randNumber}`)) {
            randNumber = this.randNumber(1, 13);
        }
        variables[`id${iteration}`] = `/api/genres/${randNumber}`;
        return variables;
    }

    private randNumber(min: number, max: number): number {
        return Math.floor(Math.random() * (max - min + 1) + min);
    }

    private objectContains(obj, term: string): boolean {
        for (let key in obj) {
            if (obj[key].indexOf(term) != -1) return true;
        }
        return false;
    }
}
</script>
