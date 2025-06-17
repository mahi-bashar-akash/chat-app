<template>

  <form @submit.prevent="forgot()" class="w-full">
      <div class="mb-2 text-2xl"> Forgot Password </div>
      <div class="mb-5 text-sm text-gray-600"> Enter your email, and we'll send you a link to reset your password. </div>
    <div class="mb-3 block w-full">
      <label for="user-email" class="block mb-1 text-[14px]"> Email </label>
      <input id="user-email" type="email" name="email" v-model="formData.email" placeholder="Enter your email" class="text-[13px] w-full border border-gray-200 min-h-[45px] max-h-[45px] px-4 outline-0 rounded-lg duration-300 ring-0 focus-within:ring-2 focus-within:ring-[rgba(3,181,98,1)]" autocomplete="off" />
      <div class="mt-1 text-red-600 text-[12px]" v-if="error.email"> {{error.email[0]}} </div>
    </div>
    <div class="mb-3 block">
      <button type="submit" class="cursor-pointer text-[14px] w-full block bg-[rgba(3,181,98,0.8)] duration-500 hover:bg-[rgba(3,181,98,1)] min-h-[45px] max-h-[45px] px-4 rounded-lg text-white" v-if="!loading">
        Forgot
      </button>
      <button type="button" class="cursor-pointer text-[14px] w-full block bg-[rgba(3,181,98,0.8)] duration-500 hover:bg-[rgba(3,181,98,1)] min-h-[45px] max-h-[45px] px-4 rounded-lg text-white" v-if="loading">
        <span class="inline-block size-[20px] border-2 border-transparent border-t-2 border-t-white rounded-full animate-spin"></span>
      </button>
    </div>
    <div class="w-full block text-center text-[14px]">
      Remember password
      <router-link :to="{name:'login'}" class="decoration-0 text-[rgba(3,181,98,1)]">
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
