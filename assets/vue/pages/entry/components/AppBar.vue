<template>
	<div>
		<v-app-bar
			color="teal darken-1"
			dense
			dark
		>
			<v-toolbar-title>{{ isMakingNewAccount ? 'Register' : 'Login'}}</v-toolbar-title>
			<v-spacer/>
			<v-btn
				@click="redirect('/')"
				class="mx-1"
			>
				Home
			</v-btn>
			<v-btn
				v-if="isMakingNewAccount"
				@click="$emit('updated-making-account', false)"
				class="mx-1"
			>
				Login
			</v-btn>
			<v-btn
				v-if="!isMakingNewAccount"
				@click="$emit('updated-making-account', true)"
				class="mx-1"
			>
				Register
			</v-btn>
		</v-app-bar>
	</div>
</template>
<script lang="ts">
const {Component, VueComponent, Prop} = require('@/common/VueComponent')

@Component
export default class AppBar extends VueComponent {

	@Prop({required: true})
	private isMakingNewAccount: boolean;

	private redirect(url: string): void {
		window.location.replace(url);
	}
};
</script>

<style scoped>
</style>