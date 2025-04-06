<template>

  <form @submit.prevent="reset()" class="w-full">
    <div class="mb-2 block w-full">
      <label for="user-email" class="block mb-1 w-full font-medium"> Email </label>
      <input id="user-email" type="email" name="email" v-model="formData.email" placeholder="Enter your email" class="w-full min-h-[45px] duration-500 rounded-lg border border-gray-300 block px-5 focus-within:border-blue-600 outline-0" autocomplete="off" />
      <div class="mt-1 text-rose-600 text-[13px]" v-if="error.email"> {{error.email[0]}} </div>
    </div>
    <div class="mb-2 block w-full">
      <label for="user-reset-code" class="block mb-1 w-full font-medium"> Reset Code </label>
      <input id="user-reset-code" type="text" name="reset_code" v-model="formData.reset_code" placeholder="Enter your reset code" class="w-full min-h-[45px] duration-500 rounded-lg border border-gray-300 block px-5 focus-within:border-blue-600 outline-0" autocomplete="off" />
      <div class="mt-1 text-rose-600 text-[13px]" v-if="error.reset_code"> {{error.reset_code[0]}} </div>
    </div>
    <div class="mb-2 block w-full">
      <label for="user-password" class="block mb-1 w-full font-medium"> Password </label>
      <input id="user-password" type="password" name="password" v-model="formData.password" placeholder="Enter your password" class="w-full min-h-[45px] duration-500 rounded-lg border border-gray-300 block px-5 focus-within:border-blue-600 outline-0" autocomplete="off" />
      <div class="mt-1 text-rose-600 text-[13px]" v-if="error.email"> {{error.email[0]}} </div>
    </div>
    <div class="mb-2 block w-full">
      <label for="user-confirm-password" class="block mb-1 w-full font-medium"> Confirm Password </label>
      <input id="user-confirm-password" type="password" name="password_confirmation" v-model="formData.password_confirmation" placeholder="Enter your confirm password" class="w-full min-h-[45px] duration-500 rounded-lg border border-gray-300 block px-5 focus-within:border-blue-600 outline-0" autocomplete="off" />
      <div class="mt-1 text-rose-600 text-[13px]" v-if="error.password_confirmation"> {{error.password_confirmation[0]}} </div>
    </div>
    <div class="mb-3 block">
      <button type="submit" class="bg-blue-600 inline-flex justify-center items-center duration-500 hover:bg-blue-800 font-medium text-white max-w-[120px] min-w-[120px] max-h-[45px] min-h-[45px] rounded-lg" v-if="!loading">
        Reset
      </button>
      <button type="button" class="bg-blue-600 inline-flex justify-center items-center duration-500 hover:bg-blue-800 font-medium text-white max-w-[120px] min-w-[120px] max-h-[45px] min-h-[45px] rounded-lg" v-if="loading">
        <span class="inline-block size-[20px] border-2 border-transparent border-t-2 border-t-white rounded-full animate-spin"></span>
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
        email: localStorage.getItem('email'),
        reset_code: '',
        password: '',
        confirm_password: '',
      },
    }
  },
  mounted() {

  },
  methods: {

    /*** reset api implementation ***/
    async reset() {
      this.loading = true;
      try {
        const response = await axios.post(apiRoute.reset, this.formData, {headers: apiService.authHeaderContent()});
        localStorage.removeItem('email');
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