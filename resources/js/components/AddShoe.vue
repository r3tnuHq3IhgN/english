<template>
  <div class="container">
    <form action="">
      <div class="form-group">
        <label for="exampleFormControlInput1">Name</label>
        <input
            type="text"
            class="form-control"
            id="exampleFormControlInput1"
            placeholder="Nhập tên giày ..."
            v-model="formData.name"
            @click="errors.name = null"
            :class="{ 'is-invalid': errors.name }"
        />
        <div class="invalid-feedback">
          {{ errors.name }}
        </div>
      </div>
      <div class="form-group">
        <label for="exampleFormControlInput1">Brand</label>
        <input
            type="text"
            class="form-control"
            id="exampleFormControlInput1"
            placeholder="Nhập hãng ..."
            v-model="formData.brand"
            @click="errors.brand = null"
            :class="{ 'is-invalid': errors.brand }"
        />
        <div class="invalid-feedback">
          {{ errors.brand }}
        </div>
      </div>
      <div class="form-group">
        <label for="exampleFormControlInput1">Size</label>
        <input
            type="text"
            class="form-control"
            id="exampleFormControlInput1"
            placeholder="Nhập size ..."
            v-model="formData.size"
            @click="errors.size = null"
            :class="{ 'is-invalid': errors.size }"
        />
        <div class="invalid-feedback">
          {{ errors.size }}
        </div>
      </div>
      <div class="form-group">
        <label for="exampleFormControlInput1">Price</label>
        <input
            type="text"
            class="form-control"
            id="exampleFormControlInput1"
            placeholder="Nhập giá ..."
            v-model="formData.price"
            @click="errors.price = null"
            :class="{ 'is-invalid': errors.price }"
        />
        <div class="invalid-feedback">
          {{ errors.price }}
        </div>     
      </div>
      <div class="form-group">
        <label for="exampleFormControlInput1">Description</label>
        <input
            type="text"
            class="form-control"
            id="exampleFormControlInput1"
            placeholder="Nhập mô tả ..."
            v-model="formData.description"
            @click="errors.description = null"
            :class="{ 'is-invalid': errors.description }"
        />
        <div class="invalid-feedback">
          {{ errors.description }}
        </div>
      </div>
      <div class="form-group">
        <label for="exampleFormControlInput1">Category</label>
        <input
            type="text"
            class="form-control"
            id="exampleFormControlInput1"
            placeholder="Nhập loại ..."
            v-model="formData.category"
            @click="errors.category = null"
            :class="{ 'is-invalid': errors.category }"
        />
        <div class="invalid-feedback">
          {{ errors.category }}
        </div>
      </div>

      <div class="form-group">
        <label for="exampleFormControlInput1">Image</label>
        <file-pond
          name="images"
          ref="pond"
          label-idle="Drop files here..."
          allow-multiple="true"
          accepted-file-types="image/jpeg, image/png"
          allow-image-preview="true"
          instant-upload="false"
          :server="serverOptions"
          v-on:init="handleFilePondInit"
          v-on:onprocessfile="handleFilePondProcessFile"
          />
      </div>

      <div>
        <div class="btn btn-primary mt-3" @click="submitForm">Submit</div>
        <transition name="fade">
          <div class="btn btn-success mt-3" v-if="success">
            Thêm thành công
          </div>
          <div class="btn btn-danger mt-3" v-if="fail">
            Thêm thất bại
          </div>
        </transition>
      </div>
    </form>
  </div>
  </template>
  
  <script>
  // Import Vue FilePond
  import vueFilePond from "vue-filepond";
  
  // Import FilePond styles
  import "filepond/dist/filepond.min.css";
  
  // Import FilePond plugins
  // Please note that you need to install these plugins separately
  
  // Import image preview plugin styles
  import "filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css";
  
  // Import image preview and file type validation plugins
  import FilePondPluginFileValidateType from "filepond-plugin-file-validate-type";
  import FilePondPluginImagePreview from "filepond-plugin-image-preview";
import axios from "axios";
  
  // Create component
  const FilePond = vueFilePond(
    FilePondPluginFileValidateType,
    FilePondPluginImagePreview
  );
  
  export default {
    props: {
      user_id: {
        type: Number
      },
    },
    data() {
      return {
        success: false,
        fail: false,
        shoe_id: null,
        formData: {
          user_id: null,
          name: null,
          size: null,
          brand: null,
          price: null,
          description: null,
          category: null,
        },
        errors: {
          name: null,
          size: null,
          brand: null,
          price: null,
          description: null,
          category: null,
        },
        serverOptions: {
          process: {
            url: '/api/shoe/create-shoe-images',
            method: 'POST',
            headers: {
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            ondata: (formData) => {
              formData.append('shoe_id', this.shoe_id);
              return formData;
            },
            onload: (response) =>{ 
              //console.log(JSON.parse(response));
              console.log(response);
              this.success = true;
            },
            onerror: (error) => console.error('Lỗi khi tải lên:', error)
          }
        },
      };
    },
    methods: {
      handleFilePondInit() {
        console.log("FilePond has initialized");
      },
      handleFilePondProcessFile() {

        console.log("File has been processed");
      },
      submitForm() {
        try {
          this.formData.user_id = this.user_id;
          console.log(this.formData);
          axios.post("/api/shoe/create", this.formData)
          .then((res) => {
            if(res.data) {
              this.shoe_id = res.data.id;
              const pond = this.$refs.pond;
              const files = pond.getFiles();
              pond.processFiles();
            }
            this.success = true;
            this.fail = false;
            this.resetForm();
          })
          .catch((error) => {
            this.fail = true;
            this.success = false;
            this.errors = error.response.data.errors;
            for (const key in this.errors) {
              if (this.errors[key]) {
                this.errors[key] = this.errors[key][0];
              }
            }
          });
        } catch (error) {
          console.log(error);
        }
        // const files = this.$refs.pond.getFiles();
        // console.log(files);
        // const pond = this.$refs.pond;
        // console.log(pond);

        //await pond.processFiles(); 
      },
      resetForm() {
        this.formData = {
          user_id: null,
          name: null,
          size: null,
          brand: null,
          price: null,
          description: null,
          category: null,
        };
      },
    },
    components: {
      FilePond,
    },
  };
  </script>
  
  <!-- Add "scoped" attribute to limit CSS to this component only -->
  <style scoped>
  h3 {
    margin: 40px 0 0;
  }
  ul {
    list-style-type: none;
    padding: 0;
  }
  li {
    display: inline-block;
    margin: 0 10px;
  }
  a {
    color: #42b983;
  }
  </style>
  