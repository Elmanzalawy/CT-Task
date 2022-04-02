<template>
    <form v-on:submit="addTask">
        <div id="task-form-wrapper">
            <input v-model="inputName" id="name-input" type="text" name="name" placeholder="Task name">
            <input v-model="inputPriority" id="priority-input" type="number" name="priority" placeholder="Priority">
            <button id="add-task" type="submit">Add Task</button>
        </div>
    </form>
  <div id="tasks-wrapper">
    <Task v-for="(task, index) in tasks" :key="task.id" :task="task" :priority="index" draggable="true"/>
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
            priority: 0,
            inputName : null,
            inputPriority : null,
            dragStartIndex : null,
        }
    },
    methods:{
        getTasks(){
            axios.get("/api/task").then((response) => {
                console.log('retrieved tasks')
                this.tasks = response.data
                setTimeout(() => {
                    this.addDraggableEvents()
                }, 500);
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
                this.inputName = ""
                this.inputPriority = ""
                return this.getTasks()
            })
            .catch(() => {
                console.log('an error has occured.')
            })

            // To prevent the form from submitting
            e.preventDefault();
        },
        addDraggableEvents(){
            const taskElements = document.getElementsByClassName('task');

            for(let i=0; i<taskElements.length; i++){
                taskElements[i].addEventListener('dragstart', this.dragStart);
                taskElements[i].addEventListener('dragover', this.dragOver);
                taskElements[i].addEventListener('drop', this.drop);
                taskElements[i].addEventListener('dragenter', this.dragEnter);
                taskElements[i].addEventListener('dragleave', this.dragLeave);
            }
        },
        dragStart(e) {
            this.dragStartIndex = e.target.getAttribute('priority');
        },
        dragOver(e) {
            e.preventDefault();
        },
        swapItems(from, to) {
            const task1 = this.tasks[from]
            const task2 = this.tasks[to]

            console.log(task1, task2)

            axios({
            method: "post",
                url: "/api/task/swap-tasks",
                data: {
                    task1 : task1.id,
                    task2 : task2.id,
                },
            })
            .then((response) => {

            })
            .catch(() => {
                console.log('an error has occured.')
            })

            this.tasks[from] = task2
            this.tasks[to] = task1

        },
        dragEnter(e) {
            // console.log(e.target)
            let el = e.target
            if (el.classList.contains('task')){
                el.classList.add('highlight')
            }else{
                el.closest(".task").classList.add("highlight")
            }
        },
        dragLeave(e) {
            let el = e.target
            if (el.classList.contains('task')){
                el.classList.remove('highlight')
            }else{
                el.closest(".task").classList.remove("highlight")
            }
        },
        drop(e) {
            let  el = e.target

            if (!el.classList.contains('task')){
                el = e.target.closest(".task")
            }

            const dragEndIndex = el.getAttribute('priority');
            this.swapItems(this.dragStartIndex, dragEndIndex);

            this.dragStartIndex = null;
            e.target.classList.remove('highlight');
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

.highlight{
  background: #c7c7c753 !important;
}
</style>
