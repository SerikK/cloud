<template>
    <div class="container">
        <div class="row justify-content-center">
            <viewer :images="images">
                <img v-for="src in images" :src="src" :key="src">
            </viewer>
        </div>
    </div>
</template>

<script>
    import 'viewerjs/dist/viewer.css'
    export default {
        mounted() {
            this.loadImages()
        },
        data() {
            return {
                images: []
            }
        },
        methods: {
            loadImages() {
                axios.get('/user/files').then(response => {
                    this.images = response.data;
                })
            }
        }
    }
</script>
