<template>
    <v-app>
    </v-app>
</template>

<script lang="ts">
const {Component, VueComponent} = require('@/common/VueComponent')

@Component
export default class App extends VueComponent
{

    public created(): void
    {
        console.log('snot');
    }
};
</script>

<style scoped>

</style>
