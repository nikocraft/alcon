<template>
    <div>
        <div v-if="showContent" class="menu-item-settings">
            <div  class="form-group">
                <label>Item Title</label>
                <input type="text" class="form-control" v-model="item.title">
            </div>
            <!-- <div class="form-group">
                <label>Subtext</label>
                <input type="text" class="form-control" v-model="item.subText">
            </div>
            <div class="form-group">
                <label>Icon</label>
                <input type="text" class="form-control" v-model="item.icon">
            </div> -->
        </div>

    </div>
</template>

<script>
    import GeneralMixin from '../../mixins/GeneralMixin'
    import { mapGetters, mapActions } from 'vuex'

    export default {
        mixins: [GeneralMixin],
        data () {
            return {
                item: {
                    title: '',
                    subText: '',
                    icon: '',
                },
            }
        },
        computed: {
            blockType: {
                get () {
                    return this.$store.getters['menus/editor/itemType'](this.uniqueId)
                }
            },
            settings () {
                return this.$store.getters['menus/editor/itemSettings'](this.uniqueId)
            },
            content () {
                return this.$store.getters['menus/editor/itemContent'](this.uniqueId)
            }
        },
        watch: {
            item: {
                handler(val) {
                    this.updateItemContent({content: val, uniqueId: this.uniqueId})
                },
                deep: true
            },
        },
        methods: {
            ...mapActions('menus/editor', [
                'updateItemContent',
            ])
        },
        mounted () {
            if(this.content) {
                this.item = this.content
            }
        }
    }
</script>
