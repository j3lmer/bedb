<template>
    <v-container>
        <v-row>
            <v-col>

            </v-col>
            <v-col>

            </v-col>
        </v-row>
    </v-container>
</template>

<script lang="ts">
const {Component, VueComponent, Prop} = require('@/common/VueComponent')

@Component
export default class Settings extends VueComponent {

    @Prop({required: true})
    private user: any;

    private isAdminUser = false;

    created(): void {
        console.log(this.user);
        this.isAdminUser = !!this.user.roles.includes("ROLE_ADMIN");
        console.log(this.isAdminUser);
    }
}
</script>
