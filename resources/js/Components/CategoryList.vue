<template>
    <div class="bg-white rounded-lg shadow-sm">
        <div class="text-lg font-medium leading-snug bg-green-600 text-white p-3 rounded-t-lg text-center">Filter by categories</div>
        <div v-if="!loading" class="flex flex-col">
            <RouterLink :to="'donation-campaigns/' + category.id" v-for="(category, index) in categories" :key="'catgeory-idx'+index" class="flex items-center gap-x-4 p-2 m-2 hover:rounded hover:bg-gray-100" role="button">
                <img :src="category.image" class="h-14 w-14 bg-gray-200 shrink-0 rounded-full" />
                <div class="text-sm font-bold text-green-900">
                    {{ category.name }}
                </div>
            </RouterLink>
        </div>
        <div v-else class="mt-2 flex flex-col space-y-4 mx-3 py-3">
            <div v-for="idx in 5" :key="'top-category-idx-' + idx" class="animate-pulse w-full">
                <div class="flex items-center space-x-4">
                    <div class="rounded-full bg-slate-300 h-14 w-14 shrink-0"></div>
                    <div class="flex flex-col gap-4 w-full">
                        <div class="h-2 bg-slate-300 rounded"></div>
                        <div class="h-2 bg-slate-300 rounded"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {mapGetters} from "vuex"
export default {
    name: "CategoryList",
    computed: {
        ...mapGetters({
            loading: "isLoading",
            categories: "getCategories"
        }),
    },
    created(){
        this.$store.dispatch('fetchCategories');
    }
}
</script>
