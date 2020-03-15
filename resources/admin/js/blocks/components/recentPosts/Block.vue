<template>
    <div class="posts-block-body">
        <div class="post" v-for="post in posts" :key="post.id">
            <!-- <div :style="{
                'background-image': imageUrl(post.featuredimage.large),
                'height': '300px',
                'width': '100%',
                'background-size': 'cover',
                'background-position': 'center'
                }"></div> -->
            <div>{{ post.title }}</div>
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
            queryString() {
                let query = '?per_page=' + this.settings.numberOfPosts
                return query
            },
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
        watch: {
            'settings.numberOfPosts'() {
                this.getPosts()
            }
        },
        methods: {
            imageUrl(url) {
                if(url && this.settings.renderFeaturedImage)
                    return 'url('+url+')'
                else
                    return 'url()'
            },
            getPosts() {
                axios.get(route('api.content.index', {contentTypeId: this.settings.postsType}) + this.queryString)
                    .then((response) => {
                        this.posts = response.data.data
                    })
                    .catch((error) => {
                        console.log(error)
                    })
            }
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
        padding: 5px 10px;
    }
</style>
