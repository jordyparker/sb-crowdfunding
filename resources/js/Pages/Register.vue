<template>
    <div class="flex items-center justify-center p-6">
        <div class="w-full max-w-md">
        <form class="mt-8 bg-white rounded-lg shadow-xl overflow-hidden" @submit.prevent="register">
            <div class="px-10 py-12">
                <h1 class="text-center text-3xl font-bold">Create your account!</h1>
                <div class="mt-6 mx-auto w-24 border-b-2" />
                <text-input v-model="form.first_name" class="mt-10" label="First Name" type="text" autofocus autocapitalize="off" />
                <text-input v-model="form.last_name" class="mt-4" label="Last Name" type="text" autofocus autocapitalize="off" />
                <text-input v-model="form.email" class="mt-4" label="Email" type="email" autofocus autocapitalize="off" />
                <text-input v-model="form.password" class="mt-4" label="Password" type="password" />
            </div>
            <div class="flex px-10 py-4 bg-gray-100 border-t border-gray-100">
                <loading-button class="btn bg-green-600  hover:bg-green-800 focus:bg-green-800 ml-auto" type="submit">Register</loading-button>
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
    name: 'Register',
    layout: MainLayout,
    components: {
        LoadingButton,
        TextInput
    },
    data() {
        return {
            form: {
                first_name: null,
                last_name: null,
                email: null,
                password: null
            },
        }
    },
    methods: {
        register() {

        },
    },
}
</script>
