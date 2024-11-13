<template>
  <div class="container">
    <!-- Button Selection -->
    <div class="header">
      <div class="btn btn-primary" @click="choose = 1">Thêm từ mới</div>
      <div class="btn btn-success" @click="showNewWords">Xem danh sách từ mới theo ngày</div>
      <div class="btn btn-danger" @click="showAllWords">Xem toàn bộ danh sách từ mới</div>
      <div class="btn btn-warning" @click="choose = 4">Chỉnh sửa từ</div>
    </div>

    <!-- Add New Word -->
    <NewWordForm v-if="choose === 1" :word="word" :errors="errors" @submit="submitForm" @reset="resetForm" :success="success" :fail="fail" />

    <!-- Words List by Date -->
    <WordsListByDate v-if="choose === 2" :dates="dates" :filter="filter" @getWords="getWords" :wordList="word1" />

    <!-- All Words List -->
    <AllWordsList v-if="choose === 3" :count="count_w" :type="type_" @getAllWords="getAllWords" :wordList="word2" />

    <!-- Edit Word -->
    <EditWord v-if="choose === 4" :searchWord="s_word" @search="searchWord" :re_word="re_word" @editWord="editWord" @deleteWord="deleteWord" :editData="edit_word" @submitEdit="submitEdit" :editSuccess="e_success" :deleteSuccess="de_success" />
  </div>
</template>

<script>
import { VueEditor } from "vue2-editor";
import NewWordForm from './NewWordForm.vue';
import WordsListByDate from './WordsListByDate.vue';
import AllWordsList from './AllWordsList.vue';
import EditWord from './EditWord.vue';

export default {
  components: {
    VueEditor,
    NewWordForm,
    WordsListByDate,
    AllWordsList,
    EditWord,
  },
  props: {
    user_id: Number,
  },
  data() {
    return {
      choose: 3,
      word: { uid: null, eng: null, type: null, vi: null, ex: null },
      errors: { eng: null, type: null, vi: null },
      success: null,
      fail: null,
      dates: null,
      filter: { date: null, type: null },
      word1: null,
      word2: null,
      type_: 'sort1',
      s_word: null,
      re_word: null,
      edit_word: { id: null, eng: null, type: null, vi: null, example: null },
      recent_w: { eng: null, type: null },
      e_success: null,
      de_success: null,
      count_w: null,
    };
  },
  mounted() {
    this.getDates();
    this.getAllWords(this.type_);
  },
  methods: {
    submitForm() {
      this.word.uid = this.user_id;
      axios.post("/api/word", this.word)
          .then(res => {
            if (res.data === "success") {
              this.success = true;
              this.resetForm();
              setTimeout(() => { this.success = false; }, 2000);
            } else {
              this.fail = true;
              setTimeout(() => { this.fail = false; }, 3000);
            }
          })
          .catch(err => {
            this.errors = err.response.data.errors;
            for (const key in this.errors) {
              if (this.errors[key]) {
                this.errors[key] = this.errors[key][0];
              }
            }
          });
    },
    resetForm() {
      this.word = { uid: null, eng: null, type: null, vi: null, ex: null };
    },
    getDates() {
      axios.get("/api/get-dates", { params: { uid: this.user_id } })
          .then(res => {
            if (res.data) {
              this.dates = res.data;
              this.filter.date = this.dates[this.dates.length - 1];
              this.filter.type = "sort1";
            }
          })
          .catch(console.error);
    },
    getWords(date_, type_) {
      axios.get("/api/get-words-with-date", { params: { uid: this.user_id, date: date_, type: type_ } })
          .then(res => { this.word1 = res.data; })
          .catch(console.error);
    },
    getAllWords(type_) {
      axios.get("/api/get-all-words", { params: { uid: this.user_id, type: type_ } })
          .then(res => {
            this.word2 = res.data;
            this.count_w = res.data.length;
          })
          .catch(console.error);
    },
    searchWord() {
      axios.get("/api/search-word", { params: { uid: this.user_id, word: this.s_word } })
          .then(res => { this.re_word = res.data !== "no" ? res.data : null; })
          .catch(console.error);
    },
    deleteWord(id) {
      axios.delete("/api/delete-word", { params: { uid: this.user_id, id: id } })
          .then(res => {
            this.de_success = true;
            setTimeout(() => { this.de_success = false; }, 2000);
          })
          .catch(console.error);
    },
    editWord(word) {
      this.recent_w = { eng: word.eng, type: word.type };
      this.edit_word = { ...word };
    },
    submitEdit() {
      axios.post("/api/edit-word", this.edit_word)
          .then(res => {
            this.searchWord();
            this.e_success = true;
            setTimeout(() => { this.e_success = false; }, 3000);
          })
          .catch(err => {
            this.e_errors = err.response.data.errors;
            for (const key in this.e_errors) {
              if (this.e_errors[key]) {
                this.e_errors[key] = this.e_errors[key][0];
              }
            }
          });
    },
    showNewWords() {
      this.choose = 2;
      this.word1 = null;
      this.getWords(this.filter.date, this.filter.type);
    },
    showAllWords() {
      this.choose = 3;
      this.word2 = null;
      this.getAllWords(this.type_);
    },
  },
};
</script>

<style scoped>
.header {
  margin-bottom: 30px;
}
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.5s;
}
.fade-enter, .fade-leave-to {
  opacity: 0;
}
label {
  font-weight: bold;
}
th, tr, td {
  text-align: center;
  vertical-align: middle;
}
td > p {
  margin: 0 auto;
}
</style>
