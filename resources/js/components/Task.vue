<template>
    <div class="task">
        <div class="priority">
            <span><b>#{{this.task.id}}</b></span>
        </div>
        <div class="task-body">
            <textarea class="name" v-model="task.name" v-on:keydown.enter="save($event)"></textarea>
            <br>
            <small>{{this.task.created_at}}</small>
        </div>
        <div class="controls">
            <button v-if="this.originalName != this.task.name" class="save" @click="save()">Save</button>
            <button class="delete" @click="destroy()">&#x2715;</button>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        task:Object,
    },
    data(){
        return{
            originalName : null,
            contentChanged : false,
        }
    },
    methods:{
        save(e){
            axios({
                method: "put",
                url: `/api/task/${this.task.id}`,
                data: {
                    name : this.task.name,
                    priority : this.task.priority,
                },
            })
            .then((response) => {
                console.log('task updated.');
                this.originalName = this.task.name
            })
            .catch(() => {
                console.log('err')
            })

            e.target.blur()
        },
        destroy(){
            axios.delete(`/api/task/${this.task.id}`).then(() => {
                console.log("Successfully deleted task.")
                this.$el.parentNode.removeChild(this.$el);
            })
        }
    },
    mounted(){
        this.originalName = this.task.name
    }

}
</script>

<style>
.task{
    position: relative;
    display: flex;
    justify-content: flex-start;
    margin: 0.5rem 0;
    background: radial-gradient( rgba(255, 0, 0, 0.114), rgba(0, 0, 255, 0.114));
    padding: 0.5rem;
    border: 1px solid rgb(138, 138, 138);
    border-radius: 1rem;
}
.task .priority{
    margin-top: 0.5rem;
    margin-right: 1rem;
}
.task .name{
    /* width: 90%; */
    margin: 0;
}
.task textarea{
    font-family: Verdana, Geneva, Tahoma, sans-serif;
    width:30vw;
    height: auto;
    background: none;
    border: none;
    resize: none;
}

.task small{
    color: rgb(78, 78, 78);
}

.task .save{
    position: absolute;
    padding: 0.5rem;
    background: deepskyblue;
    color: rgb(255, 255, 255);
    font-weight: bold;
    border: 1px solid deepskyblue;
    border-radius: 5px;
    right: 5rem;
    top: 50%;
    transform: translateY(-50%);
    cursor: pointer;
}
.task .delete{
    position: absolute;
    background: none;
    color: crimson;
    font-weight: bold;
    border: 1px solid crimson;
    border-radius: 5px;
    right: 1rem;
    top: 50%;
    transform: translateY(-50%);
    cursor: pointer;
}
.task .delete:hover{
    background: crimson;
    color: white;
    transition: 0.5s background;
}

</style>
