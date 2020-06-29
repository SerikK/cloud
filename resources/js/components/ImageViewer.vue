<template>
    <div class="container">
        <div class="row">
            <div id="col-md-3">
                <h5>Sorting</h5>
                <div class="form-group">
                    <button @click="sortByProductCode" class="btn btn-info" type="button">By product code</button>
                </div>
                <div class="form-group">
                    <button @click="sortBySequence" class="btn btn-info" type="button">By sequence</button>
                </div>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="photos" v-model="filter.photos" @change="filterChanged">
                    <label class="form-check-label" for="photos">Photos</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="videos" v-model="filter.videos" @change="filterChanged">
                    <label class="form-check-label" for="videos">Videos</label>
                </div>
            </div>
            <div class="col-md-9 justify-content-center">
                <viewer :images="images"
                        @inited="inited"
                        class="viewer row" ref="viewer"
                >
                    <template slot-scope="scope">
                        <div
                            v-for="src in scope.images"
                            :key="src.id"
                            class="col-md-4 mb-4 overflow-hidden"
                            :alt="src.order_number"
                            :title="src.order_number"
                            v-if="src.type === 'image'">
                            <img :src="src.file" class="w-100" />
                        </div>
                        <my-video-player
                            v-for="src in scope.images"
                            :key="src.id"
                            v-if="src.type === 'video'"
                            :source="src.short_version"
                            :full-version="src.file"
                        ></my-video-player>
                        {{scope.options}}
                    </template>
                </viewer>

            </div>
<!--            <button type="button" @click="show">Show</button>-->
        </div>
    </div>
</template>

<script>
    import 'viewerjs/dist/viewer.css'
    import Viewer from "v-viewer/src/component"
    import MyVideoPlayer from "./MyVideoPlayer";
    export default {
        mounted() {
            this.loadImages()
        },
        components: {
            MyVideoPlayer,
            Viewer
        },
        data() {
            return {
                images: [],
                files: [],
                filter: {
                    photos: true,
                    videos: true
                }
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
                    this.files = response.data;
                    this.images = response.data;

                    this.$viewer.show()
                })
            },
            sortByProductCode() {
                this.images.sort(function(a, b) {
                    return b.product_code - a.product_code;
                })
            },
            sortBySequence() {
                this.images.sort(function(a, b) {
                    return b.sequence - a.sequence;
                })
            },
            filterChanged() {
                if (this.filter.photos && this.filter.videos) {
                    this.images = this.files
                } else if (this.filter.photos) {
                    this.images = this.files.filter(file => file.type === 'image')
                } else if (this.filter.videos) {
                    this.images = this.files.filter(file => file.type === 'video')
                } else {
                    this.images = this.files
                }
            }
        }
    }
</script>
