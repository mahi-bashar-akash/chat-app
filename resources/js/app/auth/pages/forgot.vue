<template>

  <form @submit.prevent="forgot()" class="w-full">
    <div class="mb-2 block w-full">
      <label for="user-email" class="block mb-1 w-full font-medium"> Email </label>
      <input id="user-email" type="email" name="email" v-model="formData.email" placeholder="Enter your email" class="w-full min-h-[45px] duration-500 rounded-lg border border-gray-300 block px-5 focus-within:border-blue-600 outline-0" autocomplete="off" />
      <div class="mt-1 text-rose-600 text-[13px]" v-if="error.email"> {{error.email[0]}} </div>
    </div>
    <div class="mb-3 block">
      <button type="submit" class="bg-blue-600 duration-500 hover:bg-blue-800 font-medium text-white px-7 min-h-[45px] rounded-lg">
        Forgot
      </button>
    </div>
    <div class="text-center font-medium">
      Remember password
      <router-link :to="{name:'login'}" class="underline inline-block text-blue-500 duration-500 hover:text-blue-800">
        Login
      </router-link>
    </div>
  </form>

</template>

<script>

import axios from "axios";

import apiRoute from "../../api/apiRoute.js";
import apiService from "../../api/apiService.js";

export default {
  data() {
    return {
      loading: false,
      error: {},
      formData: {
        email: '',
      },
    }
  },
  mounted() {

  },
  methods: {

    /*** forgot api implementation ***/
    async forgot() {
      this.loading = true;
      try {
        const response = await axios.post(apiRoute.forgot, this.formData, {headers: apiService.authHeaderContent()});
        localStorage.setItem('email', this.formData.email);
        this.$router.push({name:'reset'});
      } catch (error) {
        this.error = error.response.data.errors;
      } finally {
        this.loading = false;
      }
    }

  }
}

</script>