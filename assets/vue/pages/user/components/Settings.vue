<template>
    <v-container>
        <v-row>
            <v-col>
                <v-card> <!--                Als er reviews zijn, anders misschien weergeven dat je nog geen posts hebt-->

                    <v-card-title>
                        <v-row class="text-center">
                            <v-col>
                                <h3>Jouw reviews {{isAdminUser ? "en gerapporteerde reviews" : ""}}</h3>
                            </v-col>
                        </v-row>
                    </v-card-title>
                    <v-divider/>
                    <v-card-text>
                        <v-row>
                           <v-card elevation="0" width="50vw" class="px-5 ma-3" outlined>
<!--                               v-for in de nested v-card-->
                               <v-row class="top-layer" justify="space-around">
                                   <v-col>
                                       <h4>
                                           gamenaam
                                       </h4>
<!--                                       hier een br en gebruikersnaam gerapporteerde post mocht dit een admin zijn-->
                                   </v-col>
                                   <v-col class="text-end mt-3 pr-3">
                                       <v-btn color="red" class="white--text pa-0 ma-0" @click="deleteReview">X</v-btn>
                                   </v-col>
                               </v-row>
                               <v-row class="mid-layer">
                                   <v-col>
                                       Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                                   </v-col>
                                   <v-col class="text-end">
                                       <h5>
                                           datum
                                       </h5>
                                   </v-col>
                               </v-row>
<!--                               if v-img-->
                               <v-row class="bottom-layer">
<!--                                   FIXME: hier een max height en width aan geven zonder te croppen-->
                                   <v-img class="ma-5" src="https://gifimage.net/wp-content/uploads/2018/06/work-in-progress-gif-7.gif"
                                          max-height="25vh"
                                          max-width="25-vw"
                                          contain
                                   />
                               </v-row>
                           </v-card>
                        </v-row>
                    </v-card-text>
                </v-card>
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
                    <v-divider/>
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

    private created(): void {
        console.log(this.user);
        this.isAdminUser = !!this.user.roles.includes("ROLE_ADMIN");
        //query doen voor alle reviews van
    }

    private deleteReview()
    {

    }
}
</script>
