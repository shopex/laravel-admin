<template>
  <ul>
	<li v-for="item in menus">
		<a v-bind:href="item.link">{{item.label}}</a>
	</li>
  </ul>
</template>

<script>
export default {
	props: ["menus"]
}
</script>