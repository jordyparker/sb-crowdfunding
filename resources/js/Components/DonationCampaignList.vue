<template>
    <div v-if="!loading">
        <div v-if="category && campaigns.length && campaigns[0].category" class="mb-6 text-lg">
            <router-link to="/">Donations</router-link> > categories > <span class="text-green-700">{{ campaigns[0].category.name }}</span>
        </div>
        <div class="w-full mb-6">
            <div class="relative my-2 bg-white bg-shadow flex border-1 border-gray-00 rounded">
                <input placeholder="search donation campaigns here..." class="py-3 px-2 bg-white border-2 border-gray-200 focus:ring-1 focus:border appearance-none focus:ring-green-700 focus:outline-none focus:border-ring-green-700 rounded w-full text-gray-800">
            </div>
        </div>

        <div class="space-y-6">
            <donation-campaign-item
                v-for="(campaign, index) in campaigns" :key="'campaign-idx' + index"
                :campaign="campaign"
                @donate="openDonateModal(campaign)"
            />
        </div>
        <div v-if="page < lastPage" @click="loadNextPage()" class="mt-8 border text-base font-normal border-green-700 w-full py-1 rounded text-center" role="button">
            load more campaigns
        </div>
        <div v-if="page > 1 && loading" class="mt-8 border text-base font-normal border-green-700 w-full py-1 rounded text-center">
            loading page {{ page }}
        </div>
        <donate-modal ref="donateModalRef" />
    </div>
    <div v-else class="mt-2 flex flex-col space-y-4 mx-3 py-3">
        <div v-for="idx in 5" :key="'donation-campaigns-idx-' + idx" class="animate-pulse w-full">
            <div>
                <div class="border rounded-t-xl p-6">
                    <div class="w-full flex justify-between items-center">
                        <div class="flex items-center gap-x-4">
                            <div class="rounded-full bg-slate-300 h-14 w-14 shrink-0"></div>
                            <div class="w-56 space-y-2">
                                <div class="h-2 bg-slate-300 rounded w-full"></div>
                                <div class="h-2 bg-slate-300 rounded w-full"></div>
                            </div>
                        </div>
                        <div class="w-28">
                            <div class="h-10 bg-slate-300 rounded"></div>
                        </div>
                    </div>
                    <div class="mt-4 flex flex-col gap-2">
                        <div class="h-2 bg-slate-300 rounded"></div>
                        <div class="h-2 bg-slate-300 rounded"></div>
                        <div class="h-2 bg-slate-300 rounded"></div>

                        <div class="h-5 bg-slate-300 rounded w-48 mt-3"></div>
                    </div>
                </div>
                <div class="w-full flex text-center font-bold relative">
                    <div :class="{'w-[50%]': true}" class="flex justify-center py-4 items-center bg-slate-300 text-white">

                    </div>
                    <div :class="{'w-[50%]': true}" class="flex justify-center py-4 items-center bg-slate-300 text-sm">

                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {mapGetters} from "vuex"
import DonationCampaignItem from './DonationCampaignItem.vue'
import DonateModal from './Modals/DonateModal.vue';

export default {
  components: { DonationCampaignItem, DonateModal },
    name: "DonationCampaignList",
    props: {
        category: {
            type: String|Number,
            default: null
        }
    },
    computed: {
        ...mapGetters({
            campaigns: "getCampaigns",
            loading: "isLoading",
            page: "getCurrentPage",
            lastPage: "getLastPage"
        }),
    },
    async created() {
        this.$store.dispatch('getCampaigns', this.category);
    },
    methods: {
        loadNextPage() {
            if (this.page < this.lastPage) {
                this.$store.dispatch('loadNextPage', this.category);
            }
        },
        openDonateModal(campaign) {
            this.$refs.donateModalRef.open(campaign)
        }
    }
}
</script>
