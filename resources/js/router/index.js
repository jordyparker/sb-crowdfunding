import Vue from 'vue'
import VueRouter from 'vue-router'

import Register from './../Pages/Register.vue'
import Login from './../Pages/Login.vue'
import Index from './../Pages/Index.vue'
import CreateDonationCampaign from '../Pages/Campaigns/Create.vue'
import DonationCampaignIndex from '../Pages/Campaigns/Index.vue'
import DonationCampaignCategory from '../Pages/Categories/Campaigns.vue'

Vue.use(VueRouter)

const routes = [
  { path: '/', component: Index },
  { path: '/register', component: Register },
  { path: '/login', component: Login },
  { path: '/donation-campaigns', component: DonationCampaignIndex },
  { path: '/donation-campaigns/create', component: CreateDonationCampaign },
  { path: '/donation-campaigns/:category', component: DonationCampaignCategory },
]

export default new VueRouter({
    routes
})
