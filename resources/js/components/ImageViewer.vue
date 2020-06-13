<template>
    <div class="container">
        <div class="row justify-content-center">
<!--            <viewer :images="images">-->
<!--                <img v-for="src in images" :src="src" :key="src">-->
<!--            </viewer>-->
            <viewer :options="options" :images="images"
                    @inited="inited"
                    class="viewer" ref="viewer"
            >
                <template slot-scope="scope">
                    <img v-for="src in scope.images" :src="src" :key="src">
                    {{scope.options}}
                </template>
            </viewer>
            <button type="button" @click="show">Show</button>
        </div>
    </div>
</template>

<script>
    import 'viewerjs/dist/viewer.css'
    import Viewer from "v-viewer/src/component.vue"
    export default {
        mounted() {
            this.loadImages()
        },
        components: {
            Viewer
        },
        data() {
            return {
                images: []
            }
        },
        methods: {
            inited (viewer) {
                this.$viewer = viewer
            },
            show () {
                this.$viewer.show()
            },
            loadImages() {
                axios.get('/user/files').then(response => {
                    this.images = response.data;
                    // viewer.show();
                })
            }
        }
    }
</script>
