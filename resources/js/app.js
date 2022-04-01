require('./bootstrap');

import { createApp } from 'vue';
import TasksWrapper from "./components/TasksWrapper.vue"

createApp({
    components:{
        TasksWrapper,
    }
}).mount("#app")
