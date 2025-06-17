<template>

    <form @submit.prevent="verification()" class="w-full block">
        <div class="mb-5 text-2xl text-center"> Verification Code</div>
        <div class="mb-3 block w-full">
            <label for="user-verification" class="block mb-1 text-center text-[14px] hidden"> Verification Code </label>
            <input id="user-verification" type="text" name="verification_code" v-model="formData.verification_code"
                   placeholder="Enter your verification code"
                   class="text-[13px] text-center w-full border border-gray-200 min-h-[45px] max-h-[45px] px-4 outline-0 rounded-lg duration-300 ring-0 focus-within:ring-2 focus-within:ring-[rgba(3,181,98,1)]"
                   autocomplete="off"/>
            <div class="mt-1 text-red-600 text-[12px]" v-if="error.verification_code">
                {{ error.verification_code[0] }}
            </div>
        </div>
        <div class="mb-3 block">
            <button type="submit" class="cursor-pointer text-[14px] w-full block bg-[rgba(3,181,98,0.8)] duration-500 hover:bg-[rgba(3,181,98,1)] min-h-[45px] max-h-[45px] px-4 rounded-lg text-white" v-if="!loading">
                Verification
            </button>
            <button type="button" class="cursor-pointer text-[14px] w-full block bg-[rgba(3,181,98,0.8)] duration-500 hover:bg-[rgba(3,181,98,1)] min-h-[45px] max-h-[45px] px-4 rounded-lg text-white" v-if="loading">
                <span class="inline-block size-[20px] border-2 border-transparent border-t-2 border-t-white rounded-full animate-spin"></span>
            </button>
        </div>
        <div class="w-full block text-center text-[14px]">
            Go back
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
                this.$router.push({name: 'login'});
            } catch (error) {
                this.error = error.response.data.errors;
            } finally {
                this.loading = false;
            }
        }

    }
}

</script>
