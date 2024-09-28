<template>
    <div class="flex items-center justify-center p-6">
        <div class="w-full max-w-md">
        <form class="mt-8 bg-white rounded-lg shadow-xl overflow-hidden" @submit.prevent="login">
            <div class="px-10 py-12">
            <h1 class="text-center text-3xl font-bold">Welcome Back!</h1>
            <div class="mt-6 mx-auto w-24 border-b-2" />
            <text-input v-model="form.email" class="mt-10" label="Email" type="email" autofocus autocapitalize="off" />
            <text-input v-model="form.password" class="mt-6" label="Password" type="password" />
            <label class="flex items-center mt-6 select-none" for="remember">
                <input id="remember" v-model="form.remember" class="mr-1" type="checkbox" />
                <span class="text-sm">Remember Me</span>
            </label>
            </div>
            <div class="flex px-10 py-4 bg-gray-100 border-t border-gray-100">
            <loading-button class="btn bg-green-600  hover:bg-green-800 focus:bg-green-800 ml-auto" type="submit">Login</loading-button>
            </div>
        </form>
        </div>
    </div>
</template>

<script>
import LoadingButton from '../Components/Shared/LoadingButton.vue'
import TextInput from '../Components/Shared/TextInput.vue'
import MainLayout from '../Layouts/MainLayout.vue';

export default {
    name: 'Login',
    layout: MainLayout,
    components: {
        LoadingButton,
        TextInput
    },
    data() {
        return {
            form: {
                email: null,
                password: null,
                remember: false,
            },
        }
    },
    methods: {
        login() {

        },
    },
}
</script>
