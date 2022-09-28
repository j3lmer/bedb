<template>
    <v-app>
        <AppBar
            :is-making-new-account="isMakingNewAccount"
            @updated-making-account="updateMakingAccountStatus"
        />
        <RegisterForm v-if="isMakingNewAccount"/>
        <LoginForm v-if="!isMakingNewAccount"/>
    </v-app>
</template>

<script lang="ts">
import {VueComponent, Component} from "@/common/VueComponent";

import RegisterForm from "@/pages/entry/components/RegisterForm.vue";
import AppBar from "@/pages/entry/components/AppBar.vue";
import LoginForm from "@/pages/entry/components/LoginForm.vue";

@Component({
    components: {
        AppBar,
        RegisterForm,
        LoginForm
    }
})
export default class App extends VueComponent {
    private isMakingNewAccount = false;

    private updateMakingAccountStatus(value: boolean): void {
        this.isMakingNewAccount = value;
    }

};
</script>

<style scoped>
</style>