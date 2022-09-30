<template>
    <v-app>
    </v-app>
</template>

<script lang="ts">
const {Component, VueComponent} = require('@/common/VueComponent');

@Component
export default class App extends VueComponent {
    private user = null;

    public created(): void {
        if((window as any).user !== null) {
            this.user = (window as any).user;
        }
    }
};
</script>

<style scoped>

</style>
