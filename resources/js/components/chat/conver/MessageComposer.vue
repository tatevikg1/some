<template>
    <div v-if="selectedContact">
        <div class="card-footer" ref="inputWrapper">

            <div class="input-group">
                <div class="input-group-append">
                    <span class="input-group-text attach_btn"></span>
                </div>
                <input v-model="inputValue"
                       @input="handleInput"
                       placeholder="Message"
                       @keydown.down="selectNextSuggestion"
                       @keydown.up="selectPreviousSuggestion"
                       @keydown.enter="applySelectedSuggestion"
                       class="form-control type_msg"/>
                <ul class="suggestions" v-if="showSuggestions">
                    <li v-for="(suggestion, index) in filteredSuggestions"
                        :key="suggestion"
                        :class="{ 'selected': index === selectedSuggestionIndex }"
                        @click="applySuggestion(suggestion)">
                        {{ suggestion }}
                    </li>
                </ul>
                <div class="input-group-append">
                    <span class="input-group-text send_btn">
                        <div @click="send">
                            <i class="fas fa-location-arrow"></i>
                        </div>
                    </span>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {mapGetters} from "vuex";

export default {
    data() {
        return {
            inputValue: '',
            suggestions: [],
            showSuggestions: false,
            selectedSuggestionIndex: -1,
            dictionary: ['/chatgpt'],
        };
    },
    computed: {
        ...mapGetters(['selectedContact']),

        filteredSuggestions() {
            return this.suggestions.filter(suggestion =>
                suggestion.toLowerCase().startsWith(this.inputValue.toLowerCase())
            );
        },
    },
    methods: {
        send(e) {
            e.preventDefault();
            if (this.inputValue === '') {
                return;
            }

            this.$emit('send', this.inputValue);
            this.inputValue = '';
        },
        handleInput() {
            this.showSuggestions = false;
            this.selectedSuggestionIndex = -1;

            if (this.inputValue.startsWith('/')) {
                this.suggestions = this.dictionary.filter((word) => word.startsWith(this.inputValue));
                this.showSuggestions = true;
            }
        },
        applySuggestion(suggestion) {
            this.inputValue = suggestion;
            this.showSuggestions = false;
            this.selectedSuggestionIndex = -1;
            this.$refs.inputWrapper.focus();
        },
        selectNextSuggestion() {
            if (this.selectedSuggestionIndex < this.suggestions.length - 1) {
                this.selectedSuggestionIndex++;
            }
        },
        selectPreviousSuggestion() {
            if (this.selectedSuggestionIndex > 0) {
                this.selectedSuggestionIndex--;
            }
        },
        applySelectedSuggestion() {
            const selectedSuggestion = this.suggestions[this.selectedSuggestionIndex];
            if (selectedSuggestion) {
                this.applySuggestion(selectedSuggestion);
            }
        },
    },
};
</script>

<style>
body,html{
    height: 100%;
    margin: 0;
}
.card-footer{
    border-radius: 0 0 15px 15px !important;
    border-top: 0 !important;
}
.type_msg{
    background-color: rgba(0,0,0,0.3) !important;
    border:0 !important;
    color:white !important;
    height: 60px !important;
    overflow-y: auto;
}
.type_msg:focus{
    box-shadow:none !important;
    outline:0px !important;
}
.attach_btn{
    border-radius: 15px 0 0 15px !important;
    background-color: rgba(0,0,0,0.3) !important;
    border:0 !important;
    color: white !important;
    cursor: pointer;
}
.send_btn{
    border-radius: 0 15px 15px 0 !important;
    background-color: rgba(0,0,0,0.3) !important;
    border:0 !important;
    color: white !important;
    cursor: pointer;
}

.contacts li{
    width: 100% !important;
    padding: 5px 10px;
    margin-bottom: 15px !important;
}
.user_info span{
    font-size: 20px;
    color: white;
}
.user_info p{
    font-size: 10px;
    color: rgba(255,255,255,0.6);
}
.video_cam span{
    color: white;
    font-size: 20px;
    cursor: pointer;
    margin-right: 20px;
}
.action_menu ul{
    list-style: none;
    padding: 0;
    margin: 0;
}
.action_menu ul li{
    width: 100%;
    padding: 10px 15px;
    margin-bottom: 5px;
}
.action_menu ul li i{
    padding-right: 10px;

}
.action_menu ul li:hover{
    cursor: pointer;
    background-color: rgba(0,0,0,0.2);
}
@media(max-width: 576px){
    .contacts_card{
        margin-bottom: 15px !important;
    }
}
.suggestions {
    position: absolute;
    top: 100%;
    left: 0;
    margin-top: 4px;
    padding: 0;
    list-style: none;
    background-color: #f8f8f8;
    border: 1px solid #ccc;
    border-radius: 4px;
}
.suggestions li {
    padding: 8px;
    cursor: pointer;
}
.suggestions li.selected {
    background-color: #e0e0e0;
}
</style>
