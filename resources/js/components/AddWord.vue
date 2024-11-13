<template>
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
        <div class="btn btn-success mt-3" v-if="success">
          Thêm thành công
        </div>
        <div class="btn btn-danger mt-3" v-if="fail">
          Từ đã có !!
        </div>
      </transition>
    </div>
  </form>
</template>
<script>
import { VueEditor } from "vue2-editor";
export default {
  components: {
    VueEditor,
  },
  props: {
    word: Object,
    errors: Object,
    success: Boolean,
    fail: Boolean,
  },
  methods: {
    clearError(field) {
      this.$emit("clearError", field);
    },
    submitForm() {
      this.$emit("submit");
    },
  },
};
</script>