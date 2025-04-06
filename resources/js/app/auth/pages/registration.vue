<template>

  <form @submit.prevent="registration()" class="w-full">
    <div class="mb-2 block w-full">
      <label for="user-name" class="block mb-1 w-full font-medium"> Name </label>
      <input id="user-name" type="text" name="name" v-model="formData.name" placeholder="Enter your name" class="w-full min-h-[45px] duration-500 rounded-lg border border-gray-300 block px-5 focus-within:border-blue-600 outline-0" autocomplete="off" />
      <div class="mt-1 text-rose-600 text-[13px]" v-if="error.name"> {{error.name[0]}} </div>
    </div>
    <div class="mb-2 block w-full">
      <label for="user-email" class="block mb-1 w-full font-medium"> Email </label>
      <input id="user-email" type="email" name="email" v-model="formData.email" placeholder="Enter your email" class="w-full min-h-[45px] duration-500 rounded-lg border border-gray-300 block px-5 focus-within:border-blue-600 outline-0" autocomplete="off" />
      <div class="mt-1 text-rose-600 text-[13px]" v-if="error.email"> {{error.email[0]}} </div>
    </div>
    <div class="mb-2 block w-full">
      <label for="user-phone" class="block mb-1 w-full font-medium"> Phone </label>
      <input id="user-phone" type="text" name="phone" v-model="formData.phone" placeholder="Enter your phone" class="appearance-none w-full min-h-[45px] duration-500 rounded-lg border border-gray-300 block px-5 focus-within:border-blue-600 outline-0" autocomplete="off" />
      <div class="mt-1 text-rose-600 text-[13px]" v-if="error.phone"> {{error.phone[0]}} </div>
    </div>
    <div class="mb-2 block w-full">
      <label for="user-password" class="block mb-1 w-full font-medium"> Password </label>
      <input id="user-password" type="password" name="password" v-model="formData.password" placeholder="Enter your password" class="w-full min-h-[45px] duration-500 rounded-lg border border-gray-300 block px-5 focus-within:border-blue-600 outline-0" autocomplete="off" />
      <div class="mt-1 text-rose-600 text-[13px]" v-if="error.password"> {{error.password[0]}} </div>
    </div>
    <div class="mb-2 block w-full">
      <label for="user-conform_password" class="block mb-1 w-full font-medium"> Confirm Password </label>
      <input id="user-conform_password" type="password" name="password_confirmation" v-model="formData.password_confirmation" placeholder="Enter your confirm password" class="w-full min-h-[45px] duration-500 rounded-lg border border-gray-300 block px-5 focus-within:border-blue-600 outline-0" autocomplete="off" />
      <div class="mt-1 text-rose-600 text-[13px]" v-if="error.password_confirmation"> {{error.password_confirmation[0]}} </div>
    </div>
    <div class="mb-2 block w-full">
      <div class="inline-flex items-center">
        <label for="agree_terms_and_conditions" class="cursor-pointer flex items-center font-medium">
          <span class="relative inline-flex items-center">
            <input id="agree_terms_and_conditions" type="checkbox" @change="rememberCheck()" :checked="formData.agree_terms_and_conditions === true" class="peer h-5 w-5 cursor-pointer transition-all appearance-none rounded shadow hover:shadow-md border border-slate-300 checked:bg-slate-800 checked:border-slate-800" />
            <span class="absolute text-white opacity-0 peer-checked:opacity-100 top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 pointer-events-none">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor" stroke="currentColor" stroke-width="1">
                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
              </svg>
            </span>
          </span>
          <span class="ms-2 inline-block">
            I agree with
            <a href="javascript:void(0)" class="underline text-blue-600 duration-500 hover:text-blue-950"> terms </a>
            and
            <a href="javascript:void(0)" class="underline text-blue-600 duration-500 hover:text-blue-950"> conditions </a>
          </span>
        </label>
      </div>
    </div>
    <div class="mb-3 block">
      <button type="submit" class="bg-blue-600 inline-flex justify-center items-center duration-500 hover:bg-blue-800 text-white max-w-[150px] min-w-[150px] max-h-[45px] min-h-[45px] rounded-lg font-medium" v-if="!loading">
        Registration
      </button>
      <button type="button" class="bg-blue-600 inline-flex justify-center items-center duration-500 hover:bg-blue-800 font-medium text-white max-w-[150px] min-w-[150px] max-h-[45px] min-h-[45px] rounded-lg" v-if="loading">
        <span class="inline-block size-[20px] border-2 border-transparent border-t-2 border-t-white rounded-full animate-spin"></span>
      </button>
    </div>
    <div class="text-center font-medium">
      Already Registered
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
        name: '',
        email: '',
        phone: '',
        password: '',
        password_confirmation: '',
        agree_terms_and_conditions: '',
      },
    }
  },
  mounted() {

  },
  methods: {

    /*** remember check ***/
    rememberCheck() {
      this.formData.agree_terms_and_conditions = this.formData.agree_terms_and_conditions !== true;
    },

    /*** registration api implementation ***/
    async registration() {
      this.loading = true;
      try {
        const response = await axios.post(apiRoute.registration, this.formData, {headers: apiService.authHeaderContent()});
        localStorage.setItem('user', JSON.stringify(response.data.user));
        this.$router.push({name:'verification'});
      } catch (error) {
        this.error = error.response.data.errors;
      } finally {
        this.loading = false;
      }
    }

  }
}

</script>