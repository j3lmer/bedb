<template>
    <v-app>
    </v-app>
</template>

<script lang="ts">
//import {VueComponent} from '@/common/VueComponent';
const {Component, VueComponent} = require('@/common/VueComponent')

@Component
export default class App extends VueComponent
{

    public created()
    {
        console.log('testo');
    }
};
</script>

<style scoped>

</style>
