<template>
    <div :id="id" class="modal z-9999 modal-static" role="dialog" tabindex="-1">
        <div class="modal__content modal__content relative modal__content--lg">
            <div class="border-b px-4 py-3 flex justify-between items-center w-full">
                <div></div>
                <div v-if="campaign" class="text-lg font-semibold">Contribute to {{ campaign.name }}</div>
                <button role="button" type="button" @click="close()" class="rounded-full bg-gray-100 p-2.5 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form @submit.prevent="donate" id="donate-form" class="flex flex-col p-6">
                <select-input v-model="form.payment_method" required class="mt-6" label="Payment Method" type="text">
                    <option>Choose the payment method</option>
                    <option value="orange-money">Orange Money</option>
                    <option value="mtn-mobile-money">Mtn Mobile Money</option>
                </select-input>
                <text-input v-model="form.amount" required :min="campaign?.min_donation_amount" class="mt-6" label="Amount" type="number" autofocus autocapitalize="off" />
                <text-input v-model="form.payment_number" required class="mt-6" label="Payment Number" type="text" />
                <text-area v-model="form.comments" class="mt-6" label="Comments" />
                <label class="flex items-center mt-6 select-none" for="remember">
                    <input id="remember" checked disabled class="mr-1" type="checkbox" />
                    <span class="text-sm">Anonymously</span>
                </label>
            </form>
            <div class="border-t">
                <div class="py-3 max-w-80 mx-auto">
                    <loading-button :loading="loading" form="donate-form" role="button" type="submit" class="px-2 py-1.5 border rounded w-full bg-green-600 hover:bg-green-700 text-white font-medium text-lg capitalize disabled:bg-gray-300 flex justify-center">
                        contribute
                    </loading-button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import cash from 'cash-dom';
import TextInput from '../Shared/TextInput.vue';
import TextArea from '../Shared/TextArea.vue';
import SelectInput from '../Shared/SelectInput.vue';
import axios from 'axios';
import LoadingButton from '../Shared/LoadingButton.vue';

export default {
  components: { TextInput, TextArea, SelectInput, LoadingButton },
    name: "DonateModal",
    props: {
        id: {
            type: String,
            default: () => 'donate-modal'
        }
    },
    data() {
        return {
            loading: false,
            campaign: null,
            form: {
                payment_number: null,
                amount: null,
                currency: 'XAF',
                payment_method: null,
                comments: null
            }
        }
    },
    methods: {
        open(campaign) {
            this.campaign = campaign
            this.form.amount = this.campaign.min_donation_amount
            this.form.currency = this.campaign.currency
            cash("#" + this.id).modal("show")
        },
        close() {
            if (this.loading) return;
            cash("#" + this.id).modal("hide")
            cash('body').removeClass('overflow-y-hidden')
            this.campaign = null
            this.form.payment_number = null
            this.form.payment_method = null
            this.form.comments = null
        },
        donate() {
            this.loading = true
            axios.post(`${import.meta.env.VITE_API_URL}/v1/donation-campaigns/${this.campaign.id}/donate`, this.form, {
                headers: {
                    'x-api-key': import.meta.env.VITE_API_KEY
                }
            }).then((res) => {
                this.loading = false
                this.$store.dispatch('updateCampaign', res.data.data)
                this.close()
            })
            .catch((err) => {
                console.log(err)
                this.loading = false
            })
        }
    }
}
</script>

<style scoped>

</style>
