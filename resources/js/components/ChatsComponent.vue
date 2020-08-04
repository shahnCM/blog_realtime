<template>
    <div class="containerX">
        <div class="row justify-content-center">
            <div class="heading-text pb-2">
                <h2 class="text-secondary">Lara Chat</h2>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-9 p-1">
                <div class="card">
                    <div class="card-header bg-primary text-light">
                        <h4>Messages</h4>
                    </div>

                    <div class="card-body">
                        <ul class="list-unstyled" style="min-height:350px; max-height: 100%; overflow-y:scroll">
                            <li class="p-2" v-for="(message,index) in messages" :key="index">
                                {{message.message}}
                                <br> 
                                <span class="text-right">
                                    <small><strong>{{message.user.name}}</strong></small>
                                </span> 
                            </li>
                        </ul>
                    </div>
                    
                    <div class="row p-2">
                        <div class="col-12">
                            <input 
                                @keyup.enter="sendMessage"
                                v-model="newMessage"
                                type="text" name="message" 
                                class="form-control"
                                placeholder="Enter text here . . . "  
                                style="border: 2px lightgrey solid;"
                            >        
                        </div>
                        <!-- <div class="col-3 pl-0 justify-content-center">
                            <button 
                                class="btn btn-warning text-dark"
                            >
                                <strong>Send</strong>
                            </button>
                        </div> -->
                        
                    </div>
                    
                </div>

                <small>User is typing . . . </small>

            </div>

            <div class="col-3 p-1">
                <div class="card">
                    <div class="card-header bg-success text-light">
                        <h4>Online</h4>
                    </div>

                    <div class="card-body">
                        <ul 
                            class="list-unstyled" 
                            style="
                                max-height: 100%; 
                                overflow-y:scroll
                        ">

                            <li class="py-2">Green</li>
                            <li class="py-2">Red</li>
                        
                        
                        </ul>
                    </div>
                </div>
            </div>            
        </div>
    </div>
</template>

<script>
    export default {
        props:['user'],

        data() {
            return {
                messages: [],
                newMessage: ''
            }
        },

        created() {
            this.fetchMessages();

            Echo.channel('chat')
                .listen('MessageSent', (event) => {
                    this.messages.push(event.message);
                    console.log("channel broadcasting")
                })
        },

        methods: {
            fetchMessages() {
                axios
                    .get('messages')
                    .then(response => {
                        this.message = response.data;
                    })
            },

            sendMessage() {
                this.messages.push({
                    user: this.user,
                    message: this.newMessage
                });

                axios
                    .post('messages', {
                        message: this.newMessage
                    });
                
                this.newMessage = '';

            }
        }
    }
</script>
