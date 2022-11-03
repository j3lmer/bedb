<template>
    <v-container>
        <v-row>
            <v-col>
<!--                Als er reviews zijn, anders misschien weergeven dat je nog geen posts hebt-->
                <h3>Jouw reviews {{isAdminUser ? "en gerapporteerde reviews" : ""}}</h3>
            </v-col>
            <v-col>
                <v-card>
                    <v-card-title>
                        <v-row class="text-center">
                            <v-col>
                                <h3>Account instellingen</h3>
                            </v-col>
                        </v-row>
                    </v-card-title>
                    <v-card-text class="pa-5 pl-10 pb-10">
                        <v-row class="mb-2 text-center">
                            <v-col><h4>Gebruikersnaam:</h4></v-col><v-col>{{user.username}} <a><v-icon> mdi-pencil </v-icon></a></v-col>
                        </v-row>
                        <v-row class="my-2 text-center">
                            <v-col><h4>E-mail:</h4></v-col><v-col>{{user.email}}</v-col>
                        </v-row>
                        <v-row class="mt-2">
                            <v-spacer/>
                            <v-btn>Verander wachtwoord</v-btn>
                            <v-spacer/>
                        </v-row>

                    </v-card-text>
                </v-card>
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
        //query doen voor alle reviews van
    }
}
</script>
