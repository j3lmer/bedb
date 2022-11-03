<template>
</template>

<script lang="ts">
const {Component, VueComponent, Prop} = require('@/common/VueComponent')


@Component
export default class Settings extends VueComponent {

    @Prop({required: true})
    private user: any;


    created() {
        console.log(this.user);
    }
}
</script>
