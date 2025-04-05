<template>
  <div class="w-full h-screen flex justify-center items-center bg-gradient-to-tr from-gray-600 to-blue-600">
    <div class="max-w-[550px] w-full mx-auto p-10 border border-gray-300 bg-gray-200">
      <div class="mb-5 flex justify-center text-[28px] font-bold">
        <a href="javascript:void(0)" class="decoration-0 text-black inline-block">
          Chat App
        </a>
      </div>
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