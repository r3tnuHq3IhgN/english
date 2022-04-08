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

    <!-- them tu moi -->
    <div class="mt-6" v-if="choose == 1">
      <form>
        <div class="form-group">
          <label for="exampleFormControlInput1">English</label>
          <input
            type="text"
            class="form-control"
            id="exampleFormControlInput1"
            placeholder="Nhập từ tiếng anh ..."
            v-model="word.eng"
            @click="errors.eng = null"
            :class="{ 'is-invalid': errors.eng }"
          />
          <div class="invalid-feedback">
            {{ errors.eng }}
          </div>
        </div>
        <div class="form-group">
          <label for="exampleFormControlInput1">Type</label>
          <input
            type="text"
            class="form-control"
            id="exampleFormControlInput1"
            placeholder="Nhập loại từ ..."
            v-model="word.type"
            @click="errors.type = null"
            :class="{ 'is-invalid': errors.type }"
          />
          <div class="invalid-feedback">
            {{ errors.type }}
          </div>
        </div>
        <div class="form-group">
          <label for="exampleFormControlInput1">Vietnamese</label>
          <input
            type="text"
            class="form-control"
            id="exampleFormControlInput1"
            placeholder="Nhập nghĩa tiếng việt ..."
            v-model="word.vi"
            @click="errors.vi = null"
            :class="{ 'is-invalid': errors.vi }"
          />
          <div class="invalid-feedback">
            {{ errors.vi }}
          </div>
        </div>
        <div class="form-group">
          <label for="exampleFormControlTextarea1">Example</label>
          <vue-editor v-model="word.ex"></vue-editor>
        </div>
        <div>
          <div class="btn btn-primary mt-3" @click="submitForm">Submit</div>
          <transition name="fade">
            <div class="btn btn-success mt-3" v-if="success == true">
              Thêm thành công
            </div>
            <div class="btn btn-danger mt-3" v-if="fail == true">
              Từ đã có !!
            </div>
          </transition>
        </div>
      </form>
    </div>

    <!-- danh sach theo ngay -->
    <div class="mt-6" v-if="choose == 2">
      <h2>Danh sách các từ mời theo ngày</h2>
      <select
        class="form-select mb-3"
        aria-label="Default select example"
        v-model="filter.date"
        @change="getWords(filter.date, filter.type)"
      >
        <option v-for="(date, index) in dates" :key="index">
          {{ date.date_created }}
        </option>
      </select>
      <select
        class="form-select mb-3"
        aria-label="Default select example"
        v-model="filter.type"
        @change="getWords(filter.date, filter.type)"
      >
        <option value="sort2" selected>Theo thứ tự Alphabe</option>
        <option value="sort1">Theo thứ tự được thêm vào</option>
      </select>
      <table class="table table-bordered table-striped" v-show="word1 != null">
        <thead class="thead-dark">
          <tr>
            <th scope="col" class="col-md-2">English</th>
            <th scope="col" class="col-md-1">Type</th>
            <th scope="col" class="col-md-4">Vietnamese</th>
            <th scope="col" class="col-md-5">Example</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(item, index) in word1" :key="index">
            <th scope="row">{{ item.eng }}</th>
            <td>( {{ item.type }} )</td>
            <td>{{ item.vi }}</td>
            <td v-html="item.example"></td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- toan bo danh sach -->
    <div class="mt-6" v-if="choose == 3">
      <h2>Tất cả các từ mới</h2>
      <select
        class="form-select mb-3"
        aria-label="Default select example"
        v-model="type_"
        @change="getAllWords(type_)"
      >
        <option value="sort2" selected>Theo thứ tự Alphabe</option>
        <option value="sort1">Theo thứ tự được thêm vào</option>
      </select>
      <table class="table table-bordered table-striped" v-show="word2 != null">
        <thead class="thead-dark">
          <tr>
            <th scope="col" class="col-md-2">English</th>
            <th scope="col" class="col-md-1">Type</th>
            <th scope="col" class="col-md-4">Vietnamese</th>
            <th scope="col" class="col-md-5">Example</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(item, index) in word2" :key="index">
            <th scope="row">{{ item.eng }}</th>
            <td>( {{ item.type }} )</td>
            <td>{{ item.vi }}</td>
            <td v-html="item.example"></td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- chinh sua tu -->
    <div class="mt-6" v-if="choose == 4">
      <h2>Tìm kiếm từ muốn chỉnh sửa</h2>
      <div class="mb-3">
        <input
          type="text"
          class="form-control"
          v-model="s_word"
          placeholder="Nhập từ muốn tìm kiếm ..."
        />
        <div class="btn btn-primary mt-3" @click="searchWord">Search</div>
      </div>

      <div v-if="re_word != null">
        <table class="table table-bordered table-striped" v-if="de_ != true">
          <thead class="thead-dark">
            <tr>
              <th scope="col" class="col-md-2">English</th>
              <th scope="col" class="col-md-1">Type</th>
              <th scope="col" class="col-md-4">Vietnamese</th>
              <th scope="col" class="col-md-5">Remove</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(item, index) in re_word" :key="index">
              <th scope="row">{{ item.eng }}</th>
              <td>( {{ item.type }} )</td>
              <td>{{ item.vi }}</td>
              <td>
                <div class="btn btn-primary" @click="editWord(item)">Sửa</div>
                <div class="btn btn-danger" @click="deleteWord(item.id)">
                  Xóa
                </div>
              </td>
            </tr>
          </tbody>
        </table>

        <div class="mt-6" v-if="edit == true && e_success != true">
          <div class="btn btn-danger">
            Bạn đang sửa từ: {{ recent_w.eng }} ( {{ recent_w.type }} )
          </div>
          <br />
          <br />
          <div class="d-block mt-6">
            <form>
              <div class="form-group">
                <label for="exampleFormControlInput1">English</label>
                <input
                  type="text"
                  class="form-control"
                  id="exampleFormControlInput1"
                  v-model="edit_word.eng"
                  @click="e_errors.eng = null"
                  :class="{ 'is-invalid': e_errors.eng }"
                />
                <div class="invalid-feedback">
                  {{ e_errors.eng }}
                </div>
              </div>
              <div class="form-group">
                <label for="exampleFormControlInput1">Type</label>
                <input
                  type="text"
                  class="form-control"
                  id="exampleFormControlInput1"
                  v-model="edit_word.type"
                  @click="e_errors.type = null"
                  :class="{ 'is-invalid': e_errors.type }"
                />
                <div class="invalid-feedback">
                  {{ e_errors.type }}
                </div>
              </div>
              <div class="form-group">
                <label for="exampleFormControlInput1">Vietnamese</label>
                <input
                  type="text"
                  class="form-control"
                  id="exampleFormControlInput1"
                  v-model="edit_word.vi"
                  @click="e_errors.vi = null"
                  :class="{ 'is-invalid': e_errors.vi }"
                />
                <div class="invalid-feedback">
                  {{ e_errors.vi }}
                </div>
              </div>
              <div class="form-group">
                <label for="exampleFormControlTextarea1">Example</label>
                <vue-editor v-model="edit_word.example"></vue-editor>
              </div>
              <div>
                <div
                  class="btn btn-primary mt-3"
                  @click="submitEdit()"
                  v-if="
                    edit_word.eng != e_tg.eng ||
                    edit_word.type != e_tg.type ||
                    edit_word.vi != e_tg.vi ||
                    edit_word.example != e_tg.example
                  "
                >
                  Submit
                </div>
                <button
                  class="btn btn-primary mt-3"
                  v-show="
                    edit_word.eng == e_tg.eng &&
                    edit_word.type == e_tg.type &&
                    edit_word.vi == e_tg.vi &&
                    edit_word.example == e_tg.example
                  "
                  disabled
                >
                  Submit
                </button>
              </div>
            </form>
          </div>
        </div>
        <transition name="fade">
          <div class="btn btn-success mt-3" v-if="e_success == true">
            Chỉnh sửa thành công !
          </div>
        </transition>
        <transition name="fade">
          <div class="btn btn-success" v-if="de_success == true">
            Xóa từ thành công !
          </div>
        </transition>
      </div>
    </div>
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
      choose: 3,
      word: {
        uid: null,
        eng: null,
        type: null,
        vi: null,
        ex: null,
      },
      errors: {
        eng: null,
        type: null,
        vi: null,
      },
      e_errors: {
        eng: null,
        type: null,
        vi: null,
      },
      success: null,
      fail: null,
      dates: null,
      filter: {
        date: null,
        type: null,
      },
      word1: null,
      word2: null,
      type_: null,
      s_word: null,
      re_word: null,
      de_success: null,
      de_: null,
      edit: null,
      edit_word: {
        id: null,
        eng: null,
        type: null,
        vi: null,
        example: null,
      },
      e_tg: null,
      recent_w: {
        eng: null,
        type: null,
      },
      e_success: null,
    };
  },
  methods: {
    submitForm() {
      this.word.uid = this.user_id;
      axios
        .post("/api/word", this.word)
        .then((res) => {
          //console.log(res);
          if (res.data == "success") {
            this.success = true;
            this.word.eng = null;
            this.word.type = null;
            this.word.vi = null;
            this.word.ex = null;
            setTimeout(() => {
              this.success = false;
            }, 2000);
          } else {
            //console.log(res);
            this.fail = true;
            setTimeout(() => {
              this.fail = false;
            }, 3000);
          }
        })
        .catch((err) => {
          this.errors = err.response.data.errors;
          if (this.errors.eng != null) {
            this.errors.eng = this.errors["eng"][0];
          }
          if (this.errors.type != null) {
            this.errors.type = this.errors["type"][0];
          }
          if (this.errors.vi != null) {
            this.errors.vi = this.errors["vi"][0];
          }
        });
    },
    getDates() {
      axios
        .get("/api/get-dates", {
          params: {
            uid: this.user_id,
          },
        })
        .then((res) => {
          if (res.data != null) {
            this.dates = res.data;
            this.filter.date =
              this.dates[this.dates.length - 1]["date_created"];
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
          //console.log(this.word1);
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
          //console.log(this.word2);
        })
        .catch((err) => {
          console.error(err);
        });
    },
    searchWord() {
      this.re_word = null;
      this.de_ = null;
      this.edit = null;
      axios
        .get("/api/search-word", {
          params: {
            uid: this.user_id,
            word: this.s_word,
          },
        })
        .then((res) => {
          if (res.data != "no") {
            this.re_word = res.data;
          }
          //console.log(this.re_word);
        })
        .catch((err) => {
          console.error(err);
        });
    },
    deleteWord(id) {
      axios
        .delete("/api/delete-word", {
          params: {
            uid: this.user_id,
            id: id,
          },
        })
        .then((res) => {
          this.de_success = true;
          this.de_ = true;
          setTimeout(() => {
            this.de_success = false;
          }, 2000);

          //console.log(res);
        })
        .catch((err) => {
          console.error(err);
        });
    },
    editWord(word) {
      this.edit = true;
      this.e_tg = word;
      this.recent_w.eng = word.eng;
      this.recent_w.type = word.type;
      this.edit_word.id = word.id;
      this.edit_word.eng = word.eng;
      this.edit_word.type = word.type;
      this.edit_word.vi = word.vi;
      this.edit_word.example = word.example;
    },
    submitEdit() {
      axios
        .post("/api/edit-word", this.edit_word)
        .then((res) => {
          this.searchWord();
          this.e_success = true;
          setTimeout(() => {
            this.e_success = false;
          }, 3000);
          this.edit = null;
        })
        .catch((err) => {
          this.e_errors = err.response.data.errors;
          if (this.e_errors.eng != null) {
            this.e_errors.eng = this.e_errors["eng"][0];
          }
          if (this.e_errors.type != null) {
            this.e_errors.type = this.e_errors["type"][0];
          }
          if (this.e_errors.vi != null) {
            this.e_errors.vi = this.e_errors["vi"][0];
          }
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
