<template>
    <div v-if="contact">
        <div class="input-wrapper" ref="inputWrapper">
            <input v-model="inputValue" @input="handleInput" placeholder="Message" @keydown.down="selectNextSuggestion" @keydown.up="selectPreviousSuggestion" @keydown.enter="applySelectedSuggestion" />
            <ul class="suggestions" v-if="showSuggestions">
                <li v-for="(suggestion, index) in filteredSuggestions" :key="suggestion" :class="{ 'selected': index === selectedSuggestionIndex }" @click="applySuggestion(suggestion)">
                    {{ suggestion }}
                </li>
            </ul>
            <button @click="send">
                <i class="fa fa-paper-plane"></i>
            </button>
        </div>
    </div>
</template>

<script>
export default {
    props:{
        contact:{
            type:Object
        },
    },
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
        filteredSuggestions() {
            return this.suggestions.filter(suggestion =>
                suggestion.toLowerCase().startsWith(this.inputValue.toLowerCase())
            );
        },
    },
    methods: {
        send(e){
            e.preventDefault();
            if(this.inputValue === ''){
                return;
            }

            this.$emit('send', this.inputValue);
            this.inputValue = '';
        },
        handleInput() {
            this.showSuggestions = false;
            this.selectedSuggestionIndex = -1;

            if (this.inputValue.startsWith('/') ){
                const enteredText = this.inputValue.substring(1);
                this.suggestions = this.dictionary.filter((word) => word.startsWith(enteredText));
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
.input-wrapper {
    position: relative;
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
