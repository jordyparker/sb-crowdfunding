import axios from "axios";

export default {
    state: {
        campaigns: [],
        page: 1,
        lastPage: null,
        loading: false
    },
    getters: {
        getCampaigns: (state) => {
            return state.campaigns;
        },
        getCurrentPage: (state) => {
            return state.page;
        },
        getLastPage: (state) => {
            return state.lastPage;
        },
        issLoading: (state) => {
            return state.loading;
        }
    },
    mutations: {
        SET_CAMPAIGNS(state, campaigns) {
            if (state.campaigns?.length) {
                console.log('existing ...')
                state.campaigns.push(...campaigns)
            } else {
                state.campaigns = campaigns
            }
        },
        SET_PAGE(state, page) {
            state.page = page
        },
        SET_LAST_PAGE(state, page) {
            state.lastPage = page
        },
        SET_IS_LOADING(state, loading) {
            state.loading = loading
        }
    },
    actions: {
        async clearPaginationState({ commit }) {
            commit('SET_PAGE', 1)
            commit('SET_IS_LOADING', false)
            commit('SET_CAMPAIGNS', [])
            commit('SET_LAST_PAGE', null)
            console.log('state cleared')
        },
        loadNextPage({ commit, state, dispatch }, category) {
            commit('SET_PAGE', state.page + 1)
            dispatch('getCampaigns', category);
        },
        updateCampaign({ state }, donation) {
            let idx = state.campaigns.findIndex((el) => el.id === donation.campaign_id)
            if (idx !== -1) {
                let campaign = state.campaigns[idx]
                campaign.total_donations_count += 1
                campaign.total_donations_amount = parseFloat(campaign.total_donations_amount) + parseFloat(donation.amount)
                let percentage = Math.ceil(campaign.total_donations_amount * 100 / parseFloat(campaign.target_amount))
                campaign.achieve_percentage = percentage > 100 ? 100 : percentage
                state.campaigns[idx] = campaign
            }
        },
        getCampaigns({ commit, state }, category) {
            commit('SET_IS_LOADING', true)
            axios.get(`${import.meta.env.VITE_API_URL}/v1/donation-campaigns`, {
                params: {
                    page: state.page,
                    category: category
                },
                headers: {
                    'x-api-key': import.meta.env.VITE_API_KEY
                }
            }).then((res) => {
                commit('SET_CAMPAIGNS', res.data.data)
                commit('SET_LAST_PAGE', res.data.last_page)
                commit('SET_IS_LOADING', false)
            })
            .catch((err) => {
                commit('SET_IS_LOADING', false)
            })
        }
    },
}
