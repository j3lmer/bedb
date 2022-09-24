<template>
	<v-container>
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

			<v-btn
				:disabled="!valid"
				color="success"
				class="mr-4"
				@click="submit"
			>
				Submit
			</v-btn>
		</v-form>
	</v-container>
</template>

<script lang="ts">
import axios from "axios";

const {Component, VueComponent} = require('@/common/VueComponent')

@Component
export default class LoginForm extends VueComponent {
	private valid = false;
	private email  = '';
	private password = '';

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
		// if ((this as any).$refs.form.validate() && this.valid) {
		// 	const user = {
		// 		email: this.email,
		// 		password: this.password,
		// 	}
        //
		// 	axios
		// 		.post('/entry/login', user)
		// 		.then(response => {
		// 			if(response.status < 299 && response.data.user === 'loggedIn'){
		// 				location.replace('/landed');
		// 			}
		// 		})
		// 		// .catch(error => {
		// 		// 	if (error.response.data.error) {
		// 		// 		this.snackbarText = error.response.data.error;
		// 		// 	} else {
		// 		// 		this.snackbarText = this.$t('login.errorByLogin');
		// 		// 	}
		// 		// 	this.snackbar = true;
		// 		// })
		// 	// console.log(response)
		// }
	}
};
</script>

<style scoped>
</style>