<template>
  <div class="w-full flex justify-center p-12 p-12 h-screen bg-gradient-to-tr from-gray-600 to-blue-600">
    <div class="w-full max-w-[1200px] mx-auto border border-gray-300 h-full rounded-xl overflow-hidden bg-white">
      <router-view></router-view>
    </div>
  </div>
</template>

<script>

export default {
  data() {
    return {

    }
  },
  mounted() {

  },
  methods: {

  }
}

</script>