<template>

  <form @submit.prevent="verification()" class="w-full">
    <div class="mb-2 block w-full">
      <label for="user-verification" class="block mb-1 w-full font-medium"> Verification Code </label>
      <input id="user-verification" type="text" name="verification_code" v-model="formData.verification_code" placeholder="Enter your verification code" class="w-full min-h-[45px] duration-500 rounded-lg border border-gray-300 block px-5 focus-within:border-blue-600 outline-0" autocomplete="off" />
      <div class="mt-1 text-rose-600 text-[13px]" v-if="error.verification_code"> {{error.verification_code[0]}} </div>
    </div>
    <div class="mb-3 block">
      <button type="submit" class="bg-blue-600 duration-500 hover:bg-blue-800 font-medium text-white px-7 min-h-[45px] rounded-lg">
        Verification
      </button>
    </div>
    <div class="text-center font-medium">
      Go back
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
        email: JSON.parse(localStorage.getItem('user')).email,
        verification_code: '',
      },
    }
  },
  mounted() {

  },
  methods: {

    /*** verification api implementation ***/
    async verification() {
      this.loading = true;
      try {
        const response = await axios.post(apiRoute.verification, this.formData, {headers: apiService.authHeaderContent()});
        this.$router.push({name:'login'});
      } catch (error) {
        this.error = error.response.data.errors;
      } finally {
        this.loading = false;
      }
    }

  }
}

</script>