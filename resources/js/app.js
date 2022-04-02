require('./bootstrap');

import { createApp } from 'vue';
import TasksWrapper from "./components/TasksWrapper.vue"
import Foot from "./components/layouts/Footer.vue"

createApp({
    components:{
        TasksWrapper,
        Foot
    }
}).mount("#app")
