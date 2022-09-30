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
                        v-model="username"
                        label="Username"
                        :rules="nameRules"
                        required
                    />
                    <v-text-field
                        v-model="email"
                        label="E-mail"
                        :rules="emailRules"
                        required
                    />

                    <v-text-field
                        v-model="password"
                        label="Password"
                        type="password"
                        :rules="[required, min6]"
                        required
                    />

                    <v-text-field
                        v-model="passwordCheck"
                        label="Re-enter password"
                        type="password"
                        :rules="[required, min6]"
                        required
                    />

                    <v-btn
                        :disabled="!valid"
                        color="success"
                        class="mr-4"
                        @click="submit"
                    >
                        Submit
                    </v-btn>
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
                    color="red"
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
export default class RegisterForm extends VueComponent {
    private valid = true;
    private snackbar = false;
    private username = '';
    private password = '';
    private email = '';
    private passwordCheck = '';
    private snackbarText = '';

    private nameRules = [
        v => !!v || 'Username is required',
        v => (v && v.length <= 254) || 'Username is too long',
    ];
    private emailRules = [
        v => !!v || 'E-mail is required',
        v => /.+@.+\..+/.test(v) || 'E-mail is not valid',
    ];

    private required(value): boolean | string {
        return value ? true : 'Password is required';
    }

    private min6(value): boolean | string {
        return value.length >= 6 ? true : 'Password must be at least 6 characters long';
    }

    private matchingPasswords(): boolean | string {
        return this.password === this.passwordCheck ? true : 'Passwords do not match';
    }

    private async submit(): Promise<void> {
        if ((this as any).$refs.form.validate() && this.valid) {
            const user = {
                username: this.username,
                email: this.email,
                plainPassword: this.password
            }

            axios
                .post('/register', user)
                .then(response => {
                    console.log(response.data);
                    console.log(response.headers);
                    if (response.status < 299) {
                        location.replace('/');
                    }
                })
                .catch(error => {
                    if (error.response.data.error) {
                        this.snackbarText = 'Successfully sent E-mail, Check your inbox to validate your account ';
                    } else {
                        this.snackbarText = 'Unfortunately we weren\'t able to send an E-mail, please try again';
                    }
                    this.snackbar = true;
                })
        }
    }
};
</script>

<style scoped>
</style>