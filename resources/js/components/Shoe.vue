<template>
    <div class="container">
        <div class="header">
            <div class="btn btn-primary" @click="choose = 1">Thêm giày mới</div>
            <div class="btn btn-success" @click="choose = 2">Xem toàn bộ giày</div>
            <div class="btn btn-danger" @click="choose = 3">Tìm kiếm</div>
            <div class="btn btn-warning" @click="choose = 4">Chỉnh sửa giày</div>
        </div>
        <add-shoe 
            v-if="choose === 1" 
            @submit="submitForm"
            :user_id="user_id"
        />
    </div>
</template>

<script>
    export default {
        props: {
            user_id: {
                type: Number,
                required: true,
            },
        },
        data() {
            return {
                choose: 1,
            };
        },
        methods: {
            submitForm() {
                console.log('submit form');
            },
            resetForm() {
                console.log('reset form');
            },
            getWords() {
                console.log('get words');
            },
            getAllWords() {
                console.log('get all words');
            },
        },
    };

</script>