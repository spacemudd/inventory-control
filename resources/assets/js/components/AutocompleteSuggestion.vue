<template>
    <div class="textarea">
        <div class="editable-box"
             ref="jobDescription"
             contenteditable="true"
             @keydown.tab="acceptSuggestion"
             @input="emitChanges"
             @blur="suggestion=null">
            <span contenteditable="false"
                  v-if="suggestion"
                  class="preview">
                {{ suggestion }}
            </span>
        </div>
    </div>
</template>

<script>
  export default {
    data() {
      return {
        text: '',
        suggestion: '',
      }
    },
    watch: {
      text(val) {
        this.suggestion=null;
        if (val) {
          this.findSuggestion();
        } else {
          this.suggestion = null;
        }
      },
    },
    mounted() {

    },
    methods: {
      emitChanges(event) {
        let text = event.target.childNodes[0].textContent;
        this.text = text;
        this.$emit('input', text);
      },
      findSuggestion() {
        if(this.text.startsWith('%20')) {
          this.suggestion = null;
          return false;
        }

        axios.get(this.apiUrl()+'/search/job-description?q='+this.text).then(response => {
          this.suggestion = response.data.suggestion;
        })
      },
      acceptSuggestion(event) {
        if (this.suggestion) {
          event.preventDefault();
          let suggestion = this.suggestion;
          this.text = this.text + suggestion;
          this.suggestion = null;
          this.$emit('input', this.text);
          this.$refs.jobDescription.textContent = this.text;
        }
      }
    }
  }
</script>

<style lang="scss">
    .preview {
        user-modify: read-only;
        -moz-user-modify: read-only;
        -webkit-user-modify: read-only;
        -webkit-user-drag: none;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
        color: #757575;
        padding-left: 10px;
    }
</style>
