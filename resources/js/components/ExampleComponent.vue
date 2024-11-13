<template>
  <div class="container">
    <!-- btn lua chon -->
    <div class="header">
      <div class="btn btn-primary" @click="choose = 1">Thêm từ mới</div>
      <div
        class="btn btn-success"
        @click="
          choose = 2;
          word1 = null;
          getWords(filter.date, filter.type);
        "
      >
        Xem danh sách từ mới theo ngày
      </div>
      <div
        class="btn btn-danger"
        @click="
          choose = 3;
          word2 = null;
          type_ = 'sort1';
          getAllWords(type_);
        "
      >
        Xem toàn bộ danh sách từ mới
      </div>
      <div
        class="btn btn-warning"
        @click="
          choose = 4;
          re_word = null;
          s_word = null;
        "
      >
        Chỉnh sửa từ
      </div>
    </div>
    <add-word
        v-if="choose === 1"
        :word="word"
        :errors="errors"
        @submit="submitForm"
        @reset="resetForm"
        :success="success"
        :fail="fail"
    />
    <sort-word
        v-if="choose === 2"
        :dates="dates"
        :filter="filter"
        @getWords="getWords"
        :word1="word1"
      />

    <all-words
        v-if="choose === 3"
        :count_w="count_w"
        @getAllWords="getAllWords"
        :word2="word2"
      />

    <edit-word
        v-if="choose === 4"
        :user_id="user_id"
      />

  </div>
</template>

<script>
import { VueEditor } from "vue2-editor";
export default {
  components: {
    VueEditor,
  },
  mounted() {
    this.type_ = "sort1";
    this.getDates();
    this.getAllWords("sort1");
  },
  props: {
    user_id: Number,
  },
  data() {
    return {
      choose: 4,
      word: { uid: null, eng: null, type: null, vi: null, ex: null },
      errors: { eng: null, type: null, vi: null },
      e_errors: { eng: null, type: null, vi: null },
      success: null,
      fail: null,
      dates: null,
      filter: { date: null, type: null },
      word1: null,
      word2: null,
      type_: null,
      //
      // s_word: null,
      // re_word: null,
      // de_success: null,
      // de_: null,
      // edit: null,
      // edit_word: { id: null, eng: null, type: null, vi: null, example: null },
      // e_tg: null,
      // recent_w: { eng: null, type: null },
      // e_success: null,
      count_w: null,
    };
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
      axios
        .get("/api/get-dates", {
          params: {
            uid: this.user_id,
          },
        })
        .then((res) => {
          //console.log(res.data);
          if (res.data != null) {
            this.dates = res.data;
            this.filter.date =
              this.dates[this.dates.length - 1];
            this.filter.type = "sort1";
          }
          //console.log(res);
        })
        .catch((err) => {
          console.log(err);
        });
    },
    getWords(date_, type_) {
      axios
        .get("/api/get-words-with-date", {
          params: {
            uid: this.user_id,
            date: date_,
            type: type_,
          },
        })
        .then((res) => {
          this.word1 = res.data;
        })
        .catch((err) => {
          console.error(err);
        });
    },
    getAllWords(type_) {
      axios
        .get("/api/get-all-words", {
          params: {
            uid: this.user_id,
            type: type_,
          },
        })
        .then((res) => {
          this.word2 = res.data;
          this.count_w = res.data.length;
          //console.log(this.word2);
          //console.log(this.count_w);
        })
        .catch((err) => {
          console.error(err);
        });
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
.fade-enter, .fade-leave-to /* .fade-leave-active below version 2.1.8 */ {
  opacity: 0;
}
label {
  font-weight: bold;
}
th,
tr,
td {
  text-align: center;
  vertical-align: middle;
}
td > p {
  margin: 0px auto;
}
</style>
