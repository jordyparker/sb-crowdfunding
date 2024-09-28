<template>
    <div>
        <div class="border rounded-t-xl p-6">
            <div class="w-full flex justify-between items-center">
                <div class="flex gap-x-4">
                    <img class="h-14 w-14 border object-cover rounded-full" :src="campaign.creator.avatar" alt="creator">
                    <div>
                        <div class="text-lg font-medium">{{ campaign.creator.name }}</div>
                        <div class="text-sm font-normal italic text-gray-500">{{ campaign.created_at }}</div>
                    </div>
                </div>
                <div class="" v-if="campaign.can_receive_donations">
                    <button @click="$emit('donate')" class="rounded-lg px-4 py-2.5 bg-green-600 text-white font-medium">participate</button>
                </div>
            </div>
            <div class="pt-6 pb-2 flex justify-between">
                <div class="inline-flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z" />
                    </svg>
                    <span class="ml-2">{{ campaign.total_donations_amount }} {{ campaign.currency }} / {{ campaign.target_amount }} {{ campaign.currency }}</span>
                </div>
                <div class="inline-flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                    </svg>
                    <span class="ml-2" v-if="campaign.number_of_participants">{{ campaign.total_donations_count }} / {{ campaign.number_of_participants }} contributors</span>
                    <span class="ml-2" v-else>{{ campaign.total_donations_count }} contributors</span>
                </div>
                <div v-if="campaign.ends_at?.length" class="inline-flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>

                    <span class="ml-2">{{ campaign.ends_at }}</span>
                </div>
            </div>
            <div class="mt-4">
                <div class="text-[20px] mb-3 text-green-700 capitalize font-normal line-clamp-2">{{ campaign.name }}</div>
                <p class="text-[18px] font-normal leading-tight line-clamp-4 text-gray-800 p-0 m-0">
                    {{ campaign.description }}
                </p>
                <button class="px-2 py-1.5 bg-green-700 text-white font-normal mt-6">see all details >></button>
            </div>
        </div>
        <div class="w-full flex text-center font-bold relative">
            <div v-if="campaign.achieve_percentage > 0"
                :style="`width: ${campaign.achieve_percentage}%`"
                class="flex justify-center py-2 items-center bg-green-700 text-white">
                <span class="mx-2">{{ campaign.achieve_percentage }}%</span>
            </div>
            <div :style="campaign.achieve_percentage === 100 ? 'display: none;' : `width: ${100-campaign.achieve_percentage}%`" class="flex justify-center py-2 items-center bg-gray-300 text-sm">
                <span v-if="campaign.achieve_percentage > 0">
                    on an objective of {{ campaign.target_amount }} {{ campaign.currency }}
                </span>
                <span v-else>
                    0% on an objective of {{ campaign.target_amount }} {{ campaign.currency }}
                </span>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'DonationCampaignItem',
    props: {
        campaign: Object
    }
}
</script>
