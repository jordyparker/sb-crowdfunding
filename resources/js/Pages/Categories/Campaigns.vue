<template>
    <main class="mx-auto pb-6 w-full md:max-w-4xl px-4">
        <div class="fex-1 w-full">
            <div class="bg-white p-5 z-0 rounded-lg shadow-sm">
                <div class="flex flex-col gap-6">
                    <donation-campaign-list :category="$route.params.category" />
                </div>
            </div>
        </div>
    </main>
</template>

<script>
import DonationCampaignList from '../../Components/DonationCampaignList.vue';
import MainLayout from '../../Layouts/MainLayout.vue';

export default {
    name: 'CategoryDonationCampaigns',
    layout: MainLayout,
    components: {
        DonationCampaignList,
    },
    created() {
        this.$store.dispatch('clearPaginationState');
    }
}
</script>
