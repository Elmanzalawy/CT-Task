<template>

    <form v-on:submit="changeProject">
        <div id="project-form-wrapper">
            <label id="project-select" for="project-select">Select Project: </label>
            <select v-if="this.currentProjectId" id="project-select" type="text" name="project_id" placeholder="Project ID" @change="this.changeProject($event)">
                <option v-for="project in projects" :key="project.id" :value="project.id" :selected="project.selected"> {{project.name}} </option>
            </select>
            <label v-if="this.currentProjectId" for="add-project-input">Or</label>
            <input v-model="projectNameInput" id="add-project-input" type="text" name="projectNameInput" placeholder="Add Project">
            <button id="add-project" type="submit">Add Project</button>
        </div>
    </form>

    <form v-if="this.currentProjectId" v-on:submit="addTask">
        <div id="task-form-wrapper">
            <input v-model="taskNameInput" id="name-input" type="text" name="name" placeholder="Task name">
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
            projects: [],
            currentProjectId : null,
            tasks : [],
            priority: 0,
            projectNameInput : null,
            taskNameInput : null,
            inputPriority : null,
            dragStartIndex : null,
        }
    },
    methods:{
        getProjects(){
            axios.get("/api/project").then((response) => {
                // console.log('retrieved projects')
                this.projects = response.data

                //get currently selected project
                this.projects.forEach(project => {
                    if(project.selected == 1){
                        this.currentProjectId = project.id
                        return
                    }
                });

                this.getTasks()
            })
        },
        getTasks(){

            axios.get(`/api/task?project_id=${this.currentProjectId}`).then((response) => {
                // console.log('retrieved tasks')
                this.tasks = response.data
                setTimeout(() => {
                    this.addDraggableEvents()
                }, 500);
            })
        },
        changeProject(e){
            // console.log('changing project')
            if(this.projectNameInput){
                axios({
                    method: "post",
                    url: "/api/project",
                    data: {
                        name : this.projectNameInput,
                    },
                })
                .then((response) => {
                    this.projectNameInput = ""
                    this.getProjects()
                })
                .catch(() => {
                    console.log('an error has occured.')
                })
            }
            else{
                // console.log('updating..')
                axios({
                    method: "put",
                    url: `/api/project/${e.target.value}`,
                    data: {
                        selected : 1,
                    },
                })
                .then((response) => {
                    this.projectNameInput = ""
                    this.getProjects()
                })
                .catch(() => {
                    console.log('an error has occured.')
                })

            }
            // To prevent the form from submitting
            e.preventDefault();
        },
        addTask(e){
            axios({
            method: "post",
                url: "/api/task",
                data: {
                    name : this.taskNameInput,
                    priority : this.inputPriority ?? null,
                    project_id: this.currentProjectId,
                },
            })
            .then((response) => {
                this.taskNameInput = ""
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

            // console.log(task1, task2)

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
        this.getProjects()
        this.getTasks()
    }

}
</script>

<style scoped>
#task-form-wrapper{
    display: flex;
    justify-content: space-between;
}
label{
    color: #252525;
}
input, select, button{
    padding: 0.5rem;
    margin: 0.5rem;
    border-radius: 5px;
}
#add-project{
    background: none;
    border: 1px solid rgb(0, 103, 3);
    cursor: pointer;

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
#task-form-wrapper input{
    width: 40%;
}

#tasks-wrapper{
    /* margin-top: 0.5rem; */
    padding-right: 1rem;
    max-height: 50rem;
    overflow-y: scroll;
    scroll-behavior: smooth;
}

.highlight{
  background: #c7c7c753 !important;
}
</style>
