<template>
    <div class="w-full h-full bg-gray-50">
        <Header />
        <section class="w-full min-h-[90vh] overflow-y-auto px-4 md:px-6 py-20">
            <router-view/>
        </section>
        <Footer />
    </div>
</template>

<script>
import {mapGetters} from "vuex"
import cash from "cash-dom"
import Footer from "../Components/Footer.vue"
import Header from "../Components/Header.vue"

export default {
    name: "MainLayout",
    components: {
        Footer,
        Header
    },
    computed: {
        ...mapGetters({auth: "getAuthUser"}),
    },
    created() {
        cash('body').removeClass('overflow-hidden')
    },
}
</script>
