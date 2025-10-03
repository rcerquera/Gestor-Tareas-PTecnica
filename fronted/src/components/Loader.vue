<template>
  <div class="d-flex justify-content-center align-items-center my-5" v-if="loading">
    <div class="spinner-border text-primary" role="status">
      <span class="visually-hidden">Cargando...</span>
    </div>
  </div>
</template>

<script setup>
import { defineProps } from "vue";

const props = defineProps({
  loading: { type: Boolean, default: true }
});
</script>

<style scoped>
.spinner-border {
  width: 3rem;
  height: 3rem;
}
</style>
