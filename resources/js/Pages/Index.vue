<template>
    {{ count }}
    <button @click="count++">increment</button>
    {{ title }}
</template>

<script setup>
import {ref} from "vue"
const count = ref(0)
defineProps(['title'])
</script>

<style scoped>
</style>