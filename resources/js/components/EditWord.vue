<template>
  <div class="mt-6">
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
      <table class="table table-bordered table-striped" v-if="edit_process == false">
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
            <div class="btn btn-danger" @click="deleteWord(item.id)">Xóa</div>
          </td>
        </tr>
        </tbody>
      </table>

      <div class="mt-6" v-if="edit == true">
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
                  placeholder="Nhập từ tiếng anh ..."
                  v-model="edit_word.eng"
              />
            </div>
            <div class="form-group">
              <label for="exampleFormControlInput1">Type</label>
              <input
                  type="text"
                  class="form-control"
                  placeholder="Nhập loại từ ..."
                  v-model="edit_word.type"
              />
            </div>
            <div class="form-group">
              <label for="exampleFormControlInput1">Vietnamese</label>
              <input
                  type="text"
                  class="form-control"
                  placeholder="Nhập nghĩa tiếng việt ..."
                  v-model="edit_word.vi"
              />
            </div>
            <div class="form-group">
              <label for="exampleFormControlTextarea1">Example</label>
              <vue-editor v-model="edit_word.example"></vue-editor>
            </div>
            <div>
              <div class="btn btn-primary mt-3" @click="submitEdit">Lưu</div>
              <div class="btn btn-danger mt-3" @click="clearEdit">Hủy</div>
              <transition name="fade">
                <div class="btn btn-success mt-3" v-if="edit_success">Chỉnh sửa thành công</div>
              </transition>
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
</template>

<script>
import { VueEditor } from "vue2-editor";

export default {
  components: {
    VueEditor,
  },
  props: {
    user_id: Number,
  },
  data() {
    return {
      re_word: null,
      edit_prcess: false,
      edit: false,
      de_: false,
      s_word : null,
      edit_success: false,
      recent_w: { eng: null, type: null },
      edit_word: { id: null, eng: null, type: null, vi: null, example: null },
      edit_process: false,
      e_success: false,
      de_success: false,
    };
  },
  methods: {
    searchWord() {
      this.re_word = null;
      this.de_ = null;
      this.edit = null;
      axios
          .post("/api/search-word", {
            uid: this.user_id,
            word: this.s_word,
          })
          .then((res) => {

            if (res.data != "notfound") {
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
            this.edit_process = null;
            //console.log(res);
          })
          .catch((err) => {
            console.error(err);
          });
    },
    editWord(word) {
      this.edit = true;
      this.recent_w.eng = word.eng;
      this.recent_w.type = word.type;
      this.edit_word.id = word.id;
      this.edit_word.eng = word.eng;
      this.edit_word.type = word.type;
      this.edit_word.vi = word.vi;
      this.edit_word.example = word.example;
      this.edit_process = true;
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
            this.edit_process = true;
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
    clearEdit() {
      this.edit = false;
      this.edit_process = false;
    },
  },
};
</script>

<style scoped>
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
/* Các kiểu riêng cho component này nếu cần */
</style>

