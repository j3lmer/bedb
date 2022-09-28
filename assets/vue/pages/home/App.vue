<template>
    <v-app>
    </v-app>
</template>

<script lang="ts">
import {VueComponent, Component} from "@/common/VueComponent";


@Component
export default class App extends VueComponent
{

    public created(): void
    {
        console.log('test');
    }
};
</script>

<style scoped>

</style>
