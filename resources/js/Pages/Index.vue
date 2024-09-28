<template>
    <main class="mx-auto pb-6 w-full md:max-w-5xl xl:max-w-6xl px-4">
        <div class="fex-1 w-full">
            <div class="lg:grid lg:grid-cols-12 lg:gap-10">
                <div class="hidden lg:block lg:col-span-4 transition-all duration-700 ease-in-out">
                    <aside class="sticky top-10 divide-y z-50">
                        <category-list />
                    </aside>
                </div>
                <div class="lg:col-span-8 bg-white p-5 z-0 rounded-lg shadow-sm">
                    <div class="flex flex-col gap-6">
                        <donation-campaign-list />
                    </div>
                </div>
            </div>
        </div>
    </main>
</template>

<script>
import CategoryList from '../Components/CategoryList.vue';
import DonationCampaignList from '../Components/DonationCampaignList.vue';
import MainLayout from '../Layouts/MainLayout.vue';

export default {
    name: 'Index',
    layout: MainLayout,
    components: {
        CategoryList,
        DonationCampaignList,
    }
}
</script>
