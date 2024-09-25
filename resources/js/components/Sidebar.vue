<template>
  <div id="app">
    <button @click="setEditorContent">Set Editor Contents</button>
    <vue-editor v-model="htmlForEditor"></vue-editor>
  </div>
</template>

<script>
import { VueEditor } from "vue2-editor";

export default {
  components: {
    VueEditor
  },

  data() {
    return {
      htmlForEditor: null
    };
  },

  methods: {
    setEditorContent: function() {
      this.htmlForEditor = "<h1>Html For Editor</h1>";
    }
  }
};
</script>