import Vue from "vue";
import Vuex from "vuex";
import auth from "./auth";
import category from "./category";
import donation_campaign from "./donation_campaign";

Vue.use(Vuex);

export default new Vuex.Store({
    state: {
        //
    },
    getters: {
        //
    },
    modules: {
        auth: auth,
        category: category,
        donation_campaign: donation_campaign
    }
});
