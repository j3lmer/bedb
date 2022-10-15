<template>
    <v-app>
        <Header
            :selected-component="selectedComponent"
            :items="items"
            @updated-tab="selectComponent"
        />
        <v-card flat>
            <Home v-if="selectedComponent === items.Home"
                  @updated-tab="selectComponent"
            />
            <Games v-if="selectedComponent === items.Games"
                   :genre-games="genreGames"
            />
        </v-card>
    </v-app>
</template>

<script lang="ts">
import axios from "axios";

const {Component, VueComponent} = require('@/common/VueComponent');
import {HomepageTabs} from "@/common/components/Enums/HomepageTabs";
import Header from "@/pages/home/components/Header.vue";
import Home from "@/pages/home/components/Home.vue";
import Games from "@/pages/home/components/Games.vue";

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
        this.$forceUpdate();
    }

    //TODO: stop using first 6 and get random games (random cursor?)
    private async loadGames(): Promise<void> {
        let outerString = `query GetGenreWithGamesAndDescription(`;
        let innerString = ``;
        let variables = {};

        for (let i = 0; i < this.amountOfGenres; i++) {
            variables = this.addRandomGenres(variables, i);
            outerString += i === this.amountOfGenres - 1 ? `$id${i}: ID!` : `$id${i}: ID!, `;
            let genreString = `genre${i} : genre(id: $id${i}) {
                description
                games(first:6) {
                    edges {
                        node {
                            id
                            name
                            headerImage
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
        this.genreGames = await this.queryPoster(outerString, variables);
    }

    private addRandomGenres(variables: object, iteration: number)
    {
        let randNumber = this.randNumber(1, 13);
        while (this.objectContains(variables, `/api/genres/${randNumber}`)) {
            randNumber = this.randNumber(1, 13);
        }
        variables[`id${iteration}`] = `/api/genres/${randNumber}`;
        return variables;
    }

    private async queryPoster(query: string, variables: object): Promise<object> {
        const response = await axios.post("http://127.0.0.1:8000/api/graphql", {
                query: query,
                variables: variables,
            },
            {
                headers: {
                    'Content-Type': 'application/json'
                }
            }
        );

        return response.data.data;
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
