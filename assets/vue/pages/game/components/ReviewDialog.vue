<template>
    <v-container>
        <v-dialog width="50vw">
            <template v-slot:activator="{on, attrs}">
                <v-btn
                    v-bind="attrs"
                    v-on="on"
                >
                    Laat een review achter
                </v-btn>
            </template>

            <v-card>
                <v-card-title>
                    <v-row>
                        <v-col class="text-center">
                            <h2>Wat vind je?</h2>
                        </v-col>
                    </v-row>
                </v-card-title>
                <v-divider/>
                <v-card-text class="pa-5">
                    <v-row>
                        <v-textarea outlined label="Wat je denkt"/>
                    </v-row>
                    <v-row class="d-flex flex-row-reverse">
                        <v-col cols="3" class="text-end">
<!--                            TODO: maak dit werkend lol-->
                            <v-btn>Verzend review!</v-btn>

                        </v-col>
                        <v-col class="text-end">
                            <v-btn>Selecteer een bijlage</v-btn>
                        </v-col>
                        <v-spacer/>
                    </v-row>
                </v-card-text>
            </v-card>
        </v-dialog>
    </v-container>
</template>

<script lang="ts">
const {Component, VueComponent} = require('@/common/VueComponent');

@Component()
export default class ReviewDialog extends VueComponent {
    private dialog = false;
}
</script>
