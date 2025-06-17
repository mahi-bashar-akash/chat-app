<template>

    <form @submit.prevent="login()" class="w-full">
        <div class="mb-2 text-2xl"> Sign In</div>
        <div class="mb-3 text-sm text-gray-600"> Welcome back, user! We're glad to see you again!</div>
        <div class="mb-5">
            <div class="bg-red-200 text-red-700 text-[14px] min-h-[45px] max-h-[45px] inline-flex justify-center w-full items-center rounded-lg text-center" v-if="credentialsError">
                {{ credentialsError }}
            </div>
        </div>
        <div class="mb-3 block w-full">
            <label for="user-email" class="block mb-1 text-[14px]"> Email </label>
            <input id="user-email" type="email" name="email" v-model="formData.email" placeholder="Enter your email"
                   class="text-[13px] w-full border border-gray-200 min-h-[45px] max-h-[45px] px-4 outline-0 rounded-lg duration-300 ring-0 focus-within:ring-2 focus-within:ring-[rgba(3,181,98,1)]"
                   autocomplete="off"/>
            <div class="mt-1 text-red-600 text-[12px]" v-if="error.email"> {{ error.email[0] }}</div>
        </div>
        <div class="mb-3 block w-full">
            <label for="user-password" class="text-[14px] flex justify-between items-center mb-1">
                <span> Password </span>
                <router-link :to="{name:'forgot'}" class="decoration-0 text-red-600 text-[13px]"> Forgot Password?</router-link>
            </label>
            <div class="w-full relative">
            <input id="user-password" :type="passwordFieldType" name="password" v-model="formData.password"
                   placeholder="Enter your password"
                   class="text-[13px] w-full border border-gray-200 min-h-[45px] max-h-[45px] px-4 outline-0 rounded-lg duration-300 ring-0 focus-within:ring-2 focus-within:ring-[rgba(3,181,98,1)]"
                   autocomplete="off"/>
                <div class="absolute top-0 bottom-0 end-0 pe-3 pointer-event-none inline-flex justify-center items-center">
                    <template v-if="passwordFieldType === 'text'">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-4 cursor-pointer" @click="passwordVisibility()">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        </svg>
                    </template>
                    <template v-if="passwordFieldType === 'password'">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-4 cursor-pointer" @click="passwordVisibility()">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                        </svg>
                    </template>
                </div>
            </div>
            <div class="mt-1 text-red-600 text-[12px]" v-if="error.password"> {{ error.password[0] }}</div>
        </div>
        <div class="mb-3 block w-full">
            <div class="inline-flex items-center">
                <label for="remember_me" class="cursor-pointer flex items-center text-[14px]">
                  <span class="relative inline-flex items-center">
                    <input id="remember_me" type="checkbox" @change="rememberCheck()" :checked="formData.remember === true"
                           class="peer h-5 w-5 cursor-pointer transition-all appearance-none rounded shadow hover:shadow-md border border-slate-300 checked:bg-[rgba(3,181,98,1)] checked:border-[rgba(3,181,98,1)]"/>
                    <span
                        class="absolute text-white opacity-0 peer-checked:opacity-100 top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 pointer-events-none">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor"
                           stroke="currentColor" stroke-width="1">
                        <path fill-rule="evenodd"
                              d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                              clip-rule="evenodd"></path>
                      </svg>
                    </span>
                  </span>
                    <span class="ms-2 inline-block"> Remember Me </span>
                </label>
            </div>
        </div>
        <div class="mb-3 block">
            <button type="submit" class="cursor-pointer text-[14px] w-full block bg-[rgba(3,181,98,0.8)] duration-500 hover:bg-[rgba(3,181,98,1)] min-h-[45px] max-h-[45px] px-4 rounded-lg text-white" v-if="!loading">
                Login
            </button>
            <button type="button" class="cursor-pointer w-full block bg-[rgba(3,181,98,0.8)] duration-500 hover:bg-[rgba(3,181,98,1)] min-h-[45px] max-h-[45px] px-4 rounded-lg text-white" v-if="loading">
                <span class="inline-block size-[20px] border-2 border-transparent border-t-2 border-t-white rounded-full animate-spin"></span>
            </button>
        </div>
        <div class="w-full block text-center text-[14px]">
            Dont have an account?
            <router-link :to="{name:'registration'}" class="decoration-0 text-[rgba(3,181,98,1)]">
                Sign Up
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
            credentialsError: null,
            error: {},
            passwordFieldType: 'password',
            formData: {
                email: '',
                password: '',
                remember: false,
            },
        }
    },
    mounted() {

    },
    methods: {

        /*** password visibility ***/
        passwordVisibility() {
            this.passwordFieldType = this.passwordFieldType === "password" ? "text" : "password";
        },

        /*** remember check ***/
        rememberCheck() {
            this.formData.remember = this.formData.remember !== true;
        },

        /*** login api implementation ***/
        async login() {
            this.loading = true;
            this.error = {};
            this.credentialsError = '';
            try {
                const response = await axios.post(apiRoute.login, this.formData, {headers: apiService.authHeaderContent()});
                localStorage.setItem('user', JSON.stringify(response.data.user));
                localStorage.setItem('token', response.data.token)
                this.$router.push({name: 'index'});
            } catch (error) {
                if (error.response.data.error) {
                    this.credentialsError = error.response.data.error
                } else {
                    this.error = error.response.data.errors;
                }
            } finally {
                this.loading = false;
            }
        },

    }
}

</script>
