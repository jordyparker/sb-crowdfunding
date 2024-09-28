import axios from "axios";

export default {
    state: {
        categories: [],
        loading: false
    },
    getters: {
        getCategories: (state) => {
            return state.categories;
        },
        isLoading: (state) => {
            return state.loading;
        }
    },
    mutations: {
        SET_CATEGORIES(state, categories) {
            state.categories = categories
        },
        SET_IS_LOADING(state, loading) {
            state.loading = loading
        }
    },
    actions: {
        async fetchCategories({ commit }) {
            commit('SET_IS_LOADING', true)
            axios.get(`${import.meta.env.VITE_API_URL}/v1/categories`, {
                headers: {
                    'x-api-key': import.meta.env.VITE_API_KEY
                }
            }).then((res) => {
                commit('SET_CATEGORIES', res.data.data)
                commit('SET_IS_LOADING', false)
            })
            .catch((err) => {
                commit('SET_IS_LOADING', false)
            })
        }
    },
}
