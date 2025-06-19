<template>

    <div class="w-full flex justify-between items-center px-3 py-3 border-b border-b-gray-300">
        <div class="text-[25px] font-bold"> Chat App </div>
        <div class="relative" id="userDropdown">
            <div class="cursor-pointer size-[55px] bg-gray-700 font-medium text-white rounded-full inline-flex justify-center items-center" @click="openUserDropdown()">
                {{ shortName(profileData?.name) }}
            </div>
            <ul class="absolute top-auto end-0 min-w-[210px] p-2 bg-white drop-shadow-xl border mt-2 rounded-lg z-50" v-if="isUserDropdown" @click.stop>
                <li>
                    <button type="button" class="cursor-pointer decoration-0 block w-full px-5 min-h-[48px] max-h-[48px] font-medium text-start bg-transparent duration-500 hover:bg-gray-300 rounded-lg" @click="closeUserDropdown();openEditProfileModal()">
                        Edit Profile
                    </button>
                </li>
                <li>
                    <button type="button" class="cursor-pointer decoration-0 block w-full px-5 min-h-[48px] max-h-[48px] font-medium text-start bg-transparent duration-500 hover:bg-gray-300 rounded-lg" @click="closeUserDropdown();openChangePasswordModal()">
                        Change Password
                    </button>
                </li>
                <li>
                    <button type="button" class="cursor-pointer decoration-0 block w-full px-5 min-h-[48px] max-h-[48px] font-medium text-start bg-transparent duration-500 hover:bg-gray-300 rounded-lg" v-if="!logoutLoading" @click="logout()">
                        Logout
                    </button>
                    <button type="button" class="cursor-pointer decoration-0 flex justify-center items-center w-full px-5 min-h-[48px] max-h-[48px] font-medium text-start bg-transparent duration-500 hover:bg-gray-300 rounded-lg" v-if="logoutLoading">
                        <span class="inline-block size-[20px] rounded-full border-2 border-transparent border-t-2 border-t-black animate-spin"></span>
                    </button>
                </li>
            </ul>
        </div>
    </div>

    <div class="w-full flex justify-start items-start h-[calc(100vh-178px)] overflow-hidden">

        <!-- Search with Users List part -->
        <div class="min-w-[350px] h-[calc(100vh-178px)] border-e border-e-gray-300">

            <!-- Search -->
            <div class="p-3 w-full">
                <input type="text" name="keyword" v-model="keyword" @input="searchData()" placeholder="Search Here ..." class="w-full px-5 min-h-[50px] outline-0 block border-0 bg-gray-200 rounded-lg duration-500 focus-within:border-blue-700">
            </div>

            <!-- Users List -->
            <div class="p-3 w-full scrollbar h-[calc(100vh-250px)]">
                <template v-for="each in showList" v-if="userData.length > 0">
                    <button type="button" class="w-full flex justify-start items-center bg-transparent duration-500 hover:bg-gray-200 p-3 rounded-lg" @click="selectUser(each)">
                        <span class="min-w-[50px] relative min-h-[50px] size-[50px] inline-flex justify-center rounded-full items-center bg-gray-600 text-white">
                            <span class="-top-[1px] -start-[1px] size-[20px] bg-green-500 rounded-full absolute hidden"></span>
                            <span class="-top-[1px] -start-[1px] size-[20px] bg-gray-300 rounded-full absolute hidden"></span>
                                {{ shortName(each.name) }}
                            </span>
                        <span class="ms-2 block">
                        <span class="block font-bold text-start"> {{ each.name }} </span>
                            <span class="block font-medium text-[13px] text-start">
                                <span class="truncate overflow-hidden max-w-[200px]">
                                    {{ each.email }}
                                </span>
                            </span>
                        </span>
                    </button>
                </template>
            </div>

        </div>

        <!-- User conversation -->
        <div class="min-w-[calc(100%-350px)]">

            <!-- Another user chat visible part -->
            <template v-if="selectedUserInitials">
                <div class="w-full h-[calc(100vh-175px)]">

                    <!-- Another User info and action part -->
                    <div class="w-full flex justify-between items-center p-3">

                        <!-- Another User info part -->
                        <div class="flex justify-start items-center">
                            <div
                                class="min-w-[50px] min-h-[50px] size-[50px] inline-flex justify-center rounded-full items-center bg-gray-600 text-white">
                                {{ selectedUserInitials }}
                            </div>
                            <div class="ms-2">
                                <div class="font-bold"> {{ selectedUser.name }}</div>
                                <div class="font-medium text-[13px]"> {{ selectedUser.email }}</div>
                            </div>
                        </div>

                        <!-- Another User action part -->
                        <div class="relative" id="otherUserDropdown">
                            <button type="button" class="size-[50px] min-w-[50px] min-h-[50px] rounded-full inline-flex justify-center items-center bg-transparent duration-500 hover:bg-gray-300" @click="openOtherUserDropdown()">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-[20px]">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5ZM12 12.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5ZM12 18.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5Z"/>
                                </svg>
                            </button>
                            <ul class="absolute top-auto end-0 min-w-[210px] p-2 bg-white drop-shadow-xl border mt-2 rounded-lg z-50" v-if="isOtherUserDropdown" @click.stop>
                                <li>
                                    <button type="button" class="cursor-pointer decoration-0 block w-full px-5 py-3 font-medium text-start bg-transparent duration-500 hover:bg-gray-300 rounded-lg" @click="chatClear()" v-if="!chatClearLoading">
                                        Clear Chat
                                    </button>
                                    <button type="button" class="cursor-pointer decoration-0 flex justify-center items-center w-full px-5 min-h-[48px] max-h-[48px] font-medium text-start bg-transparent duration-500 hover:bg-gray-300 rounded-lg" v-if="chatClearLoading">
                                        <span class="inline-block size-[20px] border-2 border-transparent border-t-2 border-t-black rounded-full animate-spin"></span>
                                    </button>
                                </li>
                                <li>
                                    <button type="button" class="cursor-pointer decoration-0 block w-full px-5 py-3 font-medium text-start bg-transparent duration-500 hover:bg-gray-300 rounded-lg" @click="closeOtherUserDropdown();selectedUserInitials = ''">
                                        Exit Chat
                                    </button>
                                </li>
                            </ul>
                        </div>

                    </div>

                    <!-- Chats -->
                    <div class="w-full h-[calc(100vh-325px)] bg-gray-200 p-3 scrollbar overflow-y-auto" ref="chatContainer">
                        <div v-for="(message) in chats" :key="message.id" class="flex mb-3" :class="message.sender_id == profileData.id ? 'justify-end items-start' : 'justify-start items-start'">

                            <!-- Received Message -->
                            <template v-if="message.sender_id != profileData.id">
                                <div class="min-w-[50px] min-h-[50px] inline-flex bg-white justify-center font-medium items-center rounded-full me-3 shadow-lg">
                                    {{ getInitials(message.sender_id) }}
                                </div>
                                <div class="bg-white shadow-lg overflow-hidden rounded-lg min-w-[250px]">
                                    <div class="w-full px-4 py-2 text-[16px] font-medium">
                                        {{ message.message }}
                                    </div>
                                    <div class="text-[12px] text-end bg-gray-100 px-4 py-2 shadow-inner">
                                        {{ formatTime(message.created_at) }}
                                    </div>
                                </div>
                            </template>

                            <!-- Sent Message -->
                            <template v-else>
                                <div class="bg-[rgba(3,181,98,0.9)] shadow-lg overflow-hidden text-white rounded-lg min-w-[250px]">
                                    <div class="w-full px-4 py-2 text-[16px] font-medium">
                                        {{ message.message }}
                                    </div>
                                    <div class="text-[12px] text-start bg-[rgba(3,181,98,0.9)] text-white px-4 py-2 shadow-inner">
                                        {{ formatTime(message.created_at) }}
                                    </div>
                                </div>
                                <div class="min-w-[50px] min-h-[50px] inline-flex justify-center bg-[rgba(3,181,98,1)] text-white font-medium items-center rounded-full ms-3 shadow-lg">
                                    {{ getInitials(profileData.id) }}
                                </div>
                            </template>

                        </div>
                        <div v-if="isTyping" class="flex justify-start items-center gap-2">
                            <div class="size-2 bg-gray-300 animate-pulse rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                            <div class="size-2 bg-gray-300 animate-pulse rounded-full animate-bounce" style="animation-delay: 0.4s"></div>
                            <div class="size-2 bg-gray-300 animate-pulse rounded-full animate-bounce" style="animation-delay: 0.6s"></div>
                        </div>
                        <div ref="scrollAnchor"></div>
                    </div>

                    <!-- User text submit part -->
                    <form @submit.prevent="manageChat()" class="p-3 w-full flex justify-between items-center">
                        <input type="text" name="message" v-model="formData.message" @input="sendTyping" placeholder="Typing Here ..." class="w-full min-h-[50px] px-5 outline-0 bg-gray-200 border-0 block rounded-lg" autocomplete="off"/>
                        <button type="submit" class="size-[50px] min-w-[50px] min-h-[50px] w-[50px] h-[50px] outline-0 border-0 bg-gray-200 inline-flex justify-center items-center group rounded-full ms-3">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 duration-500 rotate-0 group-hover:-rotate-45">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5"/>
                            </svg>
                        </button>
                    </form>

                </div>
            </template>

            <!-- empty Screen -->
            <template v-else>
                <div class="w-full h-[calc(100vh-175px)] flex justify-center items-center text-center flex-col">
                    <div class="text-[19px] font-bold mb-1">
                        No chat message
                    </div>
                    <div class="text-[16px] font-medium">
                        Available for now
                    </div>
                </div>
            </template>

        </div>

    </div>

    <!-- edit profile modal -->
    <div class="fixed inset-0 p-5 size-full flex justify-center items-center duration-500 z-50" :class="{ 'invisible bg-black/35' : !isEditProfileModal, 'visible bg-black/65' : isEditProfileModal }" @click="isEditProfileModal = false">
        <form @submit.prevent="editProfile()" class="bg-white rounded-2xl w-full max-w-[550px] duration-500 origin-top p-10" :class="{ 'translate-y-0 opacity-100' : isEditProfileModal, '-translate-y-1/2 opacity-0' : !isEditProfileModal }" @click.stop>
            <div class="flex justify-between items-center mb-3">
                <div class="text-[21px] font-bold"> Edit Profile</div>
                <button type="button" class="size-[45px] inline-flex justify-center items-center bg-transparent duration-500 hover:bg-gray-300 rounded-full" @click="closeEditProfileModal()">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" class="size-6 stroke-black pointer-events-none">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div class="w-full block">
                <div class="block w-full mb-3">
                    <label for="name" class="block mb-1 w-full font-medium"> Name </label>
                    <input id="name" type="text" name="name" v-model="profileParam.name" class="w-full block px-4 py-3 rounded-xl outline-0 border border-gray-300 focus-within:border-blue-600 duration-500" autocomplete="off"/>
                    <div class="text-[13px] text-rose-600" v-if="error.name"> {{ error.name[0] }}</div>
                </div>
                <div class="block w-full mb-3">
                    <label for="email" class="block mb-1 w-full font-medium"> Email </label>
                    <input id="email" type="email" name="email" v-model="profileParam.email" class="w-full block px-4 py-3 rounded-xl outline-0 border border-gray-300 focus-within:border-blue-600 duration-500" autocomplete="off"/>
                    <div class="text-[13px] text-rose-600" v-if="error.email"> {{ error.email[0] }}</div>
                </div>
                <div class="block w-full mb-3">
                    <label for="phone" class="block mb-1 w-full font-medium"> Phone </label>
                    <input id="phone" type="text" name="phone" v-model="profileParam.phone" class="w-full block px-4 py-3 rounded-xl outline-0 border border-gray-300 focus-within:border-blue-600 duration-500" autocomplete="off"/>
                    <div class="text-[13px] text-rose-600" v-if="error.phone"> {{ error.phone[0] }}</div>
                </div>
            </div>
            <div class="flex justify-end items-center gap-3">
                <button type="button" class="outline-0 border-0 bg-gray-200 duration-500 hover:bg-gray-700 text-black hover:text-white inline-flex justify-center items-center min-w-[120px] min-h-[48px] max-h-[48px] rounded-xl" @click="closeEditProfileModal()">
                    Cancel
                </button>
                <button type="submit" class="outline-0 border-0 bg-[#03b562]/25 duration-500 hover:bg-bg-[#03b562] text-[#03b562] hover:text-white inline-flex justify-center items-center min-w-[120px] min-h-[48px] max-h-[48px] rounded-xl" v-if="!editProfileLoading">
                    Update
                </button>
                <button type="button" class="outline-0 border-0 bg-[#03b562]/25 duration-500 hover:bg-bg-[#03b562] text-[#03b562] hover:text-white inline-flex justify-center items-center min-w-[120px] min-h-[48px] max-h-[48px] rounded-xl" v-if="editProfileLoading">
                    <span class="inline-block size-[20px] rounded-full border-2 border-transparent border-t-2 border-t-blue-600 group-hover:border-t-white animate-spin"></span>
                </button>
            </div>
        </form>
    </div>

    <!-- change password modal -->
    <div class="fixed inset-0 p-5 size-full flex justify-center items-center duration-500 z-50" :class="{ 'invisible bg-black/35' : !isChangePasswordModal, 'visible bg-black/65' : isChangePasswordModal }" @click="isChangePasswordModal = false">
        <form @submit.prevent="changePassword()" class="bg-white rounded-2xl w-full max-w-[550px] duration-500 origin-top p-10" :class="{ 'translate-y-0 opacity-100' : isChangePasswordModal, '-translate-y-1/2 opacity-0' : !isChangePasswordModal }" @click.stop>
            <div class="flex justify-between items-center mb-3">
                <div class="text-[21px] font-bold"> Change Password</div>
                <button type="button" class="size-[45px] inline-flex justify-center items-center bg-transparent duration-500 hover:bg-gray-300 rounded-full" @click="closeChangePasswordModal()">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" class="size-6 stroke-black pointer-events-none">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div class="w-full block">
                <div class="block w-full mb-3">
                    <label for="current_password" class="block mb-1 w-full font-medium"> Current Password </label>
                    <input id="current_password" type="password" name="current_password" placeholder="Enter your current password" v-model="passwordParam.current_password" class="w-full block px-4 py-3 rounded-xl outline-0 border border-gray-300 focus-within:border-blue-600 duration-500" autocomplete="off"/>
                    <div class="text-[13px] text-rose-600" v-if="error.current_password">
                        {{ error.current_password[0] }}
                    </div>
                </div>
                <div class="block w-full mb-3">
                    <label for="new_password" class="block mb-1 w-full font-medium"> New Password </label>
                    <input id="new_password" type="password" name="new_password" placeholder="Enter your new password" v-model="passwordParam.new_password" class="w-full block px-4 py-3 rounded-xl outline-0 border border-gray-300 focus-within:border-blue-600 duration-500" autocomplete="off"/>
                    <div class="text-[13px] text-rose-600" v-if="error.new_password"> {{ error.new_password[0] }}</div>
                </div>
                <div class="block w-full mb-3">
                    <label for="new_password_confirmation" class="block mb-1 w-full font-medium"> New Confirm Password </label>
                    <input id="new_password_confirmation" type="password" name="new_password_confirmation" placeholder="Enter your new confirm password" v-model="passwordParam.new_password_confirmation" class="w-full block px-4 py-3 rounded-xl outline-0 border border-gray-300 focus-within:border-blue-600 duration-500" autocomplete="off"/>
                    <div class="text-[13px] text-rose-600" v-if="error.new_password_confirmation">
                        {{ error.new_password_confirmation[0] }}
                    </div>
                </div>
            </div>
            <div class="flex justify-end items-center gap-3">
                <button type="button" class="outline-0 border-0 bg-gray-200 duration-500 hover:bg-gray-700 text-black hover:text-white inline-flex justify-center items-center min-w-[120px] min-h-[48px] max-h-[48px] rounded-xl" @click="closeChangePasswordModal()">
                    Cancel
                </button>
                <button type="submit" class="outline-0 border-0 bg-[#03b562]/25 duration-500 hover:bg-bg-[#03b562] text-[#03b562] hover:text-white inline-flex justify-center items-center min-w-[120px] min-h-[48px] max-h-[48px] rounded-xl" v-if="!changePasswordLoading">
                    Update
                </button>
                <button type="button" class="outline-0 border-0 bg-[#03b562]/25 duration-500 hover:bg-bg-[#03b562] text-[#03b562] hover:text-white inline-flex justify-center items-center min-w-[120px] min-h-[48px] max-h-[48px] rounded-xl" v-if="changePasswordLoading">
                    <span class="inline-block size-[20px] rounded-full border-2 border-transparent border-t-2 border-t-blue-600 group-hover:border-t-white animate-spin"></span>
                </button>
            </div>
        </form>
    </div>

    <!-- chat delete modal -->
    <div class="fixed inset-0 p-5 size-full flex justify-center items-center duration-500 z-50" :class="{ 'invisible bg-black/35' : !isChatDeleteModal, 'visible bg-black/65' : isChatDeleteModal }" @click="isChatDeleteModal = false">
        <form @submit.prevent="chatDelete()" class="bg-white rounded-2xl w-full max-w-[500px] duration-500 origin-top px-10 py-16" :class="{ 'translate-y-0 opacity-100' : isChatDeleteModal, '-translate-y-1/2 opacity-0' : !isChatDeleteModal }" @click.stop>
            <div class="mb-3 flex justify-center">
                <svg viewBox="0 0 24 24" class="size-[85px]" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                    <g id="SVGRepo_iconCarrier">
                        <path class="stroke-rose-600" d="M20.5001 6H3.5" stroke-width="1.5" stroke-linecap="round"></path>
                        <path class="stroke-rose-600" d="M6.5 6C6.55588 6 6.58382 6 6.60915 5.99936C7.43259 5.97849 8.15902 5.45491 8.43922 4.68032C8.44784 4.65649 8.45667 4.62999 8.47434 4.57697L8.57143 4.28571C8.65431 4.03708 8.69575 3.91276 8.75071 3.8072C8.97001 3.38607 9.37574 3.09364 9.84461 3.01877C9.96213 3 10.0932 3 10.3553 3H13.6447C13.9068 3 14.0379 3 14.1554 3.01877C14.6243 3.09364 15.03 3.38607 15.2493 3.8072C15.3043 3.91276 15.3457 4.03708 15.4286 4.28571L15.5257 4.57697C15.5433 4.62992 15.5522 4.65651 15.5608 4.68032C15.841 5.45491 16.5674 5.97849 17.3909 5.99936C17.4162 6 17.4441 6 17.5 6" stroke-width="1.5"></path>
                        <path class="stroke-rose-600" d="M18.3735 15.3991C18.1965 18.054 18.108 19.3815 17.243 20.1907C16.378 21 15.0476 21 12.3868 21H11.6134C8.9526 21 7.6222 21 6.75719 20.1907C5.89218 19.3815 5.80368 18.054 5.62669 15.3991L5.16675 8.5M18.8334 8.5L18.6334 11.5" stroke-width="1.5" stroke-linecap="round"></path>
                    </g>
                </svg>
            </div>
            <div class="mb-5 text-center text-[21px] font-medium"> Are you sure? </div>
            <div class="flex justify-center items-center gap-3">
                <button type="button" class="outline-0 border-0 bg-gray-200 duration-500 hover:bg-gray-700 text-black hover:text-white inline-flex justify-center items-center min-w-[120px] min-h-[48px] max-h-[48px] rounded-xl" @click="closeChatDeleteModal()">
                    Cancel
                </button>
                <button type="submit" class="outline-0 border-0 bg-rose-200 duration-500 hover:bg-rose-600 text-rose-900 hover:text-white inline-flex justify-center items-center min-w-[120px] min-h-[48px] max-h-[48px] rounded-xl" v-if="!deleteLoading">
                    Confirm
                </button>
                <button type="button" class="outline-0 border-0 bg-rose-200 duration-500 hover:bg-rose-600 text-rose-900 hover:text-white inline-flex justify-center items-center min-w-[120px] min-h-[48px] max-h-[48px] rounded-xl group" v-if="deleteLoading">
                    <span class="inline-block size-[20px] rounded-full border-2 border-transparent border-t-2 border-t-rose-600 group-hover:border-t-white animate-spin"></span>
                </button>
            </div>
        </form>
    </div>

</template>

<script>

import axios from "axios";
import apiRoute from "../../api/apiRoute.js";
import apiService from "../../api/apiService.js";
import Pusher from 'pusher-js';

export default {
    computed: {

        /*** filter user list ***/
        filteredUserList() {
            return this.userData.filter((list) => {
                return !this.keyword || list.name.toLowerCase().includes(this.keyword.toLowerCase()) || list.email.toLowerCase().includes(this.keyword.toLowerCase());
            });
        },

        /*** show filter user list as asc or desc ***/
        showList() {
            return [...this.filteredUserList].sort((a, b) => {
                return b.id - a.id;
            });
        },

    },
    data() {
        return {
            /*** Data properties ***/
            isUserDropdown: false,
            isOtherUserDropdown: false,
            isLeftChatDropdown: false,
            isRightChatDropdown: false,
            isEditProfileModal: false,
            isChangePasswordModal: false,
            isChatDeleteModal: false,
            logoutLoading: false,
            loading: false,
            userLoading: false,
            editProfileLoading: false,
            changePasswordLoading: false,
            deleteLoading: false,
            chatClearLoading: false,
            id: null,
            keyword: '',
            profileParam: { name: '', email: '', phone: '' },
            passwordParam: { current_password: '', new_password: '', new_password_confirmation: '' },
            error: {},
            formData: { sender_id: '', receiver_id: '', message: '' },
            userData: [],
            chats: [],
            selectedUser: {},
            selectedUserInitials: '',
            profileData: null,
            pusher: null,
            channel: null,
            isTyping: false,
        }
    },
    watch: {
        'chats.length'(newVal, oldVal) {
            this.$nextTick(() => this.scrollToBottom());
        }
    },
    async mounted() {
        /*** Mounted properties ***/
        await this.getUserDetails();
        await this.userList();
        if (!this.formData.receiver_id && this.userData.length > 0) {
            await this.selectUser(this.userData[0]);
            await this.chatList();
        }
        window.addEventListener("click", this.handleUserDropdownClose);
        window.addEventListener("click", this.handleOtherUserDropdownClose);
        window.addEventListener("click", this.handleLeftChatDropdownClose);
        window.addEventListener("click", this.handleRightChatDropdownClose);
    },
    async beforeUnmount() {
        /*** Before Unmounted properties ***/
        this.unsubscribeChannel();
        window.removeEventListener("click", this.handleUserDropdownClose);
        window.removeEventListener("click", this.handleOtherUserDropdownClose);
        window.removeEventListener("click", this.handleLeftChatDropdownClose);
        window.removeEventListener("click", this.handleRightChatDropdownClose);
    },
    methods: {

        /*** Open user dropdown ***/
        openUserDropdown() {
            this.isUserDropdown = true;
        },

        /*** Close user dropdown ***/
        closeUserDropdown() {
            this.isUserDropdown = false;
        },

        /*** Handle user dropdown close ***/
        handleUserDropdownClose() {
            if (!event.target.closest("#userDropdown")) {
                this.isUserDropdown = false;
            }
        },

        /*** Open other user dropdown ***/
        openOtherUserDropdown() {
            this.isOtherUserDropdown = true;
        },

        /*** Close other user dropdown ***/
        closeOtherUserDropdown() {
            this.isOtherUserDropdown = false;
        },

        /*** Handle other user dropdown close ***/
        handleOtherUserDropdownClose() {
            if (!event.target.closest("#otherUserDropdown")) {
                this.isOtherUserDropdown = false;
            }
        },

        /*** Handle left chat dropdown close ***/
        handleLeftChatDropdownClose() {
            if (!event.target.closest("#leftChatDropdown")) {
                this.isLeftChatDropdown = false;
            }
        },

        /*** Handle right chat dropdown close ***/
        handleRightChatDropdownClose() {
            if (!event.target.closest("#rightChatDropdown")) {
                this.isRightChatDropdown = false;
            }
        },

        /*** Open edit profile modal ***/
        openEditProfileModal() {
            this.error = {};
            this.isEditProfileModal = true;
        },

        /*** Close edit profile modal ***/
        closeEditProfileModal() {
            this.isEditProfileModal = false;
        },

        /*** Open change password modal ***/
        openChangePasswordModal() {
            this.error = {};
            this.isChangePasswordModal = true;
        },

        /*** Close change password modal ***/
        closeChangePasswordModal() {
            this.isChangePasswordModal = false;
        },

        /*** Close change password modal ***/
        closeChatDeleteModal() {
            this.isChatDeleteModal = false;
        },

        /*** Short name ***/
        shortName(fullName) {
            if (!fullName) return "";
            const words = fullName.trim().split(" ");
            return words.slice(0, 2).map(word => word[0]).join("").toUpperCase();
        },

        /*** Details api implementation ***/
        async getUserDetails() {
            this.loading = true;
            try {
                const response = await axios.post(apiRoute.details, null, {headers: apiService.authHeaderContent()});
                this.profileData = response.data.user;
                this.profileParam = JSON.parse(JSON.stringify(response.data.user));
                this.formData.sender_id = response.data.user.id;
                await this.subscribeToPrivateChannel();
            } catch (error) {
                if (error.chat === 'Unauthorized') {
                    localStorage.removeItem("pusherTransportTLS");
                    localStorage.removeItem("token");
                    localStorage.removeItem("user");
                } else if (error.response.data.errors) {
                    this.error = error.response.data.errors;
                }
            } finally {
                this.loading = false;
            }
        },

        /*** subscribe to private channel ***/
        async subscribeToPrivateChannel() {
            if (!this.profileData?.id) return;
            this.pusher = new Pusher(import.meta.env.VITE_PUSHER_APP_KEY, {
                cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
                forceTLS: true,
                authEndpoint: '/broadcasting/auth',
                auth: {
                    headers: {
                        Authorization: `Bearer ${localStorage.getItem('token')}`,
                    },
                },
            });
            this.channel = this.pusher.subscribe(`chat-application`);
            this.channel.bind('chat-event', (data) => {
                if (data.chat && data.chat.message === '__typing__') {
                    if (data.chat.sender_id === this.formData.receiver_id) {
                        this.isTyping = true;
                        clearTimeout(this.typingTimeout);
                        this.typingTimeout = setTimeout(() => {
                            this.isTyping = false;
                        }, 1500);
                    }
                } else {
                    this.chats.push(data.chat);
                    this.$nextTick(() => this.scrollToBottom());
                }
            });

        },

        unsubscribeChannel() {
            if (this.pusher && this.channel) {
                this.pusher.unsubscribe(`private-chats.${this.profileData.id}`);
                this.channel = null;
            }
        },

        /*** Change details api implementation ***/
        async editProfile() {
            this.editProfileLoading = true;
            try {
                const response = await axios.post(apiRoute.changeDetails, this.profileParam, {headers: apiService.authHeaderContent()});
                if (response) {
                    await this.getUserDetails();
                    this.closeEditProfileModal();
                }
            } catch (error) {
                this.error = error.response.data.errors;
            } finally {
                this.editProfileLoading = false;
            }
        },

        /*** Change password api implementation ***/
        async changePassword() {
            this.changePasswordLoading = true;
            try {
                const response = await axios.post(apiRoute.changePassword, this.passwordParam, {headers: apiService.authHeaderContent()});
                if (response) {
                    await this.getUserDetails();
                    this.closeChangePasswordModal();
                }
            } catch (error) {
                this.error = error.response.data.errors;
            } finally {
                this.changePasswordLoading = false;
            }
        },

        /*** logout api implementation ***/
        async logout() {
            this.logoutLoading = true;
            try {
                const response = await axios.post(apiRoute.logout, null, {headers: apiService.authHeaderContent()});
                if (response) {
                    localStorage.removeItem('token');
                    localStorage.removeItem('user');
                    this.$router.push({name: 'login'});
                }
            } catch (error) {
                console.log(error.response.data);
            } finally {
                this.logoutLoading = false;
            }
        },

        /*** Chat list api implementation ***/
        async chatList() {
            if (!this.formData.receiver_id) return;
            this.loading = true;
            try {
                const response = await axios.get(`${apiRoute.chat}/list`, {
                    headers: apiService.authHeaderContent(),
                    params: {chat: this.formData.receiver_id}
                });
                this.chats = response.data.chats;
            } catch (error) {
                console.log(error.response?.data?.errors);
            } finally {
                this.loading = false;
            }
        },

        /*** manage chat api ***/
        manageChat() {
            if (this.formData.id) {
                this.chatUpdate();
            } else {
                this.chatStore();
            }
        },

        /*** Chat store api implementation ***/
        async chatStore() {
            this.loading = true;
            try {
                await axios.post(`${apiRoute.chat}/store`, this.formData, {headers: apiService.authHeaderContent()});
                this.formData.message = '';
                await this.chatList();
                await this.$nextTick(() => { this.scrollToBottom(); });
            } catch (error) {
                console.log(error.response.data.errors);
            } finally {
                this.loading = false;
            }
        },

        /*** Chat update api implementation ***/
        async chatUpdate() {
            this.loading = true;
            try {
                const response = await axios.patch(`${apiRoute.chat}/update/${this.id}`, this.formData, {headers: apiService.authHeaderContent()});
                const index = this.chats.findIndex(chat => chat.id === response.data.chat.id);
                this.chats.splice(index, 1, response.data.chat);
                await this.chatList();
            } catch (error) {
                console.log(error.response.data.errors);
            } finally {
                this.loading = false;
            }
        },

        /*** Chat delete api implementation ***/
        async chatDelete() {
            this.deleteLoading = true;
            try {
                const response = await axios.delete(`${apiRoute.chat}/delete/${this.id}`, {headers: apiService.authHeaderContent()});
                if (response) {
                    this.chats = this.chats.filter(chat => chat.id !== this.id);
                    this.closeChatDeleteModal();
                }
            } catch (error) {
                console.log(error.response.data.errors);
            } finally {
                this.deleteLoading = false;
            }
        },

        /*** Chat clear api implementation ***/
        async chatClear() {
            this.chatClearLoading = true;
            try {
                const response = await axios.delete(`${apiRoute.chat}/clear`, {headers: apiService.authHeaderContent()});
                if (response) {
                    this.chats = [];
                    this.closeOtherUserDropdown();
                }
            } catch (error) {
                console.log(error.response.data.errors);
            } finally {
                this.chatClearLoading = false;
            }
        },

        /*** User list api implementation ***/
        async userList() {
            this.userLoading = true;
            try {
                const response = await axios.get(`${apiRoute.users}/other-users`, {headers: apiService.authHeaderContent()});
                const currentUser = JSON.parse(localStorage.getItem('user'));
                this.userData = response.data.users.filter(user => user.id !== currentUser.id);
            } catch (error) {
                console.log(error.response.data.errors);
            } finally {
                this.userLoading = false;
            }
        },

        /*** Select user initialization ***/
        async selectUser(user) {
            this.selectedUser = user;
            this.selectedUserInitials = this.shortName(user.name);
            this.formData.receiver_id = user.id;
            await this.$nextTick(() => this.scrollToBottom());
        },

        /*** Format date time ***/
        formatTime(Time) {
            const options = {hour: '2-digit', minute: '2-digit', hour12: true};
            return new Date(Time).toLocaleString('en-GB', options);
        },

        /*** Search data ***/
        searchData() {
            clearTimeout(this.searchTimeout);
            this.searchTimeout = setTimeout(() => {
                this.userList();
            }, 500);
        },

        /*** get initials ***/
        getInitials(userId) {
            if (userId === this.profileData.id) return this.shortName(this.profileData.name);
            return this.selectedUserInitials;
        },

        /*** After store chat scroll down ***/
        scrollToBottom() {
            const el = this.$refs.scrollAnchor;
            if (el) {
                el.scrollIntoView({ behavior: 'smooth' });
            }
        },

        sendTyping() {
            if (this.isTyping) return;
            this.isTyping = true;
            const fakeTypingData = {
                sender_id: this.formData.sender_id,
                receiver_id: this.formData.receiver_id,
                message: '__typing__',
            };
            axios.post(`${apiRoute.chat}/store`, fakeTypingData, {
                headers: apiService.authHeaderContent(),
            }).catch(() => {}).finally(() => {
                setTimeout(() => {
                    this.isTyping = false;
                }, 1500);
            });
        }

    }
}
</script>
