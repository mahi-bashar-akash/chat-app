<template>
  <router-view></router-view>
</template>
<script>
import { defineComponent } from 'vue'
export default defineComponent({
  name:'App',
  watch: {
    $route: {
      immediate: true,
      handler(to, from) {
        document.title = to.meta.title || 'Chat App';
      }
    },
  }
})
</script>
