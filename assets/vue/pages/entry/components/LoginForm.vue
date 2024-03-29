<template>
    <v-container>
        <v-row>
            <v-spacer/>
            <v-col cols="3">
                <v-form
                    class="mb-4"
                    ref="form"
                    v-model="valid"
                    lazy-validation
                >
                    <v-text-field
                        v-model="email"
                        :rules="emailRules"
                        label="E-mail"
                        required
                    />

                    <v-text-field
                        v-model="password"
                        :rules="[required, min6]"
                        label="Password"
                        type="password"
                        required
                    />
                    <v-row>
                        <v-col>
                            <v-btn
                                :disabled="!valid"
                                color="success"
                                class="mr-4"
                                @click="submit"
                            >
                                Submit
                            </v-btn>
                        </v-col>
                        <v-col>
                            <div :style="{visibility: submitting ? 'visible' : 'hidden'}">
                                <v-progress-circular
                                    indeterminate
                                    color="green"
                                ></v-progress-circular>
                            </div>
                        </v-col>
                    </v-row>

                </v-form>
            </v-col>
            <v-spacer/>
        </v-row>
        <v-snackbar
            v-model="snackbar"
        >
            {{ snackbarText }}

            <template v-slot:action="{ attrs }">
                <v-btn
                    color="pink"
                    text
                    v-bind="attrs"
                    @click="snackbar = false"
                >
                    Close
                </v-btn>
            </template>
        </v-snackbar>
    </v-container>
</template>

<script lang="ts">
const {Component, VueComponent} = require('@/common/VueComponent');
import axios from "axios";

@Component
export default class LoginForm extends VueComponent {
    private valid = false;
    private email = '';
    private password = '';
    private snackbar = false;
    private snackbarText = '';
    private submitting = false;

    private emailRules = [
        v => !!v || 'E-mail required',
        v => /.+@.+\..+/.test(v) || 'E-mail not valid',
    ];

    private required(value): boolean | string {
        return value ? true : 'Password required';
    }

    private min6(value): boolean | string {
        return value.length >= 6 ? true : 'Password too short';
    }

    private async submit(): Promise<void> {
        this.submitting = true;
        await this.$nextTick();
        if ((this as any).$refs.form.validate() && this.valid) {
            const user = {
                email: this.email,
                password: this.password,
            };

            axios
                .post('/login', user)
                .then(response => {
                    if (response.status < 299) {
                         location.replace('/');
                    }
                })
                .catch(error => {
                    if (error.response.data.error) {
                        this.snackbarText = error.response.data.error;
                    } else {
                        this.snackbarText = 'There has been an unforeseen error, try again or contact support';
                    }
                    this.snackbar = true;
                })
        }
        this.submitting = false;
        await this.$nextTick();
    }
};
</script>

<style scoped>
</style>