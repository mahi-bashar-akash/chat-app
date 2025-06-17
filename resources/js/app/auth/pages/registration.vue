<template>

    <form @submit.prevent="registration()" class="w-full">
        <div class="mb-2 text-2xl"> Sign Up</div>
        <div class="mb-3 text-sm text-gray-600"> Sign up now to create a free account and join us!</div>
        <div class="w-full mb-3 block">
            <label for="user-name" class="block mb-1 text-[14px]"> Name </label>
            <input id="user-name" type="text" name="name" v-model="formData.name" placeholder="Enter your name" class="text-[13px] w-full border border-gray-200 min-h-[45px] max-h-[45px] px-4 outline-0 rounded-lg duration-300 ring-0 focus-within:ring-2 focus-within:ring-[rgba(3,181,98,1)]" autocomplete="off"/>
            <div class="mt-1 text-rose-600 text-[13px]" v-if="error.name"> {{ error.name[0] }}</div>
        </div>
        <div class="mb-2 block w-full">
            <label for="user-email" class="block mb-1 text-[14px]"> Email </label>
            <input id="user-email" type="email" name="email" v-model="formData.email" placeholder="Enter your email" class="text-[13px] w-full border border-gray-200 min-h-[45px] max-h-[45px] px-4 outline-0 rounded-lg duration-300 ring-0 focus-within:ring-2 focus-within:ring-[rgba(3,181,98,1)]" autocomplete="off"/>
            <div class="mt-1 text-rose-600 text-[13px]" v-if="error.email"> {{ error.email[0] }}</div>
        </div>
        <div class="mb-2 block w-full">
            <label for="user-phone" class="block mb-1 text-[14px]"> Phone </label>
            <input id="user-phone" type="text" name="phone" v-model="formData.phone" placeholder="Enter your phone" class="appearance-none text-[13px] w-full border border-gray-200 min-h-[45px] max-h-[45px] px-4 outline-0 rounded-lg duration-300 ring-0 focus-within:ring-2 focus-within:ring-[rgba(3,181,98,1)]" autocomplete="off"/>
            <div class="mt-1 text-rose-600 text-[13px]" v-if="error.phone"> {{ error.phone[0] }}</div>
        </div>
        <div class="mb-2 block w-full">
            <label for="user-password" class="block mb-1 text-[14px]"> Password </label>
            <input id="user-password" type="password" name="password" v-model="formData.password" placeholder="Enter your password" class="text-[13px] w-full border border-gray-200 min-h-[45px] max-h-[45px] px-4 outline-0 rounded-lg duration-300 ring-0 focus-within:ring-2 focus-within:ring-[rgba(3,181,98,1)]" autocomplete="off"/>
            <div class="mt-1 text-rose-600 text-[13px]" v-if="error.password"> {{ error.password[0] }}</div>
        </div>
        <div class="mb-2 block w-full">
            <label for="user-conform_password" class="block mb-1 text-[14px]"> Confirm Password </label>
            <input id="user-conform_password" type="password" name="password_confirmation" v-model="formData.password_confirmation" placeholder="Enter your confirm password" class="text-[13px] w-full border border-gray-200 min-h-[45px] max-h-[45px] px-4 outline-0 rounded-lg duration-300 ring-0 focus-within:ring-2 focus-within:ring-[rgba(3,181,98,1)]" autocomplete="off"/>
            <div class="mt-1 text-rose-600 text-[13px]" v-if="error.password_confirmation">
                {{ error.password_confirmation[0] }}
            </div>
        </div>
        <div class="mb-2 block w-full">
            <div class="inline-flex items-center">
                <label for="agree_terms_and_conditions" class="cursor-pointer text-[14px] flex items-center font-medium">
                  <span class="relative inline-flex items-center">
                    <input id="agree_terms_and_conditions" type="checkbox" @change="rememberCheck()" :checked="formData.agree_terms_and_conditions === true" class="peer h-5 w-5 cursor-pointer transition-all appearance-none rounded shadow hover:shadow-md border border-slate-300 checked:bg-slate-800 checked:border-slate-800"/>
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
            <button type="submit" class="cursor-pointer text-[14px] w-full block bg-[rgba(3,181,98,0.8)] duration-500 hover:bg-[rgba(3,181,98,1)] min-h-[45px] max-h-[45px] px-4 rounded-lg text-white" v-if="!loading">
                Create Account
            </button>
            <button type="button" class="cursor-pointer text-[14px] w-full block bg-[rgba(3,181,98,0.8)] duration-500 hover:bg-[rgba(3,181,98,1)] min-h-[45px] max-h-[45px] px-4 rounded-lg text-white" v-if="loading">
                <span class="inline-block size-[20px] border-2 border-transparent border-t-2 border-t-white rounded-full animate-spin"></span>
            </button>
        </div>
        <div class="w-full block text-center text-[14px]">
            Already have an account?
            <router-link :to="{name:'login'}" class="decoration-0 text-[rgba(3,181,98,1)]">
                Sign In
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
                this.$router.push({name: 'verification'});
            } catch (error) {
                this.error = error.response.data.errors;
            } finally {
                this.loading = false;
            }
        }

    }
}

</script>
