<template>
    <div class="col-md-4 mb-4 overflow-hidden">
        <video-player  class="video-player-box mb-2"
                       ref="videoPlayer"
                       :options="playerOptions"
                       :playsinline="false"
                       :fluid="true"
        >
        </video-player>
        <a class="btn btn-primary" :href="fullVersion" target="_blank">Full version</a>
    </div>
</template>

<script>
    import 'video.js/dist/video-js.css'
    import 'vue-video-player/src/custom-theme.css'
    import { videoPlayer } from 'vue-video-player'
    export default {
        name: "MyVideoPlayer",
        props: ['source', 'fullVersion'],
        components: {
            videoPlayer
        },
        data() {
            return {
                playerOptions: {
                    // videojs options
                    muted: true,
                    language: 'en',
                    playbackRates: [0.7, 1.0, 1.5, 2.0],
                    sources: [{
                        type: "video/mp4",
                        src: this.source
                    }],
                    fluid: true
                }
            }
        },
    }
</script>

<style scoped>

</style>
