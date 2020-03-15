<template>
    <div class="posts-block-body">
        <div class="post" v-for="post in posts" :key="post.id">
            <h3 style="margin-bottom: 10px;">{{post.title}}</h3>
            <div v-if="settings.displayFeaturedImage && post.featuredimage" :style="{
                'background-image': imageUrl(post.featuredimage),
                'height': '300px',
                'width': '100%',
                'background-size': 'cover',
                'background-position': 'center'
                }"></div>
        </div>
    </div>
</template>

<script>
    import GeneralMixin from '../../mixins/GeneralMixin'
    import { mapGetters, mapActions } from 'vuex'

    export default {
        mixins: [GeneralMixin],
        data() {
            return {
                posts: null
            }
        },
        computed: {
            imageStyles() {
                let styleObj = {
                    padding: this.settings.padding,
                    backgroundImage: this.settings.backgroundColor,
                    justifyContent: this.settings.imagePosition,
                }
                return styleObj
            },
            blockType: {
                get() {
                    return this.$store.getters[`${this.storePath}/itemType`](this.uniqueId)
                }
            },
            settings: {
                get() {
                    return this.$store.getters[`${this.storePath}/itemSettings`](this.uniqueId)
                }
            },
            content() {
                return this.$store.getters[`${this.storePath}/itemContent`](this.uniqueId)
            }
        },
        methods: {
            imageUrl(url) {
                if(url && this.settings.displayFeaturedImage)
                    return 'url('+url.large+')'
                else
                    return 'url()'
            },
            getPosts() {
                axios.get(route('api.content.index', {contentTypeId: 2}))
                    .then((response) => {
                        this.posts = response.data.data
                    })
                    .catch((error) => {
                        console.log('we got error back')
                        // console.log(error)
                    })
            },
        },
        mounted() {
            // if(this.settings.postsType)
            this.getPosts()
        }
    }
</script>

<style scoped lang="scss">
    .no-image {
        padding: 20px;
        border: 1px dashed rgba(0, 0, 0, 0.2);
        background-color: rgba(0,0,0, 0.12);
        cursor: pointer;
    }

    img {
        cursor: pointer;
    }

    .post {
        margin-bottom: 20px;
    }
</style>
