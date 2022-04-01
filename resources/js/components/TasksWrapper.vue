<template>
    <form v-on:submit="addTask">
        <div id="task-form-wrapper">
            <input v-model="inputName" id="name-input" type="text" name="name" placeholder="Task name">
            <input v-model="inputPriority" id="priority-input" type="number" name="priority" placeholder="Priority">
            <button id="add-task" type="submit">Add Task</button>
        </div>
    </form>
  <div id="tasks-wrapper">
    <Task v-for="task in tasks" :key="task.id" :task="task"/>
  </div>
</template>

<script>
import Task from './Task.vue'

export default {
    components:{
        Task,
    },
    data(){
        return {
            tasks : [],
            inputName : null,
            inputPriority : null,
        }
    },
    methods:{
        getTasks(){
            axios.get("/api/task").then((response) => {
                console.log('retrieved tasks')
                this.tasks = response.data
            })
        },
        addTask(e){
            axios({
            method: "post",
                url: "/api/task",
                data: {
                    name : this.inputName,
                    priority : this.inputPriority ?? null,
                },
            })
            .then((response) => {
                //handle success
                console.log('success');
                return this.getTasks()
            })
            .catch(() => {
                console.log('err')
            })


                this.inputName = ""
                this.inputPriority = ""

            // To prevent the form from submitting
            e.preventDefault();
        }
    },
    mounted(){
        this.getTasks()
    }

}
</script>

<style>
#task-form-wrapper{
    display: flex;
    justify-content: space-between;
}
#task-form-wrapper input{
    width: 40%;
    padding: 0.5rem;
    border-radius: 5px;
}
#task-form-wrapper #add-task{
    background: rgba(0, 191, 255, 0);
    padding: 0.5rem;
    color: rgb(57, 57, 57);
    border: 1px solid deepskyblue;
    border-radius: 5px;
    cursor: pointer;
}
#task-form-wrapper #add-task:hover{
    background: deepskyblue;
    color: rgb(57, 57, 57);
    transition: 0.5s background;
}

#tasks-wrapper{
    margin-top: 0.5rem;
    padding-right: 1rem;
    max-height: 50rem;
    overflow-y: scroll;
    scroll-behavior: smooth;
}
</style>
